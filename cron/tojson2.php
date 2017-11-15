<?php
include "./config.php";
/**
 * Created on 			: 31-11-2016
 * Author 				: Sebin Thomas
 * Last Modified on 	: 14-06-2017
 * Desc 				: Formatted JSON Data Creation.
 * 						  This file is called in Cron Job for the further processing.
 **/

//Test
/**
 * GALLERY
 * @param $slug
 * @return Array
 * @Desc If a new week is found first push it as an album and the same as image. Then others are as image
 */
function gallery($slug){
	$temp = array();
	$t= array();
	$query = db()->gallery()
		->select("*")
		->where("slug", $slug)
		->order("week DESC");
	$result = array_map('iterator_to_array', iterator_to_array($query));
	$week = array();
	$itr = 0;
	foreach ($result as $v){
		if(!in_array($v["week"], $week)) {
			$itr++;
			$temp["path"] = $v["uploaded_path"];
			$temp["title"] = $v["album_name"];
			$temp["kind"] = "album";
			$temp["id"] = $itr;
			array_push($t, $temp);
			$temp["path"] = $v["uploaded_path"];
			$temp["title"] = $v["image_description"];
			$temp["kind"] = "image";
			$temp["id"] = $itr;
			array_push($t, $temp);
		}else{
			$temp["path"] = $v["uploaded_path"];
			$temp["title"] = $v["image_description"];
			$temp["kind"] = "image";
			$temp["id"] = $itr;
			array_push($t, $temp);
		}
		if(!in_array($v["week"], $week)) {
			array_push($week, $v["week"]);
		}
	}
	return $t;
}

/**
 * KPI(QRM)
 * @param $slug
 * @return Array
 * @Desc
 */
function kpi($slug){
	$max_date = db()->kpi()
		->select('MAX(date)')
		->where("slug", $slug)
		->or("slug", strtoupper($slug));
	$m = $max_date->fetch();
	if($m) {
		$date = ($m['max']);
		$query = db()->kpi()
			->select('name', 'baseline', 'target', 'actual')
			->where("slug", $slug)
			->or("slug", strtoupper($slug))
			->where("date", $date);
		$result = array_map('iterator_to_array', iterator_to_array($query));
		$kpiarr = array();
		foreach($result as $q){
			$kpiarr[] = array($q['name'], (float)$q['baseline'], (float)$q['target'], (float)$q['actual']);
		}
		return $kpiarr;
	}
	return [];
}

/**
 * Info
 * @param $slug
 * @return Array
 * @Desc
 */
function info($slug){
	$query = db()->package_info()
		->select('spd','type','url')
		->where("slug", $slug)
		->or("slug", strtoupper($slug));
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}

function getName($slug){
	$slugInfo = db()->ref_slug()
		->where("slug", $slug)
		->or("slug", strtoupper($slug));
	$slugInfo = $slugInfo->fetch();
	if($slugInfo) {
		$name = ($slugInfo['name']);
	}else{
		$name ="-";
	}
	return $name;
}

/**
 * Contractor info
 * @param $slug
 * @return Array
 * @Desc
 */
function contractor($slug){
	$query = db()->contractors()
		->select('contractor')
		->where("slug", $slug)
		->or("slug", strtoupper($slug));
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}

/**
 * Package Info
 * @param $slug
 * @return Array
 * @Desc
 */
function packageInfo($slug, $type = 1){
	$contractor = contractor($slug);
	$i = array();
	$i["name"] = getName($slug);
	foreach ($contractor as $value) {
		$i["contractor"] = $value['contractor'];
	}
	if ($type == 1) {
		$info = info($slug);
		$i["station"] = array();
		$i["mspr"] = array();
		$i["depot"] = array();
		foreach ($info as $value) {
			if ($value["type"] == 1) {
				array_push($i["station"], $value["spd"]);
			} elseif ($value["type"] == 2) {
				array_push($i["mspr"], $value["spd"]);
			} else {
				array_push($i["depot"], $value["spd"]);
			}
		}
	}
	return $i;
}

/**
 * KAD
 * @param $slug
 * @return Array
 * @Desc
 */
function kad($slug, $type){
	if($type==1){
		$query = db()->{'"key_dates'.$type.'"'}
		->select('pagename','slug','name','forecast','contract','dps','date')
		->where("slug", $slug)
		->or("slug", strtoupper($slug));
	}
	if($type==2 or $type==3){
		$query = db()->{'"key_dates'.$type.'"'}
		->select('pagename','slug','name','forecast','contract','date')
		->where("slug", $slug)
		->or("slug", strtoupper($slug));
		}
	$result = array_map('iterator_to_array', iterator_to_array($query));

	if(sizeof($result)>0) {
		$kadarr = array();
		$kadobj = (object)array('date' => date('d-M-y', strtotime($result[0]['date'])));
		$kadarr[] = $kadobj;
		if($type==1){
			foreach ($result as $q) {
			$kadarr[] = array($q['name'], date('d-M-y', strtotime($q['forecast'])), date('d-M-y', strtotime($q['contract'])), date('d-M-y', strtotime($q['dps'])));
			}
		}
		if($type==2 or $type==3){
			foreach ($result as $q) {
			$kadarr[] = array($q['name'], date('d-M-y', strtotime($q['forecast'])), date('d-M-y', strtotime($q['contract'])));
			}
		}
		return $kadarr;
	}else{
		return [];
	}
}

/**
 * KD
 * @param $slug
 * @return Array
 * @Desc
 */
function kd($slug, $type = 1){
	return [];
}
/**
 * Safety Incidents (hsse)
 * @param $slug
 * @return Array
 * @Desc
 */
function safety_incident($slug){
	$query = db()->hsse()
		->select('incident_date','incident')
		->where("slug", $slug)
		->or("slug", strtoupper($slug));
	$result = array_map('iterator_to_array', iterator_to_array($query));
	$hssearr = array();
	foreach($result as $q){
		$hssearr[] = array(date('d-F-Y', strtotime($q['incident_date'])), $q['incident']);
	}
	return $hssearr;
}

/**
 * S-curve
 * @param $slug
 * @return Array
 * @Desc
 */
function scurve($slug){
	$query = db()->{'"scurve_main"'}
		->select('pagename','slug','early_data','delayed_data','actual_data','scurve_date','date')
		->where("slug", $slug)
	    ->or("slug", strtoupper($slug));
	$query2 = db()->{'"scurve"'}
		->select('pagename','slug','early_data','delayed_data','actual_data','var_early','var_late','trend','chart_type','view_type','scurve_date','date')
		->where("slug", $slug)
	    ->or("slug", strtoupper($slug));
	$result = array_map('iterator_to_array', iterator_to_array($query));
	$result2 = array_map('iterator_to_array', iterator_to_array($query2));

	$output = array("scurve" => $result2, "scurve_main" => $result);

	return $output;
//	return [];
}

/**
 * Tunnel Progress
 * @param $slug
 * @return Array
 * @Desc
 */
function tunnel_progress($slug){
	$query = db()->{'"tunnel_progress"'}
		->select('pagename','slug','name','week1','week2','week3','week4','asof','date')
		->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}

/**
 * Underground Station Progress
 * @param $slug
 * @return Array
 * @Desc
 */
function ug_station_progress($slug){
	$query = db()->{'"ug_station_progress"'}
			->select('pagename','slug','station_name','progress','date','asof');
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}

/**
 * INFO TUNNEL
 * @param $slug
 * @return Array
 * @Desc
 */
function info_tunnel($slug){
	$contractor = contractor($slug);
	$i["name"] = "Tunnel";
	foreach ($contractor as $value) {
		$i["contractor"] = $value['contractor'];
	}
	return $i;
}

/**
 * Underground Station Activity
 * @param $slug
 * @return Array
 * @Desc
 */
function ug_station_activity($slug){
	$query = db()->{'"ug_station_work_progress"'}
		->select('pagename','slug','item','progress','date','asof')
		->where("slug", $slug);
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}

/**
 * Viaduct Summary
 * @param $slug
 * @return Array
 * @Desc
 */
function v_summary($slug){
	return [];
}

/**
 * Dashboard
 * @param $slug
 * @return Array
 * @Desc
 */
function dashboard(){
	$query = db()->dash_commercial()
		->select('pagename','slug','name','value','date','as_of');
	$result = array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}

/**
 * North and South Summary
 * @param $slug
 * @return Array
 * @Desc
 */
function ns_summary($slugs){
	$query = db()->{'"scurve"'}
		->select('pagename','early_data','delayed_data','actual_data','var_early','var_late','trend')
		->where("slug", $slugs)
		->and("scurve_date", db()->scurve()->select('MAX(scurve_date)'));
	$result= array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}
/**
 * Piers
 * @param $slug
 * @return Array
 * @Desc
 */

function piers($slug){
	$final = array();
	$query = db()->{'"pier_ref"'}
				 ->select('pier_v','pier_id','pier_north_id', 'pier_south_id','pier_marker_a','pier_marker_b', 'pier_layout', 'pier_type', 'span_type', 'sbg')
				 ->where("pier_v", $slug);
	$result= array_map('iterator_to_array', iterator_to_array($query));
	foreach($result as $v){	
		$ad =  pier_span_col($slug,$v['pier_id']);
		$temp = array();
		$temp['pier_v']= $v['pier_v'];
		$temp['pier_id']= $v['pier_id'];
		$temp['pier_north_id']= $v['pier_north_id'];
		$temp['pier_south_id']= $v['pier_south_id'];
		$temp['pier_marker_a']= $v['pier_marker_a'];
		$temp['pier_marker_b']= $v['pier_marker_b'];
		$temp['pier_layout']= $v['pier_layout'];
		$temp['pier_type']= $v['pier_type'];
		$temp['span_type']= $v['span_type'];
		if(sizeof($ad)>0){
			$temp['pier_pile_1']= $ad[0]['pier_pile_1'];
			$temp['pier_pile_2']= $ad[0]['pier_pile_2'];
			$temp['pier_pilecap_1']= $ad[0]['pier_pilecap_1'];
			$temp['pier_pilecap_2']= $ad[0]['pier_pilecap_2'];
			$temp['pier_pier_1']= $ad[0]['pier_pier_1'];
			$temp['pier_pier_2']= $ad[0]['pier_pier_2'];
			$temp['pier_pierhead_1']= $ad[0]['pier_pieread_1'];
			$temp['pier_pierhead_2']= $ad[0]['pier_pieread_2'];
			$temp['pier_pierhead_3']= $ad[0]['pier_pieread_3'];
			$temp['span1']= $ad[0]['span_1'];
			$temp['span2']= $ad[0]['span_2'];
			$temp['span3']= $ad[0]['span_3'];
			$temp['sbg'] = array();
			$temp['sbg']= pier_sbg_res($ad[0]['sbg_left_count'], $ad[0]['sbg_right_count'],$ad[0]['sbg_left'],$ad[0]['sbg_right']);
			$temp['parapet1']= $ad[0]['parapet_1'];
			$temp['parapet2']= $ad[0]['parapet_2'];
			$temp['parapet3']= $ad[0]['parapet_3'];
		}else{
			$temp['pier_pile_1']= "0";
			$temp['pier_pile_2']= "0";
			$temp['pier_pilecap_1']= "0";
			$temp['pier_pilecap_2']= "0";
			$temp['pier_pier_1']= "0";
			$temp['pier_pier_2']= "0";
			$temp['pier_pierhead_1']= "0";
			$temp['pier_pierhead_2']= "0";
			$temp['pier_pierhead_3']= "0";
			$temp['span1']= "0";
			$temp['span2']= "0";
			$temp['span3']= "0";
			$temp['sbg'] = array();
			$temp['sbg']= pier_sbg_res(0,0,0,0);
			$temp['parapet1']= "0";
			$temp['parapet2']= "0";
			$temp['parapet3']= "0";
		}
		array_push($final,$temp);
	}
	return $final;
}

function pier_span_col($slug, $id){
	$query = db()->{'"pier_span_col"'}
		->select('pier_v', 'pier_id', 'pier_north_id', 'pier_south_id', 
				'pier_pile_1', 'pier_pile_2', 'pier_pilecap_1', 'pier_pilecap_2', 'pier_pier_1', 
				'pier_pier_2', 'pier_pieread_1', 'pier_pieread_2', 'pier_pieread_3', 'sbg_left_count', 'sbg_right_count',
				'sbg_left', 'sbg_right', 'span_1', 'span_2', 'span_3', 'span_4', 'parapet_1', 
				'parapet_2', 'parapet_3')
		->where("slug", $slug)->and("pier_id", $id);
	$result= array_map('iterator_to_array', iterator_to_array($query));
	return $result;
}

function pier_sbg_res($left_total, $right_total,$left_completed,$right_completed){
	$sbg = array(
		'sbg_id'=>array(),
		'sbg_lr' =>array(),
		'sbg_va' =>array()
	);
  	 for($q = 0; $q< ($left_total+$right_total); $q++){ 
		array_push($sbg['sbg_id'],"sbg".($q+1));
/* 		if($q < $left_completed){
			array_push($sbg['sbg_va'],1);
		}else{
			if(sizeof($sbg['sbg_va']) <= $left_total){
				array_push($sbg['sbg_va'],0);
			}else{
				if($q > $left_total){
					if(){
						array_push($sbg['sbg_va'],1);
					}else{
						array_push($sbg['sbg_va'],0);
					}
				}
			}
		} */
	 }   
	 for($q = 0; $q< $left_total; $q++){
		 if($q < $left_completed){
			 array_push($sbg['sbg_va'],1);
		 }else{
			 array_push($sbg['sbg_va'],0);
		 }
	 }
	 for($q = 0; $q< $right_total; $q++){
		 if($q < $right_completed){
			 array_push($sbg['sbg_va'],1);
		 }else{
			 array_push($sbg['sbg_va'],0);
		 }
	 }
	 for($q = 0; $q< ($left_total); $q++){ 
		array_push($sbg['sbg_lr'],"left");
	 }
	 for($q = 0; $q< ($right_total); $q++){ 
		array_push($sbg['sbg_lr'],"right");
	 }  	 
	return $sbg;
}
/**
 *69696969669696969696969696969696969696969696969696969696969696969696969696969696969699696969696996969696969696969696969696969696969696969696969696969
 */

/**
 * VIADUCTS
 * @param $slug
 * @return Array
 * @Desc Portlet specific info of viaducts
 */
function build_viaducts($slug){

	$info = packageInfo($slug,1);
	$gallery = gallery($slug);
	$kpi = kpi($slug);
	$kad = kad($slug,1);
	$kd = kd($slug,1);
	$hsse = safety_incident($slug);
	$scurve = scurve($slug);
	$piers = piers($slug);

	//SCURVE
	if(sizeof($scurve['scurve'])>0 ) {
		$actual = array();
		$late = array();
		$early = array();
		foreach ($scurve['scurve_main'] as $q) {
			if ($q['actual_data'] != '-')
				$actual[] = (float)$q['actual_data'];
			if ($q['delayed_data'] != '-')
				$late[] = (float)$q['delayed_data'];
			if ($q['early_data'] != '-')
				$early[] = (float)$q['early_data'];
		}
		$scurvearr = array(
			'date' => date('d-M-y', strtotime($scurve['scurve'][0]['scurve_date'])),
			'actualData' => $actual,
			'earlyData' => $early,
			'delayedData' => $late,
			'currentEarly' => $scurve['scurve'][0]['early_data'] . '%',
			'currentLate' => $scurve['scurve'][0]['delayed_data'] . '%',
			'currentActual' => $scurve['scurve'][0]['actual_data'] . '%',
			'varEarly' => $scurve['scurve'][0]['var_early'] . 'w',
			'varLate' => $scurve['scurve'][0]['var_late'] . 'w',
			'trend' => $scurve['scurve'][0]['trend'],
			'chartType' => "long",
			'viewType' => "2",
		);
	}

	$galleryFormatter = array("title"=> strtoupper($slug).' Image Gallery',"items" => $gallery);
	$finalQRM = array("QRM" => $kpi);
	$finalKAD = array("KAD" => $kad);
	$finalKD = array("KD" => $kd);
	$finalINFO = array("INFO" =>$info);
	$finalHSSE = array("hsse" => $hsse);
	$finalGALLERY = array("gallery" => $galleryFormatter);
	$finalSCURVE = array("scurve" => (sizeof($scurve['scurve'])>0 ? $scurvearr : []));
	$finalPIERS = array("PIERS" => $piers);
	$superFinal = array($slug => array_merge($finalQRM, $finalKAD, $finalKD, $finalINFO, $finalHSSE, $finalGALLERY, $finalSCURVE,$finalPIERS));
	return json_encode($superFinal);
//	updateDB($slug, json_encode($superFinal), $date);
}
/**
 * SYSTEMS
 * @param $slug
 * @return Array
 * @Desc  Portlet specific info of Systems
 */
function build_systems($slug){

	$info = packageInfo($slug,2);
	$gallery = gallery($slug);
	$kad = kad($slug,3);
	$scurve = scurve($slug);

	//SCURVE
	if(sizeof($scurve['scurve'])>0 ) {
		$actual = array();
		$late = array();
		$early = array();
		foreach ($scurve['scurve_main'] as $q) {
			if ($q['actual_data'] != '-')
				$actual[] = (float)$q['actual_data'];
			if ($q['delayed_data'] != '-')
				$late[] = (float)$q['delayed_data'];
			if ($q['early_data'] != '-')
				$early[] = (float)$q['early_data'];
		}
		$scurvearr = array(
			'date' => date('d-M-y', strtotime($scurve['scurve'][0]['scurve_date'])),
			'actualData' => $actual,
			'earlyData' => $early,
			'delayedData' => $late,
			'currentEarly' => $scurve['scurve'][0]['early_data'] . '%',
			'currentLate' => $scurve['scurve'][0]['delayed_data'] . '%',
			'currentActual' => $scurve['scurve'][0]['actual_data'] . '%',
			'varEarly' => $scurve['scurve'][0]['var_early'] . 'w',
			'varLate' => $scurve['scurve'][0]['var_late'] . 'w',
			'trend' => $scurve['scurve'][0]['trend'],
			'chartType' => "long",
			'viewType' => "2",
		);
	}
	
	$finalKAD = array("KAD" => $kad);
	$finalINFO = array("INFO" =>$info);
	$finalGALLERY = array("gallery" => $gallery);
	$finalSCURVE = array("scurve" => (sizeof($scurve['scurve'])>0 ? $scurvearr : []));
	$superFinal = array($slug => array_merge($finalINFO,  $finalKAD, $finalGALLERY, $finalSCURVE));
	return json_encode($superFinal);
}

/**
 * STATIONS
 * @param $slug
 * @return Array
 * @Desc Portlet specific info of Stations
 */
function build_stations($slug){

	$info = packageInfo($slug,2);
	$gallery = gallery($slug);
	$kpi = kpi($slug);
	$kad = kad($slug,1);
	$scurve = scurve($slug);

	//SCURVE
	if(sizeof($scurve['scurve'])>0 ) {
		$actual = array();
		$late = array();
		$early = array();
		foreach ($scurve['scurve_main'] as $q) {
			if ($q['actual_data'] != '-')
				$actual[] = (float)$q['actual_data'];
			if ($q['delayed_data'] != '-')
				$late[] = (float)$q['delayed_data'];
			if ($q['early_data'] != '-')
				$early[] = (float)$q['early_data'];
		}
		$scurvearr = array(
			'date' => date('d-M-y', strtotime($scurve['scurve'][0]['scurve_date'])),
			'actualData' => $actual,
			'earlyData' => $early,
			'delayedData' => $late,
			'currentEarly' => $scurve['scurve'][0]['early_data'] . '%',
			'currentLate' => $scurve['scurve'][0]['delayed_data'] . '%',
			'currentActual' => $scurve['scurve'][0]['actual_data'] . '%',
			'varEarly' => $scurve['scurve'][0]['var_early'] . 'w',
			'varLate' => $scurve['scurve'][0]['var_late'] . 'w',
			'trend' => $scurve['scurve'][0]['trend'],
			'chartType' => "long",
			'viewType' => "2",
		);
	}

	$galleryFormatter = array("title"=> strtoupper($slug).' Image Gallery',"items" => $gallery);
	$finalQRM = array("QRM" => $kpi);
	$finalKAD = array("KAD" => $kad);
	$finalINFO = array("INFO" =>$info);
	$finalGALLERY = array("gallery" => $galleryFormatter);
	$finalSCURVE = array("scurve" => (sizeof($scurve['scurve'])>0 ? $scurvearr : []));
	$superFinal = array($slug => array_merge($finalQRM, $finalKAD, $finalINFO, $finalGALLERY, $finalSCURVE));
	return json_encode($superFinal);
}
/**
 * UNDER GROUND (UG)
 * @param $slug
 * @return Array
 * @Desc  Portlet specific info of Under Ground (UG)
 */
function build_ug($slug){
	$tunnel_progress = tunnel_progress($slug);
	$info = packageInfo($slug,2);
	$info_tunnel = info_tunnel("tunnel");
	$station_progress = ug_station_progress($slug);
	//$gallery = gallery($slug);
	$gallery_tunnel = gallery($slug);
	$kad = kad($slug,2);
	$kad_tunnel = kad($slug,2);
	$hsse = safety_incident($slug);
	$hsse_tunnel = safety_incident($slug);
	$scurve = scurve($slug);


	//station progress
	$station_progress_arr=new stdClass();
	$station_progress_arr->date = date('d-M-y', strtotime($station_progress[0]['asof']));
	foreach($station_progress as $q){
		$station_progress_arr->$q['station_name'] = array("progress" => (float) $q['progress']);
	}

	$finalPROGRESS = array("overall_tunnel_progress" => $tunnel_progress);
	$finalSP =  array("station" => $station_progress_arr);
	$finalKAD = array("KAD" => $kad);
	$finalKADTEL = array("KAD_TUNNEL" => $kad_tunnel);
	$finalINFO = array("INFO" =>$info);
	$finalINFOTEL = array("tunnel" =>$info_tunnel);
	$finalHSSE = array("hsse" => $hsse);
	$finalHSSETEL = array("hsse_tunnel" => $hsse_tunnel);
	//$finalGALLERY = array("gallery" => $gallery);
	$finalGALLERYTEL = array("gallery_tunnel" => $gallery_tunnel);
	$finalSCURVE = array("scurve" => $scurve);
	$superFinal = array($slug => array_merge($finalPROGRESS, $finalINFO, $finalKAD, $finalSP, $finalHSSE, $finalSCURVE, $finalINFOTEL, $finalKADTEL, $finalHSSETEL, $finalGALLERYTEL));
	return json_encode($superFinal);
}

/**
 * UNDER GROUND STATIONS
 * @param $slug
 * @return Array
 * @Desc  Portlet specific info of Under Ground (UG)
 */
function build_ug_stations($slug){

	$info = packageInfo($slug,2);
	$gallery = gallery($slug);
	$kad = kad($slug,2);
	$hsse = safety_incident($slug);
	$station_activity = ug_station_activity($slug);

	$finalKAD = array("KAD" => $kad);
	$finalINFO = array("INFO" =>$info);
	$finalHSSE = array("hsse" => $hsse);
	$finalSA = array("station_activity" => $station_activity);
	$finalGALLERY = array("gallery" => $gallery);
	$superFinal = array($slug => array_merge($finalINFO, $finalKAD, $finalHSSE, $finalSA, $finalGALLERY));
	return json_encode($superFinal);
}

/**
 *	DEPOT
 * @param $slug
 * @return Array
 * @Desc  Portlet specific info of Under Ground (UG)
 */
function build_depot($slug){

	$info = packageInfo($slug,2);
	$gallery = gallery($slug);
	$kpi = kpi($slug);
	$kad = kad($slug,2);
	$kd = kad($slug,2);
	$hsse = safety_incident($slug);
	$scurve = scurve($slug);

	//SCURVE
	if(sizeof($scurve['scurve'])>0 ) {
		$actual = array();
		$late = array();
		$early = array();
		foreach ($scurve['scurve_main'] as $q) {
			if ($q['actual_data'] != '-')
				$actual[] = (float)$q['actual_data'];
			if ($q['delayed_data'] != '-')
				$late[] = (float)$q['delayed_data'];
			if ($q['early_data'] != '-')
				$early[] = (float)$q['early_data'];
		}
		$scurvearr = array(
			'date' => date('d-M-y', strtotime($scurve['scurve'][0]['scurve_date'])),
			'actualData' => $actual,
			'earlyData' => $early,
			'delayedData' => $late,
			'currentEarly' => $scurve['scurve'][0]['early_data'] . '%',
			'currentLate' => $scurve['scurve'][0]['delayed_data'] . '%',
			'currentActual' => $scurve['scurve'][0]['actual_data'] . '%',
			'varEarly' => $scurve['scurve'][0]['var_early'] . 'w',
			'varLate' => $scurve['scurve'][0]['var_late'] . 'w',
			'trend' => $scurve['scurve'][0]['trend'],
			'chartType' => "long",
			'viewType' => "2",
		);
	}

	$galleryFormatter = array("title"=> strtoupper($slug).' Image Gallery',"items" => $gallery);
	$finalQRM = array("QRM" => $kpi);
	$finalKD = array("KD" => $kd);
	$finalKAD = array("KAD" => $kad);
	$finalINFO = array("INFO" =>$info);
	$finalHSSE = array("hsse" => $hsse);
	$finalGALLERY = array("gallery" => $galleryFormatter);
	$finalSCURVE = array("scurve" => (sizeof($scurve['scurve'])>0 ? $scurvearr : []));
	$superFinal = array($slug => array_merge($finalQRM, $finalINFO, $finalKAD, $finalKD, $finalHSSE, $finalGALLERY, $finalSCURVE));
	return json_encode($superFinal);
}

/**
 * S-CURVES (PROGRAMME)
 * @param $slug
 * @return Array
 * @Desc
 */
function build_scurves($slug){

	$overall_elevated = scurve('overall_elevated');

	if(sizeof($overall_elevated['scurve'])>0 ) {
		$asofdate = $overall_elevated['scurve'][0]['scurve_date'];
	}
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

	if(isset($asofdate)) {
		return array("asofdate" => $asofdate, "value" => json_encode($superFinal));
	}else{
		return array("value" => json_encode($superFinal));
	}
}

/**
 * MSPR
 * @param $slug
 * @return Array
 * @Desc
 */
function build_mspr($slug){

	$info = packageInfo($slug,2);
	$gallery = gallery($slug);
	$kad = kad($slug,2);
	$scurve = scurve($slug);

	//SCURVE
	if(sizeof($scurve['scurve'])>0 ) {
		$actual = array();
		$late = array();
		$early = array();
		foreach ($scurve['scurve_main'] as $q) {
			if ($q['actual_data'] != '-')
				$actual[] = (float)$q['actual_data'];
			if ($q['delayed_data'] != '-')
				$late[] = (float)$q['delayed_data'];
			if ($q['early_data'] != '-')
				$early[] = (float)$q['early_data'];
		}
		$scurvearr = array(
			'date' => date('d-M-y', strtotime($scurve['scurve'][0]['scurve_date'])),
			'actualData' => $actual,
			'earlyData' => $early,
			'delayedData' => $late,
			'currentEarly' => $scurve['scurve'][0]['early_data'] . '%',
			'currentLate' => $scurve['scurve'][0]['delayed_data'] . '%',
			'currentActual' => $scurve['scurve'][0]['actual_data'] . '%',
			'varEarly' => $scurve['scurve'][0]['var_early'] . 'w',
			'varLate' => $scurve['scurve'][0]['var_late'] . 'w',
			'trend' => $scurve['scurve'][0]['trend'],
			'chartType' => "long",
			'viewType' => "2",
		);
	}
    $galleryFormatter = array("title"=> strtoupper($slug).' Image Gallery',"items" => $gallery);
	$finalKAD = array("KAD" => $kad);
	$finalINFO = array("INFO" =>$info);
	$finalGALLERY = array("gallery" => $galleryFormatter);
	$finalSCURVE = array("scurve" => (sizeof($scurve['scurve'])>0 ? $scurvearr : []));
	$superFinal = array($slug => array_merge($finalINFO,  $finalKAD, $finalGALLERY, $finalSCURVE));
	return json_encode($superFinal);
}

/**
 * PROCUREMENT
 * @param $slug
 * @return Array
 * @Desc  Portlet specific info of Under Ground (UG)
 */
function build_procurement($slug){
}

/**
 * DASHBOARD
 * @param $slug
 * @return Array
 * @Desc  Portlet specific info of Under Ground (UG)
 */
function build_dashboard($get_asof = false){

	$commercial = dashboard();
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

/**
 * Systems Summary
 * @param $slug
 * @return Array
 * @Desc  Portlet specific info of Under Ground (UG)
 */
function build_systems_summary($slug){
	$arr_slugs = array();
	array_push($arr_slugs, "stc-psd-apg","icss-cmms","etde","twmv","psds","comms","afc");
	$summary = ns_summary($arr_slugs);
	$finalSUMMARY = array("syspackage" => $summary);
	$superFinal = array($slug => array_merge($finalSUMMARY));
	return json_encode($superFinal);
}

/**
 * Viaducts Summary
 * @param $slug
 * @return Array
 * @Desc  Portlet specific info of Under Ground (UG)
 */
function build_viaducts_summary($slug){

	$arr_slugs = array();
	if($slug==="north"){
		array_push($arr_slugs, "v201","v202","v203","mspr1","mspr2","mspr3","mspr4","mspr5");
	}else if($slug==="south"){
		array_push($arr_slugs, "v204","v205","v206","v207", "v208", "v209", "v210","serdang-dpt","mspr6","mspr7","mspr8","mspr9","mspr10", "mspr11","mspr12","mspr13","mspr14","mspr15");
	}
	$summary = ns_summary($arr_slugs);
	$finalSUMMARY = array("scorecard" => $summary);
	$superFinal = array($slug => array_merge($finalSUMMARY));
	return json_encode($superFinal);
}

/**
 * Utility Function (Copied from the "tojson(line1)")
 * @param $data, $asofdate
 * @return Array
 * @Desc   Utility Function (Copied from the "tojson(line1)")
 */
function build_me_an_scurve_array($data,$asofdate = TRUE){
	if(sizeof($data['scurve'])>0) {
		$actual = array();
		$late = array();
		$early = array();
		foreach ($data['scurve_main'] as $q) {
			if ($q['actual_data'] != '-')
				$actual[] = (float)$q['actual_data'];
			if ($q['delayed_data'] != '-')
				$late[] = (float)$q['delayed_data'];
			if ($q['early_data'] != '-')
				$early[] = (float)$q['early_data'];
		}
		$scurvearr = array(
			'date' => date('d-M-y', strtotime($data['scurve'][0]['scurve_date'])),
			'actualData' => $actual,
			'earlyData' => $early,
			'delayedData' => $late,
			'currentEarly' => $data['scurve'][0]['early_data'] . '%',
			'currentLate' => $data['scurve'][0]['delayed_data'] . '%',
			'currentActual' => $data['scurve'][0]['actual_data'] . '%',
			'varEarly' => $data['scurve'][0]['var_early'] . 'w',
			'varLate' => $data['scurve'][0]['var_late'] . 'w',
			'trend' => $data['scurve'][0]['trend'],
			'chartType' => "long",
			'viewType' => "1",
		);


		if (!$asofdate)
			unset($scurvearr['date']);

		return $scurvearr;
	}else{
		return [];
	}
}

/**
 * Slugify
 * @param $text
 * @return String
 * @Desc   Sort and Reduce (Copied from the "tojson(line1)")
 */
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

	if (empty($text)) {
		return 'n-a';
	}

	return $text;
}

/**
 * Update the Line2 DB
 * @param $slug, $value, $date
 * @Desc Updates if item_id and date exists otherwise Inserts.
 */
function updateDB($slug, $value, $date){
	$item = mpxd()->items()
			->where("slug", $slug);
	$item = $item->fetch();
	if($item){
		$id = ($item['id']);
		$name = ($item['name']);
		$data = mpxd()->data_sources()
			->where("item_id = $id AND date = '$date'"); //Check if there's a record for given date.
		$datax = $data->fetch();
		if($datax){ // exist
			$d = array("value" => $value);
			$data->update($d);
		}else{ //new row
			$d = array("item_id" => $id , "value" => $value, "date" => $date, "name" => $name);
			$ds = mpxd()->data_sources(); //echo $ds->count();
			$ds->insert($d);
		}
	}
}
function run(){
	$query = db()->ref_slug()
		         ->select('slug','category');
	$slug_ref = array_map('iterator_to_array', iterator_to_array($query));
	//Job date
	$date = date("Y-m-d");
	foreach($slug_ref as $q) {
		switch ($q['category']) {
			case 1:
				$viaduct = build_viaducts($q['slug']);
				updateDB($q['slug'], $viaduct, $date);
				break;
			case 2:
				$stations = build_stations($q['slug']);
				updateDB($q['slug'], $stations, $date);
				break;
			case 3:
				$depot = build_depot($q['slug']);
				updateDB($q['slug'], $depot, $date);
				break;
			case 4:
				$ug = build_ug($q['slug']);
				updateDB($q['slug'], $ug, $date);
				break;
			case 5:
				$ug_station = build_ug_stations($q['slug']);
				updateDB($q['slug'], $ug_station, $date);
				break;
			case 6:
				$systems = build_systems($q['slug']);
				updateDB($q['slug'], $systems, $date);
				break;
			case 7:
				$scurves = build_scurves($q['slug']);
				updateDB($q['slug'], $scurves, $date);
				break;
			case 8:
				$mspr = build_mspr($q['slug']);
				updateDB($q['slug'], $mspr, $date);
				break;
			case 9:
				$procurement = build_procurement($q['slug']);
//				updateDB($q['slug'], $procurement, $date);
				break;
			case 10:
				$dashboard = build_dashboard();
				updateDB($q['slug'], $dashboard, build_dashboard(false));
				break;
			case 11:
				$systems_summary = build_systems_summary($q['slug']);
				updateDB($q['slug'], $systems_summary, $date);
				break;
			case 12:
				$viaducts_summary = build_viaducts_summary($q['slug']);
				updateDB($q['slug'], $viaducts_summary, $date);
				break;
			default:
				echo "Nothing to run";
		}
	}
}
//This run on call
//-----------Build
run();
?>