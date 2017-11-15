<?php
include "./config.php";

//QRM @ KPI
function qrm($slug){
	$query = db()->{'"QRM"'}->where("slug", $slug)->select('name','baseline','target','actual');
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}
//KAD
function kad($slug,$type = 1){
	$query = db()->{'"KAD'.$type.'"'}->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}
//HSSE
function hsse($slug){
	$query = db()->{'"HSSE"'}->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}
//HSSE
function scurve($slug){
	$query = db()->{'"SCURVE_MAIN"'}->where("slug", $slug);
	$query2 = db()->{'"SCURVE"'}->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	$result2 = array_map('iterator_to_array', iterator_to_array($query2));
	
	$output = array("scurve" => $result2, "scurve_main" => $result);
	return $output;
}
//PAGE INFO
function pageinfo($slug){
	$query = db()->{'"PAGE_INFO"'}->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}



//GALLERY
function gallery($slug){
	$query = db()->{'"GALLERY"'}->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}


//UG STATION PROGRESS
function ug_station_progress(){
	$query = db()->{'"UG_STATION_PROGRESS"'}();
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}
// TUNNEL PROGRESS
function ug_tunnel_progress($slug){
	$query = db()->{'"TUNNEL_PROGRESS"'}->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}
// COMMERCIAL
function commercial(){
	$query = db()->{'"COMMERCIAL"'}();
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}
// UG STATION WORK PROGRESS
function ug_work_progress($slug){
	$query = db()->{'"UG_STATION_WORK_PROGRESS"'}->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}

//Prognosis
function prognosis($slug){
	$query = db()->{'"PROGNOSIS"'}->where("slug", $slug)->order("id desc")->limit(1)->fetch();
	//$result = array_map('iterator_to_array', iterator_to_array($query));
	return $query;
}

function build_viaduct($slug){
	
	$qrm = qrm($slug);
	$kad = kad($slug,1);
	$info = pageinfo($slug);
	$hsse = hsse($slug);
	$gallery = gallery($slug);
	$scurve = scurve($slug);
	$prognosis = prognosis($slug);
	//QRM
	$qrmarr = array();
	foreach($qrm as $q){
		$qrmarr[] = array($q['name'],$q['baseline'],$q['target'],$q['actual']);
	}
	//KAD
	$kadarr = array();
	$kadobj = (object) array('date' => date('d-M-y', strtotime($kad[0]['date'])));
	$kadarr[] = $kadobj;
	foreach($kad as $q){
		$kadarr[] = array($q['name'],date('d-M-y', strtotime($q['forecast'])),date('d-M-y', strtotime($q['contract'])),date('d-M-y', strtotime($q['dps'])));
	}
	//PAGE INFO
	$pagearr = (object) array('name' => $info[0]['name'],'contractor' => $info[0]['contractor'],'station' => explode(',',$info[0]['station']));
	//HSSE
	$hssearr = array();
	foreach($hsse as $q){
		$hssearr[] = array(date('d-M-Y', strtotime($q['incident_date'])), $q['incident']);
	}
	//GALLERY
	unset($gallery[0]['slug']);
	$galleryarr = (object) $gallery[0];
	//SCURVE
	$actual = array();
	$late = array();
	$early = array();
	foreach($scurve['scurve_main'] as $q){
		if($q['actual_data'] != '-')
			$actual[] = (float) $q['actual_data'];
		if($q['delayed_data'] != '-')
			$late[] = (float) $q['delayed_data'];
		if($q['early_data'] != '-')
			$early[] = (float) $q['early_data'];
	}
	$scurvearr = array(
		'date' => date('d-M-y', strtotime($scurve['scurve'][0]['scurve_date'])),
		'actualData' => $actual,
		'earlyData' => $early,
		'delayedData' => $late,
		'currentEarly' => $scurve['scurve'][0]['early_data'].'%',
		'currentLate' => $scurve['scurve'][0]['delayed_data'].'%',
		'currentActual' => $scurve['scurve'][0]['actual_data'].'%',
		'varEarly' => $scurve['scurve'][0]['var_early'].'w',
		'varLate' => $scurve['scurve'][0]['var_late'].'w',
		'trend' => $scurve['scurve'][0]['trend'],
		'chartType' => "long",
		'viewType' => "2",
		);
	
	//PROGNOSIS
	$prognosis = $prognosis['prognosis'];
	//echo json_encode($scurvearr);
	
	$finalQRM = array("QRM" => $qrmarr);
	$finalKAD = array("KAD" => $kadarr);
	$finalINFO = array("INFO" => $pagearr);
	$finalHSSE = array("hsse" => $hssearr);
	$finalGALLERY = array("gallery" => $galleryarr);
	$finalSCURVE = array("scurve" => $scurvearr);
	$finalPROGNOSIS = array("prognosis" => $prognosis);
	
	$superFinal = array($slug => array_merge($finalQRM,$finalKAD,$finalINFO,$finalHSSE,$finalGALLERY,$finalSCURVE,$finalPROGNOSIS));
	//echo json_encode($superFinal);
	
	$test = json_decode('{"INFO":{"name":"V4","contractor":"Sunway Cosntruction Sdn Bhd","station":["Phileo Damansara","Pusat Bandar Damansara","Semantan"]}}');
	//echo(json_encode($test));
	//var_dump($test);
	
	return json_encode($superFinal);
}

function build_depot($slug){
	
	$qrm = qrm($slug); 
	$kad = kad($slug,2);
	$info = pageinfo($slug);
	$hsse = hsse($slug);
	$gallery = gallery($slug);
	$scurve = scurve($slug);
	
	//QRM
	$qrmarr = array();
	foreach($qrm as $q){
		$baseline = is_null($q['baseline'])? "-" : (int) $q['baseline'];
		$target = is_null($q['target'])? "-" : (int) $q['target'];
		$actual = is_null($q['actual'])? "-" : (int) $q['actual'];
		$qrmarr[] = array($q['name'],$baseline,$target,$actual);
	}
	//KAD
	$kadarr = array();
	$kadobj = (object) array('date' => date('d-M-y', strtotime($kad[0]['date'])));
	$kadarr[] = $kadobj;
	foreach($kad as $q){
		$kadarr[] = array($q['name'],date('d-M-y', strtotime($q['forecast'])),date('d-M-y', strtotime($q['contract'])));
	}
	
	//HSSE
	$hssearr = array();
	foreach($hsse as $q){
		$hssearr[] = array(date('d-M-Y', strtotime($q['incident_date'])), $q['incident']);
	}
	//GALLERY
	unset($gallery[0]['slug']);
	$galleryarr = (object) $gallery[0];
	//SCURVE
	$actual = array();
	$late = array();
	$early = array();
	foreach($scurve['scurve_main'] as $q){
		if($q['actual_data'] != '-')
			$actual[] = (float) $q['actual_data'];
		if($q['delayed_data'] != '-')
			$late[] = (float) $q['delayed_data'];
		if($q['early_data'] != '-')
			$early[] = (float) $q['early_data'];
	}
	$scurvearr = array(
		'date' => date('d-M-y', strtotime($scurve['scurve'][0]['scurve_date'])),
		'actualData' => $actual,
		'earlyData' => $early,
		'delayedData' => $late,
		'currentEarly' => $scurve['scurve'][0]['early_data'].'%',
		'currentLate' => $scurve['scurve'][0]['delayed_data'].'%',
		'currentActual' => $scurve['scurve'][0]['actual_data'].'%',
		'varEarly' => $scurve['scurve'][0]['var_early'].'w',
		'varLate' => $scurve['scurve'][0]['var_late'].'w',
		'trend' => $scurve['scurve'][0]['trend'],
		'chartType' => "long",
		'viewType' => "2",
		);
	
	//echo json_encode($scurvearr);
	
	$finalQRM = array("QRM" => $qrmarr);
	$finalKAD = array("KAD" => $kadarr);
	$finalHSSE = array("hsse" => $hssearr);
	$finalGALLERY = array("gallery" => $galleryarr);
	$finalSCURVE = array("scurve" => $scurvearr);
	
	$superFinal = array($slug => array_merge($finalQRM,$finalKAD,$finalHSSE,$finalGALLERY,$finalSCURVE));
	//echo json_encode($superFinal);
	
	//$test = json_decode('{"INFO":{"name":"V4","contractor":"Sunway Cosntruction Sdn Bhd","station":["Phileo Damansara","Pusat Bandar Damansara","Semantan"]}}');
	//echo(json_encode($test));
	//var_dump($test);
	
	return json_encode($superFinal);
}

//Build 6-Curves
function build_curve6(){
	$overall_elevated = scurve('overall_elevated'); 
	
	$asofdate = $overall_elevated['scurve']['0']['scurve_date'];
	$overall_elevated = array("overall_elevated" => build_me_an_scurve_array($overall_elevated,FALSE));
	
	$underground = scurve('underground');
	$underground = array("underground" => build_me_an_scurve_array($underground,FALSE));
	
	$elevated_north = scurve('elevated_north');
	$elevated_north = array("elevated_north" => build_me_an_scurve_array($elevated_north,FALSE));
	
	$overall_elevated_underground = scurve('overall_elevated_underground');
	$overall_elevated_underground = array("overall_elevated_underground" => build_me_an_scurve_array($overall_elevated_underground,FALSE));
	
	$elevated_south = scurve('elevated_south');
	$elevated_south = array("elevated_south" => build_me_an_scurve_array($elevated_south,FALSE));
	
	$elevated_south_underground = scurve('elevated_south_underground');
	$elevated_south_underground = array("elevated_south_underground" => build_me_an_scurve_array($elevated_south_underground,FALSE));
	
	$superFinal = array('programme' => array_merge($overall_elevated,$underground,$elevated_north,$overall_elevated_underground,$elevated_south,$elevated_south_underground));
	
	return array("asofdate" => $asofdate, "value" => json_encode($superFinal));
}

// Build Underground
function build_ug(){
	//Temporary
	$kad = '{"KAD2":{"Overall Tunnel":[["Access to equipment rooms/ station/  building/cross passage ","01-Jul-16","01-Jul-16"]],"Tunnel Section Between Semantan North Portal to Pasar Seni (EPB)":[["Tracks works access to tunnel section: North Portal - KL Sentral","30-Sep-15","30-Sep-15"],["Tracks works access to tunnel section: KL Sentral - Pasar Seni","30-Nov-15","30-Nov-15"],["System access to IVS 1","25-Jun-16","25-Jun-16"],["Tunnel Practical Completion","31-Dec-16","31-Dec-16"]],"Tunnel Section Between Pudu Launch Shaft to Pasar Seni (EPB)":[["Track works access to tunnel section: Pasar Seni - Merdeka","30-Nov-15","30-Nov-15"],["Track work access to tunnel section: Merdeka - Bukit Bintang","30-Nov-15","30-Nov-15"],["Tunnel Practical Completion","31-Dec-16","31-Dec-16"]],"Tunnel Section Between Inai Launch Shaft to Pudu Launch Shaft (VD)":[["Track works access to tunnel section: Bukit Bintang - Tun Razak Exchange","30-Nov-15","30-Nov-15"],["System access to IVS 2","30-Jun-16","30-Jun-16"],["Tunnel Practical Completion","31-Dec-16","31-Dec-16"]],"Tunnel Section Between Cochrane to Tun Razak Exchange (VD)":[["Track works access to tunnel section: Tun Razak Exchange - Cochrane","31-Oct-15","31-Oct-15"],["Tunnel Practical Completion","31-Dec-16","31-Dec-16"]],"Tunnel Section Between Cochrane to Maluri (VD)":[["Track works access to tunnel section: Cochrane - Maluri","31-Oct-15","31-Oct-15"],["Track works access to tunnel section: Maluri - South Portal","02-Oct-15","02-Oct-15"],["Tunnel Practical Completion","31-Dec-16","31-Dec-16"]]}}';
	$tunnel_progress = '{"tunnel_progress":{"Inai TBM1":[[2,7,0,5,6],[650,702,702,721,758]]}}';
	$gallery = '{"gallery":{"title":"Underground Image Gallery","album":"6062574122697330913","authkey":"Gv1sRgCIKBm7Kn35T50QE","keyword":"Null"}}';
	$gallery_tunnel = '{"gallery-tunnel":{"title":"Tunnel Image Gallery","keyword":"Tunnel 17th April 2015 - Pudu Launch Shaft & Pudu to Pasar Seni Tunnel Drives"}}';
	$scurve = '{"scurve":{"date":"31-Mar-15","actualData":[0,0,0,0,0.29,0.57,1.22,1.78,3.37,5.61,9.07,12.81,16.17,19.25,21.87,24.58,26.82,30,32.24,35.32,38.03,41.39,44.38,47.18,50.73,53.44,54.75,57.93,59.98,62.23,64.09,66.63,68.53,69.2,70.01,71.85,74.51,75.35,77.31],"earlyData":[0,0,0,0,0.29,0.57,1.22,1.78,3.37,5.61,9.07,12.81,16.17,19.25,21.87,24.58,26.82,30,32.24,35.32,38.03,41.39,44.38,47.18,50.73,53.44,54.75,57.83,60.26,61.98,63.97,66.34,67.96,69.7,71.44,72.94,74.56,75.68,76.55,78.17,79.42,80.54,82.04,83.66,85.53,86.9,89.02,90.63,92.5,94,95.74,96.87,97.37,97.74,97.99,98.24,98.49,98.74,98.87,98.99,99.37,99.37,99.62,99.87,100,100,100,100],"delayedData":[0,0,0,0,0.29,0.57,1.22,1.78,3.37,5.61,9.07,12.81,16.17,19.25,21.87,24.58,26.82,30,32.24,35.32,38.03,41.39,44.38,47.18,50.73,53.44,54.75,58.86,60.86,62.73,64.1,65.84,67.46,69.45,70.7,71.95,73.07,74.07,75.44,76.56,77.68,78.8,80.17,81.67,83.54,85.41,87.28,89.02,90.64,92.88,94.63,95.87,96.99,97.24,97.5,97.75,97.87,98,98.37,98.62,99,99.12,99.62,99.87,100,100,100,100],"currentEarly":"79.0%","currentLate":"74.86%","currentActual":"77.31%","varEarly":"-4.0w","varLate":"6.0w","trend":"Up","chartType":"long","viewType":"1"}}';
	$qrm = '{"QRM":[["Pad Footing (EMU)",336,304,260]]}';
	$info = '{"INFO":{"name":"UG","contractor":"MMC Gamuda KVMRT (T) Sdn Bhd"}}';
	$tunnel = '{"tunnel":{"name":"Tunnel","contractor":"MMC Gamuda KVMRT (T) Sdn Bhd","currentActual":"36.05%"}}';
	$tbm_progress = '{"tbm_progress":{"tracks":{"bore_tunnel":[{"track":"nb","startd":0,"endd":2905},{"track":"nb","startd":2905,"endd":9400},{"track":"sb","startd":0,"endd":2980},{"track":"sb","startd":2948,"endd":9261}]},"TBM":[{"track":"nb","label":"Pudu 2","distance":2710,"isCompleted":"yes"},{"track":"sb","label":"Pudu 1","distance":2948,"isCompleted":"yes"}]}}';
	//End Temporary//
	
	$overall_tunnel_progress = ug_tunnel_progress('ug');
	$overall_tbm_progress = ug_tunnel_progress('tunnel');
	$station_progress = ug_station_progress();	
	$hsse = hsse('ug');
	$hsse_tunnel = hsse('tunnel');
	
	//overall tunnel progress
	$overall_tunnel_progress_arr = array();
	foreach($overall_tunnel_progress as $q){
		$overall_tunnel_progress_arr[] = array($q['name'],$q['week1'],$q['week2'],$q['week3'],$q['week4']);
	}
	$overall_tunnel_progress_obj = new stdClass();
	$overall_tunnel_progress_obj->progress = $overall_tunnel_progress_arr;
	$overall_tunnel_progress_obj->as_of = date('M/y', strtotime($overall_tunnel_progress[0]['asof']));
	$final_overall_tunnel_progress = array("overall_tunnel_progress" => $overall_tunnel_progress_obj);
	
	//overall tbm progress
	$overall_tbm_progress_arr = array();
	foreach($overall_tbm_progress as $q){
		$overall_tbm_progress_arr[] = array($q['name'],$q['week1'],$q['week2'],$q['week3'],$q['week4']);
	}
	$overall_tbm_progress_obj = new stdClass();
	$overall_tbm_progress_obj->progress = $overall_tbm_progress_arr;
	$overall_tbm_progress_obj->as_of = date('M/y', strtotime($overall_tunnel_progress[0]['asof']));
	$final_overall_tbm_progress = array("overall_tbm_progress" => $overall_tbm_progress_obj);
	
	//station progress
	$station_progress_arr=new stdClass();
	$station_progress_arr->date = date('d-M-y', strtotime($station_progress[0]['asof']));
	foreach($station_progress as $q){	
		$station_progress_arr->$q['station_name'] = array("progress" => (float) $q['progress']);
	}
	$final_station_progress = array("station" => $station_progress_arr);
	
	//hsse
	$hsse_arr = array();
	foreach($hsse as $q){
		$hsse_arr[] = array($q['incident_date'],$q['incident']);
	}
	$final_hsse = array("hsse" => $hsse_arr);
	
	//hsse tunnel
	$hsse_tunnel_arr = array();
	foreach($hsse_tunnel as $q){
		$hsse_tunnel_arr[] = array($q['incident_date'],$q['incident']);
	}
	$final_hsse_tunnel = array("hsse-tunnel" => $hsse_tunnel_arr);
	
	$output = array("ug" => array_merge(
						$final_overall_tunnel_progress,
						$final_overall_tbm_progress,
						$final_station_progress,
						$final_hsse,
						$final_hsse_tunnel,
						(array) json_decode($kad),
						(array) json_decode($tunnel_progress),
						(array) json_decode($gallery),
						(array) json_decode($gallery_tunnel),
						(array) json_decode($scurve),
						(array) json_decode($qrm),
						(array) json_decode($info),
						(array) json_decode($tunnel),
						(array) json_decode($tbm_progress)
						));	
						
	return json_encode($output);
}

//Build Commercial
function build_commercial($get_asof = false){
	$commercial = commercial();
	$commercial_arr = new stdClass();
	foreach($commercial as $k => $q){
		$as_of = $q['as_of'];
		$name = slugify($q['name']);
		$commercial_arr->$name = (float) $q['value'];
		if($k==0 || $k==1 || $k==2)
			$commercial_arr->$name /= 1000;
		
	}
	if($get_asof){
		$as_of = DateTime::createFromFormat('d-M-Y', $as_of)->format('Y-m-d');
		return $as_of;
	}
	else
		return json_encode($commercial_arr);
}

//Elevated Station
function build_el_station($slug){
	//Temporary data for phileo damansara
	$kad = '{"KAD":[{"date":"16-Mar-15"},["Access Date to Station/Equipment Room at Phileo Damansara Station (Concourse Level (All Rooms))","30-Apr-15","30-Apr-15","16-Jun-15"],["Access Date to Station/Equipment Room at Phileo Damansara Station (Platform Level (All Rooms))","17-May-15","30-Apr-15","16-Jun-15"],["Access Date to Station/Equipment Room at Phileo Damansara Station (Platform Level (APG Rooms))","17-May-15","30-Apr-15","30-Sep-15"]]}';
	$gallery = '{"gallery":{"title":"V4 Image Gallery","album":"6061807558957679857","authkey":"Gv1sRgCMrih9TrpLOk4AE","keyword":"Null"}}';
	//End temporary
	$qrm = qrm($slug);
	
	$qrm_arr = array();
	foreach($qrm as $q){
		$baseline = is_null($q['baseline'])? "-" : (int) $q['baseline'];
		$target = is_null($q['target'])? "-" : (int) $q['target'];
		$actual = is_null($q['actual'])? "-" : (int) $q['actual'];
		$qrm_arr[] = array($q['name'],$baseline,$target,$actual);
	}
	$final_qrm = array("KPI" => $qrm_arr);
	
	$output = array($slug => array_merge((array) json_decode($kad),(array) json_decode($gallery),$final_qrm));
	
	return json_encode($output);
}

//Underground Station
function build_ug_station($slug){
	//Temporary data for Muzium negara
	$kad = '{"KAD":[["Access to trackside areas in station including 33kV traction substraction","30-Nov-15","30-Nov-15"],["LV (415V) Power On","31-May-16","31-May-16"],["Access to equipment rooms/ station/ building/ cross passage","25-Jun-16","25-Jun-16"],["Station Practical Completion","31-Dec-16","31-Dec-16"]]}';
	
	//End temporary
	$hsse = hsse($slug);
	$gallery = gallery($slug);
	$ug_work = ug_work_progress($slug);
	
	//GALLERY
	unset($gallery[0]['slug']);
	$gallery_arr = (object) $gallery[0];
	//HSSE
	$hsse_arr = array();
	foreach($hsse as $q){
		$hsse_arr[] = array($q['incident_date'],$q['incident']);
	}
	//UG WORK
	$ug_work_arr = new stdClass();
	$ug_work_sb_arr = new stdClass();
	$ug_work_arr->date = date('d-M-y', strtotime($ug_work[0]['asof']));
	foreach($ug_work as $q){
		$ug_work_sb_arr->$q['item'] = (float) $q['progress'];
	}
	$ug_work_arr->{"Station Box"} = array("Station Box" => $ug_work_sb_arr);
	
	
	
	$final_hsse = array("hsse" => $hsse_arr);	
	$final_gallery = array("gallery" => $gallery_arr);
	$final_ug_work = array("station_activity" => $ug_work_arr);
	
	$output = array($slug => array_merge((array) json_decode($kad),$final_hsse,$final_gallery,$final_ug_work));
	
	return json_encode($output);
}

function reset_commercial(){
	$dcs = dcs();
	$sql_previous = 'SELECT il1."config_no",il1."row",il1."value",il1."timestamp",il1."revision",il1."validate_status",il1."validate_comment",il1."user_id" FROM "ilyas" il1 LEFT JOIN "ilyas" il2 ON il1."config_no" = il2."config_no" AND il1."revision" < il2."revision" LEFT JOIN "ilyas_config" ic ON il1."config_no" = ic."config_no" WHERE il1."config_no" IN (399,400,446,444) AND il2."revision" IS NULL AND il1."validate_status" = 2 AND ic."col_order" = 1 ORDER BY ic."col_order",il1."row"';
	$sql_current = 'SELECT il1."config_no",il1."row",il1."value",il1."timestamp",il1."revision",il1."validate_status",il1."validate_comment",il1."user_id" FROM "ilyas" il1 LEFT JOIN "ilyas" il2 ON il1."config_no" = il2."config_no" AND il1."revision" < il2."revision" LEFT JOIN "ilyas_config" ic ON il1."config_no" = ic."config_no" WHERE il1."config_no" IN (399,400,446,444) AND il2."revision" IS NULL AND il1."validate_status" = 2 AND ic."col_order" = 2 ORDER BY ic."col_order",il1."row"';
	
	foreach ($dcs->query($sql_current) as $k=>  $row) { // Current value
		$actual[$k] = array('config_no' => $row['config_no'], 'row' => $row['row'], 'revision' => $row['revision'], 'value' => $row['value']);
		//Empty current value.
		$sql = 'UPDATE "ilyas" SET "value" = \'\' WHERE "config_no"= '.$row['config_no'].' AND "row"= '.$row['row'].' AND "revision" ='.$row['revision']; //var_dump($sql);
		$dcs->query($sql);
	}
	
	foreach ($dcs->query($sql_previous) as $k => $row) { // Previous Value
		if($actual[$k]['value'] !== ''){
			$prev[$k] = array('config_no' => $row['config_no'], 'row' => $row['row'], 'revision' => $row['revision'], 'value' => $row['value']);
			//Update prev value using current value.
			$sql = 'UPDATE "ilyas" SET "value" = \''.$actual[$k]['value'].'\' WHERE "config_no"= '.$row['config_no'].' AND "row"= '.$row['row'].' AND "revision" ='.$row['revision']; //var_dump($sql);
			$dcs->query($sql);
		}
	}
	
	//update prev value
	
	//var_dump($actual);
}

function reset_safety(){
	$dcs = dcs();
	$sql = 'DELETE FROM "ilyas" WHERE "config_no" IN ( SELECT ic."config_no" FROM "journal_master_nonprogressive" jm JOIN "ilyas_config" ic ON ic."journal_no" = jm."journal_no" WHERE lower(jm.journal_name) LIKE \'%safety incidents\' ORDER BY jm."journal_name" )';
	$dcs->query($sql);
}


//------ Utilities function

function build_me_an_scurve_array($data = 'Give me the data or im not going to build anything for you! evil laugh',$asofdate = TRUE){
	$actual = array();
	$late = array();
	$early = array();
	foreach($data['scurve_main'] as $q){
		if($q['actual_data'] != '-')
			$actual[] = (float) $q['actual_data'];
		if($q['delayed_data'] != '-')
			$late[] = (float) $q['delayed_data'];
		if($q['early_data'] != '-')
			$early[] = (float) $q['early_data'];
	}
	$scurvearr = array(
		'date' => date('d-M-y', strtotime($data['scurve'][0]['scurve_date'])),
		'actualData' => $actual,
		'earlyData' => $early,
		'delayedData' => $late,
		'currentEarly' => $data['scurve'][0]['early_data'].'%',
		'currentLate' => $data['scurve'][0]['delayed_data'].'%',
		'currentActual' => $data['scurve'][0]['actual_data'].'%',
		'varEarly' => $data['scurve'][0]['var_early'].'w',
		'varLate' => $data['scurve'][0]['var_late'].'w',
		'trend' => $data['scurve'][0]['trend'],
		'chartType' => "long",
		'viewType' => "1",
		);
		
	if(!$asofdate)
		unset($scurvearr['date']);
	
	return $scurvearr;
}

function slugify($text){
  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '_', $text);

  // trim
  $text = trim($text, '_');

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // lowercase
  $text = strtolower($text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  if (empty($text))
  {
    return 'n-a';
  }

  return $text;
}

function updateDB($slug, $value, $date){
	$item = mpxd()->{'"items"'}->where("slug", $slug);
	//$items = array_map('iterator_to_array', iterator_to_array($items));
	$item = $item->fetch();
	if($item){
		$id = ($item['id']);
		$name = ($item['name']);
	//$data = mpxd()->{'"data_sources"'}->where("item_id = $id AND date = now()::date");
	$data = mpxd()->{'"data_sources"'}->where("item_id = $id AND date = '$date'"); //Check if there's a record for given date.
		$datax = $data->fetch();
		if($datax){ // exist
			$d = array("value" => $value);
			$data->update($d);
		}
		else{ //new row
			$d = array("item_id" => $id , "value" => $value, "date" => $date, "name" => $name);
			$ds = mpxd()->{'"data_sources"'}(); //echo $ds->count();
			$ds->insert($d);
		}
	}
}

//-----------Build
$run = isset($_GET['run']) ? $_GET['run'] : 'no data';

switch($run){
	case 'v4':
		$v4 = build_viaduct('v4'); //echo $v4;
		updateDB('v4',$v4,'2015-05-24');
		reset_safety();
		break;
	case 'dpt1':
		$depot1 = build_depot('dpt1'); //echo $depot1;
		updateDB('dpt1',$depot1,'2015-05-24');
		reset_safety();
		break;
	case 'phileo-damansara':
		$phileo = build_el_station('phileo-damansara'); //echo $phileo;
		updateDB('phileo-damansara',$phileo,'2015-05-24');
		reset_safety();
		break;
	case 'programme':
		$curve6 = build_curve6(); //var_dump($curve6);
		updateDB('programme',$curve6['value'],$curve6['asofdate']);
		break;
	case 'ug':
		$ug = build_ug(); //echo $ug;
		updateDB('ug',$ug,'2015-05-24');
		reset_safety();
		break;
	case 'muzium-negara':
		$muzium_negara = build_ug_station('muzium-negara'); //echo $muzium_negara;
		updateDB('muzium-negara',$muzium_negara,'2015-05-24');
		reset_safety();
		break;
	case 'commercial_front':
		$commercial = build_commercial(); //echo $commercial;
		updateDB('commercial_front',$commercial,build_commercial(true));
		break;
	case 'commercial_reset':
		reset_commercial();
		break;
	case 'safety_reset':
		reset_safety();
		break;
	default:
		echo "Nothing to run";
}
//$v4 = build_viaduct('v4'); echo $v4;
//$curve6 = build_curve6();
//$ug = build_ug(); echo $ug;
//$commercial = build_commercial();

//$phileo = build_el_station('phileo-damansara');
//$depot1 = build_depot('dpt1');
//$muzium_negara = build_ug_station('muzium-negara');

//-----------Update DB
// Note: update date based on asofdate or current job date;
//updateDB('v4',$v4,'2015-05-10');
//updateDB('ug',$ug,date('Y-m-d'));
//updateDB('dpt1',$depot1,date('Y-m-d'));
//updateDB('muzium-negara',$muzium_negara,date('Y-m-d'));
//updateDB('phileo-damansara',$phileo,date('Y-m-d'));
//updateDB('commercial_front',$commercial,date('Y-m-d'));

//updateDB('programme',$curve6['value'],$curve6['asofdate']);


//echo 'completed';
?>