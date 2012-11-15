<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <head>
    <style type="text/css">
.flot {
    height: 480px;
    width: 640px;
    float: left;
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
<div class="flot" id="editor-render-write"></div>
<div class="flot" id="editor-render-read"></div>
    <script type="text/javascript" src="js/flotr2.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript">
	
<?php
// auto parse IOzone file to generate array
$write_tests = array();
$read_tests = array();
$random_read_tests = array();
$random_write_tests = array();

$reclen = array();

$pattern = '/\d+/';

// create datastructure
foreach (file(dirname(__FILE__).'/data/resultlocal.txt') as $line)
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

// write graph and data : TODO factorize
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

// read graph and data : TODO factorize
$i = 1;
$graph_data_read = null;
foreach ($read_tests as $key => $read_test) {
	$graph_data_read .= "{data: r".$i.",label: '".$key."'},";
	echo ("var r".$i." = [];\n");
	$j = 0;
	foreach ($read_test as $result) {
        echo ("r".$i.".push([".$j.",$result]);\n");
        $j++;
    }
    $i++;
}

// tick generator
$t = 0;
$tick_data = null;
foreach (end($reclen) as $reclen_value) {
	$tick_data .= "[".$t.",'".$reclen_value."'],";
    $t++;
}

?>

// first graph
(function basic(container) {

    var ticks = [
            <?php echo $tick_data."\n";?>
            ],
    graph = Flotr.draw(container, 
    [
		<?php echo $graph_data."\n"; ?>
    ], 
    
    {xaxis: {ticks: ticks,},grid: {verticalLines: false,backgroundColor: {colors: [[0, '#fff'],[1, '#ccc']],start: 'top',end: 'bottom'}},
    legend: {position: 'ne'},title: 'Zfs on NFS Linux client',subtitle: 'WRITER Perfs in Mbytes/s'});})
    
    (document.getElementById("editor-render-write"));
    
// second graph
(function basic(container) {

    var ticks = [
            <?php echo $tick_data."\n";?>
            ],
    graph = Flotr.draw(container, 
    [
		<?php echo $graph_data_read."\n"; ?>
    ], 
    
    {xaxis: {ticks: ticks,},grid: {verticalLines: false,backgroundColor: {colors: [[0, '#fff'],[1, '#ccc']],start: 'top',end: 'bottom'}},
    legend: {position: 'ne'},title: 'Zfs on NFS Linux client',subtitle: 'READER Perfs in Mbytes/s'});})
    
    (document.getElementById("editor-render-read"));
</script>
  </body>
</html>
