<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <head>
    <style type="text/css">
.flot {
    height: 480px;
    width: 640px;
}
.flotr-mouse-value {
    opacity: 1 !important;
    background-color: #FFFFFF !important;
    color: #666 !important;
    font-size: 12px;
    border: 1px solid #778b9f;
    font-weight: 600;
}
    </style>
  </head>
  <body>
<div class="flot" id="editor-render-0"></div>
    <script type="text/javascript" src="js/flotr2.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript">
	
<?php
// TODO auto parse IOzone file to generate array
/// JFT
$write_tests = array();
$read_tests = array();
$pattern = '/\d+/';

foreach (file(dirname(__FILE__).'/data/resultth.txt') as $line)
{
	$line = preg_split('/\s+/', trim($line));
	if (preg_match($pattern,$line[0]) == 1) {
		$write_tests[$line[0]][] = $line[2];
		$read_tests[$line[0]][] = $line[4];
		}
}

//print_r($write_tests);


// TODO generate auto ticks for graph

// writer graph
$i = 1;
$graph_data = null;
foreach ($write_tests as $key => $write_test) {
	$graph_data .= "{data: w".$i.",label: '".$key."'},";
	echo ("var w".$i." = [];\n");
	$j = 0;
	foreach ($write_test as $result) {
        echo ("w".$i.".push([".$j.",$result]);\n");
        $j++;
    }
    $i++;
}
?>

(function basic(container) {

    var ticks = [
            [0,"4"], [1,"8"], [2,"16"], [3,"32"], [4,"64"], [5,"128"], [6,"256"], [7,"512"], [8,"1024"], [9,"2048"], [10,"4096"], [11,"8192"], [12,"16384"]
        ],



    graph = Flotr.draw(container, 
    [
		<?php echo $graph_data."\n"; ?>
    ], 
    
    {xaxis: {ticks: ticks,},grid: {verticalLines: false,backgroundColor: {colors: [[0, '#fff'],[1, '#ccc']],start: 'top',end: 'bottom'}},
    legend: {position: 'ne'},title: 'Zfs on NFS Linux client',subtitle: 'WRITER Perfs in kbytes/s'});})
    
    (document.getElementById("editor-render-0"));
</script>
  </body>
</html>
