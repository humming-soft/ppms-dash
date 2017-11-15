<?php 

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0"); 
//header('content-type:text/json');
var_dump($_POST);
$array = json_decode($post,true);
// var_dump($array);
echo "<style type='text/css'>
	table {
		background: #333;
		color: #9c3;
	}
	.highlight{
		background: #1f3b3f;
		color: #fff;
		font-weight: bold;
	}
	th, td {
		padding: 10px;
	}
	h1{
		background:#222;
		color:#15a6e9;
	}
	</style>";
	
for ($i=0; $i < sizeof($array); $i++) { 
	
	echo "<h1>".$array[$i]['title']."</h1>";

	echo $array[$i]['html'];

	echo "<br><br>";
}

?>
