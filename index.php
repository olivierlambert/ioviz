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



// filename, bench_name, description
$ioviz = new Ioviz("/data/resultth.txt","ZFS","Zfs bench on NFS Linux Client");

$ioviz->get_graph();


?>


  </body>
</html>
