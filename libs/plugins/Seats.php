<?php 

namespace REJ\Libs;

class Seats {

	public $jsonData = array();
	private $dataType = array('sitemap','seats');
	private $sess;
	private $error;
	private $global;

	public function __construct( $glob_var ) {
		$this->global = $glob_var;
		$this->sess = $glob_var['session'];
		$this->error = $glob_var['error_file_path'];
	}
	
	public function extractDataSiteMap( $db, $site ) {
		$map_name = $site . " site map";
		$img = $this->global['site']['mapimg'];

		$get_time = date("Y-m-d H:i:s");
		$curr_date = date("Y-m-d");
		$datetime = date("Y-m-d H:i:s");
		$curr_time = date("H:i:s");
		$cv = strtotime(date("Y-m-d"), " -1 day");
		$yest_date = gmdate("Y-m-d", $cv);
		$curr_datetime = gmdate("Y-m-d", $cv) . ' ' . date("H:i:s");

		$rs = $db->execQuery("SELECT 
								map.campaign_id, 
								map.left, 
								map.top,
								map.width,
								map.height,
								area.area_name,
								area.area_color,
								(
									SELECT 
										count(station_name) 
									FROM 
										tbl_station_info 
									WHERE campaign_id=map.campaign_id
								),
								map.label_top,
								map.label_left,
								map.label_width
							FROM tbl_map_coor AS map
							INNER JOIN tbl_map_areas AS area
							ON map.campaign_id = area.a_id
							WHERE area.this_site='" . $site . "'",array(),"num");

		$camp_info = $db->execQuery("CALL usp_map_whole('" . $datetime . "')",array(),"num");
		$inc_camp = array();
		$camp_util = array();

		$active = 0;
		$late = 0;
		$absent = 0;
			
		foreach($camp_info as $info) {
			if($info[5] !== null) {
				$absent = $info[2];
				$active = $info[3];
				$late = $info[4];
				
				$tmp['aID'] = $info[5];
				$tmp['area'] = $info[6];
				$tmp['camp_name'] = $info[0];
				$tmp['Active'] = $info[3];
				$tmp['Absent'] = $info[2];
				$tmp['Late'] = $info[4];
				$tmp['OverBreak'] = $info[7];
				$inc_camp[$info[5]][] = $tmp;
			}
		}

		$active = 0;
		$late = 0;
		$absent = 0;

		$area_clist = array();

		$areas = array();
		$ddata = array();

		foreach($rs as $coordinate) {

			$tmp['cID'] = $coordinate[0];
			$tmp['left'] = $coordinate[1];
			$tmp['top'] = $coordinate[2];
			$tmp['width'] = $coordinate[3];
			$tmp['height'] = $coordinate[4];
			$tmp['l_top'] = $coordinate[8];
			$tmp['l_left'] = $coordinate[9];
			$tmp['l_width'] = $coordinate[10];
			$tmp['cName'] = $coordinate[5];
			$tmp['loop'] = (array_key_exists($coordinate[0], $inc_camp)) ? 0 : 1;
			$tmp['num_seats'] = $coordinate[7];
			$tmp['a_clist'] = (array_key_exists($coordinate[0], $inc_camp)) ? $inc_camp[$coordinate[0]] : '';
			$areas[] = $tmp;
			$dtmp['Area'] = $coordinate[5];
			$dtmp['Color'] = $coordinate[6];
			$ddata[] = $dtmp;
			
		}

		$return = array(
			'areas'=>$areas,
			'img'=>$img,
			'site_map'=>$map_name,
			'summary' => $ddata,
			'timezone'=>$get_time
		);

		//echo json_encode($return);
		
		$is_cache = $db->execQuery("SELECT cache_id FROM tbl_cache_json_seats WHERE type='" . $this->dataType[0] . "'",array(),"count");

		if($is_cache > 0) {
			$upd = $db->execQuery("UPDATE tbl_cache_json_seats
									SET 
										json='" . json_encode($return) . "',
										last_updated='" . $datetime . "'
									WHERE type='" . $this->dataType[0] . "'",array(),"update");

			return ($upd) ? true : false;
		} else {
			$ins = $db->execQuery("INSERT INTO tbl_cache_json_seats
								(
									type,
									json,
									last_updated
								)
								VALUES 
								(
									'" . $this->dataType[0] . "',
									'" . json_encode($return) . "',
									'" . $datetime . "'
								)",array(),"insert");

			return ($ins) ? true : false;
		}
		
	}


	public function extractDataSeats( $db ) {
		$curdate = date("Y-m-d H:i:s");

		$date = strtotime(date("Y-m-d H:i:s") . " - 7 hour");
		//$curdate = gmdate("Y-m-d H:i:s", $date);
		$rs_size = $db->execQuery("SELECT a_id, grid_x, grid_y, area_name FROM tbl_map_areas",array(),"num");

		foreach($rs_size as $msize) {
			//$rs_size = $db->execQuery("SELECT a_id, grid_x, grid_y, area_name FROM tbl_map_areas WHERE a_id='" . $camp_post . "'","rows");
			$rs_seats = $db->execQuery("SELECT position, station_name FROM tbl_station_info WHERE campaign_id='" . $msize[0] . "'",array(),"num");
					
			$rs = $db->execQuery("CALL usp_map_specific('" . $curdate . "'," . $msize[0] . ")",array(),"num");

			$return = array();
			$map_info = array();
			$map_info_tmp = array();
			$map_all_seats = array();
			$map_checker = array();
			$map_content = array();
			$map_size = array();
			$count = 0;
			$data_count = 0;
			$camp_name = "";
			$map_id = 0;
			$camp_in_map = array();

			$map_size['x'] = $msize[1];
			$map_size['y'] = $msize[2];
			$camp_name = $msize[3];

			foreach($rs_seats as $pc) {
				$map_checker[] = $pc[0];
				$map_all_seats[$pc[0]] = $pc[1]; 
			}

			foreach($rs as $seat) {
				$map_id = $seat[0];
				$map_content[] = $seat[4];
				$map_info_tmp[$seat[4]]['Station'] = $seat[3];
				$map_info_tmp[$seat[4]]['Position'] = $seat[4];
				$map_info_tmp[$seat[4]]['Agent'] = $seat[7];
				$map_info_tmp[$seat[4]]['Status'] = $seat[8];
				$map_info_tmp[$seat[4]]['Start'] = $seat[5];
				$map_info_tmp[$seat[4]]['End'] = $seat[6];
				$map_info_tmp[$seat[4]]['Reason'] = $seat[11];
				$map_info_tmp[$seat[4]]['TotalPause'] = $seat[12];
				$map_info_tmp[$seat[4]]['OverBreak'] = $seat[13];
				
				$exp_lid = explode(",", $seat[14]);
				$arr_lid = array();
				foreach($exp_lid as $lid) {
					$arr_lid[] = trim($lid);
				}
				
				$map_info_tmp[$seat[4]]['lID'] = $arr_lid;
				$map_info_tmp[$seat[4]]['forceEnd'] = $seat[15];
				$map_info_tmp[$seat[4]]['CampCol'] = $seat[16];
				$map_info_tmp[$seat[4]]['CampName'] = $seat[17];
				$map_info_tmp[$seat[4]]['GettyVer'] = $seat[18];
				$map_info_tmp[$seat[4]]['wSched'] = $seat[19];
				$map_info_tmp[$seat[4]]['schedStart'] = $seat[20];
				$map_info_tmp[$seat[4]]['schedEnd'] = $seat[21];
				$map_info_tmp[$seat[4]]['gPic'] = $this->global['adconf']['getuserpic'] . $seat[7];
				if(!in_array(array($seat[17],$seat[16]), $camp_in_map)) {
					$camp_in_map[] = array($seat[17],$seat[16]);
				}
			}

			$cnt_all_seats = 0;
			$cnt_occupy = 0;

			for($y = 0; $y < $map_size['y']; $y++) {
				for($x = 0; $x < $map_size['x']; $x++) {
					if(!in_array("$y-$x", $map_content)) {
						$is_vacant = (in_array("$y-$x", $map_checker)) ? $map_all_seats["$y-$x"] : "";
						$map_info[$data_count]['Station'] = $is_vacant;
						$map_info[$data_count]['Position'] = "$y-$x";
						$map_info[$data_count]['Agent'] = "";
						$map_info[$data_count]['Status'] = "";
						$map_info[$data_count]['Start'] = "";
						$map_info[$data_count]['End'] = "";
						$map_info[$data_count]['TotalPause'] = "";
						$map_info[$data_count]['OverBreak'] = "";
						$map_info[$data_count]['lID'] = array("");
						$map_info[$data_count]['forceEnd'] = "";
						$map_info[$data_count]['CampCol'] = "";
						$map_info[$data_count]['CampName'] = "";
						$map_info[$data_count]['GettyVer'] = "";
						$map_info[$data_count]['gPic'] = "";
						$check = trim($is_vacant);
						$cnt_all_seats += (!empty($check)) ? 1 : 0;
					} else {
						$map_info[$data_count]['Station'] = $map_info_tmp["$y-$x"]['Station'];
						$map_info[$data_count]['Position'] = $map_info_tmp["$y-$x"]['Position'];
						$map_info[$data_count]['Agent'] = $map_info_tmp["$y-$x"]['Agent'];
						$map_info[$data_count]['Status'] = $map_info_tmp["$y-$x"]['Status'];
						$map_info[$data_count]['Start'] = $map_info_tmp["$y-$x"]['Start'];
						$map_info[$data_count]['End'] = $map_info_tmp["$y-$x"]['End'];
						$map_info[$data_count]['Reason'] = $map_info_tmp["$y-$x"]['Reason'];
						$map_info[$data_count]['TotalPause'] = $map_info_tmp["$y-$x"]['TotalPause'];
						$map_info[$data_count]['OverBreak'] = $map_info_tmp["$y-$x"]['OverBreak'];
						$map_info[$data_count]['lID'] = $map_info_tmp["$y-$x"]['lID'];
						$map_info[$data_count]['forceEnd'] = $map_info_tmp["$y-$x"]['forceEnd'];
						$map_info[$data_count]['CampCol'] = $map_info_tmp["$y-$x"]['CampCol'];
						$map_info[$data_count]['CampName'] = $map_info_tmp["$y-$x"]['CampName'];
						$map_info[$data_count]['GettyVer'] = $map_info_tmp["$y-$x"]['GettyVer'];
						$map_info[$data_count]['wSched'] = $map_info_tmp["$y-$x"]['wSched'];
						$map_info[$data_count]['schedStart'] = $map_info_tmp["$y-$x"]['schedStart'];
						$map_info[$data_count]['schedEnd'] = $map_info_tmp["$y-$x"]['schedEnd'];
						$map_info[$data_count]['gPic'] = $map_info_tmp["$y-$x"]['gPic'];
						$cnt_occupy++;
						$cnt_all_seats++;
					}
					$data_count++;
				}
			}

			$area_util = ($cnt_occupy == 0) ? 0 : (($cnt_occupy / $cnt_all_seats) * 100);

			$return = array(
				'size'=>$map_size,
				'onboard'=>$map_info,
				'campaign'=>$camp_name,
				'mp_unq'=>$map_id,
				'num_seats'=>$cnt_all_seats,
				'occupied'=>$cnt_occupy,
				'util'=> round($area_util, 1, PHP_ROUND_HALF_EVEN),
				'map_camp'=>$camp_in_map,
				'errimg'=>'ajax/get_image.php'
			);

			//echo json_encode($return);
			$is_cache = $db->execQuery("SELECT cache_id FROM tbl_cache_json_seats WHERE type='" . $this->dataType[1] . "' AND area=" . $msize[0], array(),"count");

			if($is_cache > 0) {
				$db->execQuery("UPDATE tbl_cache_json_seats 
								SET
									json='" . json_encode($return) . "',
									last_updated='" . $curdate . "'
								WHERE 
									type='" . $this->dataType[1] . "' 
								AND area=" . $msize[0],array(),"update");

			} else {
				$db->execQuery("INSERT INTO tbl_cache_json_seats 
									(
										type,
										json,
										last_updated,
										area
									)
									VALUES 
									(
										'" . $this->dataType[1] . "',
										'" . json_encode($return) . "',
										'" . $curdate . "',
										" . $msize[0] . "
									)",array(),"insert");

			}
		}
	}

	public function retrieveCacheDataSiteMap( $db, $site ) {

		$jsona = "";
		$is_cache = $db->execQuery("SELECT cache_id FROM tbl_cache_json_seats WHERE type='" . $this->dataType[0] . "'",array(),"count");
		
		if($is_cache > 0) {
			$json = $db->execQuery("SELECT json FROM tbl_cache_json_seats WHERE type='" . $this->dataType[0] . "'",array(),"num");
			$jsona = html_entity_decode(html_entity_decode($json[0][0]));
		} else {
			if($this->extractDataSiteMap($db, $site)) {
				$json = $db->execQuery("SELECT json FROM tbl_cache_json_seats WHERE type='" . $this->dataType[0] . "'",array(),"num");
				$jsona = html_entity_decode(html_entity_decode($json[0][0]));
			}
		}

		return json_decode($jsona, true);
	}

	public function retrieveCacheDataSeats( $db, $site, $area ) {

		$jsona = "";
		$is_cache = $db->execQuery("SELECT cache_id FROM tbl_cache_json_seats WHERE type='" . $this->dataType[1] . "' AND area=" . $area,array(),"count");

		if($is_cache > 0) {
			$json = $db->execQuery("SELECT json FROM tbl_cache_json_seats WHERE type='" . $this->dataType[1] . "' AND area=" . $area,array(),"num");
			$jsona = html_entity_decode(html_entity_decode($json[0][0]));
		} else {
			if($this->extractDataSeats($db)) {
				$json = $db->execQuery("SELECT json FROM tbl_cache_json_seats WHERE type='" . $this->dataType[1] . "' AND area=" . $area,array(),"num");
				$jsona = html_entity_decode(html_entity_decode($json[0][0]));
			}
		}

		return json_decode($jsona, true);
	}
}