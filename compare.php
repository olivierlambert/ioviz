<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <head>
    <link rel="stylesheet" href="style.css" type="text/css" />
  </head>
  <body>
<div class="flot" id="editor-render-write"></div>
<div class="flot" id="editor-render-read"></div>
<div class="flot" id="editor-render-compare"></div>
    <script type="text/javascript" src="js/flotr2.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="data/graph_data.js"></script>

<script type="text/javascript">
	
<?php
require_once("ioviz.php");
$iozone = parse_data("/data/resultth.txt");
$graph_data = create_graph_data("write",$iozone[1]);
$tick_data = tick_generator($iozone[0]);
//create_graph_data("read",$iozone[2]);
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
    
    {xaxis: {ticks: ticks,},grid: {verticalLines: true,backgroundColor: {colors: [[0, '#fff'],[1, '#eee']],start: 'top',end: 'bottom'}},
    legend: {position: 'ne'},spreadsheet: {show: true},title: 'Zfs on NFS Linux client',subtitle: 'WRITER Perfs in Mbytes/s'});})
    
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
    
    {xaxis: {ticks: ticks,},grid: {verticalLines: true,backgroundColor: {colors: [[0, '#fff'],[1, '#eee']],start: 'top',end: 'bottom'}},
    legend: {position: 'ne'},spreadsheet: {show: true},title: 'Zfs on NFS Linux client',subtitle: 'READER Perfs in Mbytes/s'});})
    
    (document.getElementById("editor-render-read"));
    
(function basic_bars(container, horizontal) {

    var
    horizontal = (horizontal ? true : false);
    var ticks = [
            <?php echo $tick_data."\n";?>
            ];

    // Draw the graph
    Flotr.draw(
    container, [{data: write7,label: '4096'},{data: write8,label: '8192'}], {
        bars: {
            show: true,
            stacked: false,
            barWidth: 0.6,
            lineWidth: 1,
            horizontal: horizontal
        },
        line: {
			show: true,
			fill: true
		},
        mouse: {
            track: true,
            relative: true
        },
        xaxis: {ticks: ticks,},
        legend: {position: 'nw'}
    });
})(document.getElementById("editor-render-compare"));
</script>
  </body>
</html>

