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

$fp = fopen("data/graph_data.js","w");
// write graph and data : TODO factorize
$i = 1;
$graph_data = null;
foreach ($write_tests as $key => $write_test) {
	$graph_data .= "{data: w".$i.",label: '".$key."'},";
	fwrite($fp,"var w".$i." = [];\n");
	$j = 0;
	foreach ($write_test as $result) {
        fwrite($fp,"w".$i.".push([".$j.",$result]);\n");
        $j++;
    }
    $i++;
}



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
}
fclose($fp);
// tick generator
$t = 0;
$tick_data = null;
foreach (end($reclen) as $reclen_value) {
	$tick_data .= "[".$t.",'".$reclen_value."'],";
    $t++;
}

?>
