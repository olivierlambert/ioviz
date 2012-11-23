<?php

class Ioviz
{
	private $iozone_file;
	private $bench_name;
	private $bench_description;
	private $bench_data;
	private $display_graph;
	private $labels;

	public function __construct($iozone_file,$bench_name,$bench_description)
	{
		$this->iozone_file = $iozone_file;
		$this->bench_name = $bench_name;
		$this->bench_description = $bench_description;
		$this->labels = ["KB","Reclen","Write","Rewrite","Read","Reread","Randomread","Randomwrite","Bkwdread","Recordrewrite","Strideread","Fwrite","Frewrite","Fread","Freread"];
		
		// insert js data
		$this->display_graph .= '<script type="text/javascript" src="data/graph_data_'.$this->bench_name.'.js"></script>';
		$this->parse_data();
		$this->get_data();
		$this->create_all_graph();
	}
	
	public function get_data()
	{
		return $this->bench_data;
	}

	public function parse_data()
	{
		// auto parse IOzone file to generate array
		$kb = array();
		$reclen = array();
		$w = array();
		$rw = array();
		$r = array();
		$rer = array();
		$randr = array();
		$randw = array();
		$bkwdr = array();
		$rdrw = array();
		$str = array();
		$fw = array();
		$frw = array();
		$fre = array();
		$frer = array();
		$pattern = '/\d+/';
		foreach (file(dirname(__FILE__).$this->iozone_file) as $line)
		{
			$line = preg_split('/\s+/', trim($line));
			if (preg_match($pattern,$line[0]) == 1)
			{
				$kb[$line[0]][] = $line[0];
				$reclen[$line[0]][] = $line[1];
				$w[$line[0]][] = round($line[2]/1024);
				$rw[$line[0]][] = round($line[3]/1024);
				$r[$line[0]][] = round($line[4]/1024);
				$rer[$line[0]][] = round($line[5]/1024);
				$randr[$line[0]][] = round($line[6]/1024);
				$randw[$line[0]][] = round($line[7]/1024);
				$bkwdr[$line[0]][] = round($line[8]/1024);
				$rdrw[$line[0]][] = round($line[9]/1024);
				$str[$line[0]][] = round($line[10]/1024);
				$fw[$line[0]][] = round($line[11]/1024);
				$frw[$line[0]][] = round($line[12]/1024);
				$fre[$line[0]][] = round($line[13]/1024);
				$frer[$line[0]][] = round($line[14]/1024);
			}
		}
		$result = array($kb,$reclen,$w,$rw,$r,$rer,$randr,$randw,$bkwdr,$rdrw,$str,$fw,$frw,$fre,$frer);
		
		$this->bench_data = array_combine($this->labels,$result);
	}
	
	public function create_all_graph()
	{
		$tick = $this->tick_generator($this->bench_data["Reclen"]);
		
		$result = array_shift($this->bench_data);
		$result = array_shift($this->bench_data);
		$fp = fopen("data/graph_data_".$this->bench_name.".js","w");
		
		
		foreach ($this->bench_data as $key => $bench)
		{
			$graph_title = $key;
			//print_r($bench);
			$data = $this->create_graph_data($fp,$graph_title,$bench);
			$this->draw_graph($tick,$data,$graph_title,$graph_title,$this->bench_description);	
		}
		fclose($fp);
	}
	
	public function get_graph()
	{
		echo $this->display_graph;
	}
	
	public function create_graph_data($fp,$bench_name,$bench_data)
	{
		$i = 1;
		$graph_data = null;
		foreach ($bench_data as $key => $bench_data)
		{
			$graph_data .= "{data: ".$bench_name.$i.",label: '".$key."'},";
			fwrite($fp,"var ".$bench_name.$i." = [];\n");
			$j = 0;
			foreach ($bench_data as $result)
			{
		        fwrite($fp,$bench_name.$i.".push([".$j.",$result]);\n");
		        $j++;
		    }
		    $i++;
		}
		return $graph_data;
	}
	
	public function draw_graph($tick_data,$graph_data,$container,$title,$subtitle)
	{
		$javascript = '
		<div class="flot" id="'.$container.'"></div>
		<script type="text/javascript">
	
		(function basic(container) {
	
	    var ticks = [';
	    $javascript .= $tick_data;
	    $javascript .= '],
	    graph = Flotr.draw(container, 
	    [';
	    $javascript .= $graph_data;
	    $javascript .= '], 
	    
	    {xaxis: {ticks: ticks,},grid: {verticalLines: true,backgroundColor: {colors: [[0, \'#fff\'],[1, \'#eee\']],start: \'top\',end: \'bottom\'}},
	    legend: {position: \'ne\'},spreadsheet: {show: true},title: \''.$title.'\',subtitle: \''.$subtitle.'\'});})
	    
	    (document.getElementById("'.$container.'"));
	    
		</script>';

		$this->display_graph .= $javascript;
	}
	
	private function tick_generator($bench_data)
	{
		// tick generator
		$t = 0;
		$tick_data = null;
		foreach (end($bench_data) as $reclen_value)
		{
			$tick_data .= "[".$t.",'".$reclen_value."'],";
		    $t++;
		}
		return $tick_data;
	}
}
?>
