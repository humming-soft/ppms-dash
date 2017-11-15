<?php

include "./notorm/NotORM.php";

//$pdo->exec('SET search_path TO public');
function db(){ //warehouse
	$host = '192.168.1.52';
	$pdo = new PDO("pgsql:dbname=dcs_l2_dw;user=postgres;password=mrt@mpxd!@#123;host=$host;port=5432");
	$db = new NotORM($pdo);
	return $db;
}

function mpxd(){ //dashboard
	$host = '192.168.1.52';
	$pdo = new PDO("pgsql:dbname=mpxd2;user=postgres;password=mrt@mpxd!@#123;host=$host;port=5432");
	$db = new NotORM($pdo);
	return $db;
}

function dcs(){ //dcs
	$host = '192.168.1.52';
	$pdo = new PDO("pgsql:dbname=pil;user=postgres;password=mrt@mpxd!@#123;host=$host;port=5432");
	//$db = new NotORM($pdo);
	return $pdo;
}

?>