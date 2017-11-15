<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

//$temp = array();
//$i = 0;
//foreach($item as $val){
//     array_push($temp,$val['TRAIN_NO'],$val['Static_Total']);
//    $i++;
//}

print_r(json_decode(json_encode($item)));
