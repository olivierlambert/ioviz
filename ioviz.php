<?php


// create datastructure
function parse_data($filename)
{
	// auto parse IOzone file to generate array
	$write_tests = array();
	$read_tests = array();
	$random_read_tests = array();
	$random_write_tests = array();
	$reclen = array();

	$pattern = '/\d+/';
	foreach (file(dirname(__FILE__).$filename) as $line)
	{
		$line = preg_split('/\s+/', trim($line));
		if (preg_match($pattern,$line[0]) == 1) {
			$reclen[$line[0]][] = $line[1];
			$write_tests[$line[0]][] = round($line[2]/1024);
			$read_tests[$line[0]][] = round($line[4]/1024);
			$random_read_tests[$line[0]][] = $line[6];
			$random_write_tests[$line[0]][] = $line[7];
			}
	}
	return array($reclen,$write_tests,$read_tests,$random_read_tests,$random_write_tests);
}

function create_graph_data($bench_name,$bench_data)
{
	$fp = fopen("data/graph_data_".$bench_name.".js","w");
	$i = 1;
	$graph_data = null;
	foreach ($bench_data as $key => $bench_data) {
		$graph_data .= "{data: ".$bench_name.$i.",label: '".$key."'},";
		fwrite($fp,"var ".$bench_name.$i." = [];\n");
		$j = 0;
		foreach ($bench_data as $result) {
	        fwrite($fp,$bench_name.$i.".push([".$j.",$result]);\n");
	        $j++;
	    }
	    $i++;
	}
	fclose($fp);
	return $graph_data;
}

function tick_generator($bench_data)
{
	// tick generator
	$t = 0;
	$tick_data = null;
	foreach (end($bench_data) as $reclen_value) {
		$tick_data .= "[".$t.",'".$reclen_value."'],";
	    $t++;
	}
	return $tick_data;
}

function draw_graph($tick_data,$graph_data,$container,$title,$subtitle)
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

	return $javascript;
	
}
/*
// read graph and data : TODO factorize
$i = 1;
$graph_data_read = null;
foreach ($read_tests as $key => $read_test) {
	$graph_data_read .= "{data: r".$i.",label: '".$key."'},";
	fwrite($fp,"var r".$i." = [];\n");
	$j = 0;
	foreach ($read_test as $result) {
        fwrite($fp,"r".$i.".push([".$j.",$result]);\n");
        $j++;
    }
    $i++;
}*/


?>
