<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <head>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script type="text/javascript" src="js/flotr2.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
  </head>
  <body>
	  <h1>Xena4</h1>
	
<?php
require_once("ioviz.php");

// load all DATA JS
$handle=opendir("data/");
while (($file = readdir($handle))!==false) {
	if (strstr($file,".js")) {
	echo '<script type="text/javascript" src="data/'.$file.'"></script>';
	}
}
closedir($handle);
//

// to do : browse to upload file then load bench by hosts (subfolders)
$iozone = parse_data("/data/resultth.txt");
$iozone2 = parse_data("/data/resultlocal.txt");

$bench_name= "write";
$bench_name2= "writelocal";
$graph_data = create_graph_data($bench_name,$iozone[1]);
$graph_data2 = create_graph_data($bench_name2,$iozone2[1]);
$tick_data = tick_generator($iozone[0]);
$tick_data2 = tick_generator($iozone2[0]);

echo draw_graph($tick_data,$graph_data,$bench_name,"Zfs on NFS Linux client","WRITER Perfs in Mbytes/s");
echo draw_graph($tick_data2,$graph_data2,$bench_name2,"Ext4 on local disk","WRITER Perfs in Mbytes/s");
?>


  </body>
</html>
