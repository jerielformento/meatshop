<?php 

namespace REJ\Libs;

class Monitoring {
	
	public $users = array();
	public $jsonData = array();
	public $maxUtilization = array();
	public $utilLists = array();
	private $agents = array();
	private $scheds = array();
	private $scheds_info = array();
	private $convertTo = "";
	private $agent = "";
	private $curr_agent = "";
	private $campaign = "";
	private $team = "";
	private $date_req = "";
	private $st_stamp = "";
	private $en_stamp = "";
	private $st_break = "";
	private $en_break = "";
	private $timefrom = "";
	private $timeto = "";
	private $sched_start = "";
	private $sched_end = "";
	private $last_st = "";
	private $last_en = "";
	private $last_date = "";
	private $status = "";
	private $get_pre_shift = "";
	private $get_post_shift = "";
	private $cell = 0;
	private $counter = 0;		
	private $data_count = 1;
	private $agt_count = 0;
	private $total_pauses = 0;
	private $sess;
	private $error;
	private $global;

	public function __construct( $glob_var ) {
		$this->global = $glob_var;
		$this->sess = $glob_var['session'];
		$this->error = $glob_var['error_file_path'];
	}
	
		
	public function timeConverter( $def, $est, $dst ) {
		$conv = "";
		if($def == 1) {
			$conv = "";
		} else if($est == 1) {
			$conv = "est";
		} else if($dst == 1) {
			$conv = "dst";
		}
		
		return $conv;
	}
	
	
	public function extractMonitoringData( $db, $date, $curr_time, $timezone, $site_id, $campaign_id, $team_id ) {
		
		//$this->users = $db->execQuery("CALL usp_Utilization_Uni_a('" . $date . "','" . $timezone . "'," . $site_id . "," . ((empty($campaign_id)) ? 'null' : $campaign_id) . "," . ((empty($team_id)) ? 'null' : $team_id) . ",'" . $curr_time . "')","rows");

		$this->users = $db->execQuery("CALL usp_Monitoring('" . $date . "','" . $timezone . "'," . $site_id . "," . ((empty($campaign_id)) ? 'null' : $campaign_id) . "," . ((empty($team_id)) ? 'null' : $team_id) . ",'" . $curr_time . "')",array(),"num");
		$this->maxUtilization = $this->getMaxUtilizationPerHr( $db, $date, $campaign_id, $team_id, '', $timezone ); 
		
	}
	
	
	public function extractMonitoringDataCache( $db, $date, $date_to, $curr_time, $timezone, $site_id, $campaign_id, $team_id ) {

		$this->jsonData = $this->retrieveCacheData( $db, $timezone, $date, $date_to, $campaign_id, $team_id );

		if(!empty($campaign_id)) {
			$this->users = $this->jsonData['users'];
			$this->maxUtilization = $this->getMaxUtilizationPerHr( $db, $date, $campaign_id, $team_id, '', $timezone ); 
		} else {
			$this->users = $this->jsonData['users'];
			$this->maxUtilization = array(
				$this->jsonData['m_util'], 
				$this->jsonData['as_of'], 
				$this->jsonData['names']
			);
		}
	}

	public function retrieveCacheData( $db, $timezone, $date, $date_to, $campaign_id, $team_id ) {
		$json = "";
		$jsona = "";
		
		$begin = date_create( $date );
		$end = date_create( $date_to );
		$users = array();
		
		$where_c = "";
		$count_range = 0;
		for($i = $begin; $i <= $end; $i->modify('+1 day')) {
			//echo $i->format("Y-m-d") . "<br/>";
			$date_explode = explode("-", $i->format("Y-m-d"));
			$where_c  .= " (year='" . $date_explode[0] . "' AND month='" . $date_explode[1] . "' AND day='" . $date_explode[2] . "' AND timezone='" . $timezone . "') OR";
			$count_range++;
		}
		
		if($count_range <= 31) {
			//die("SELECT json, CONCAT(year,'-',month,'-',day) date FROM tbl_cache_json WHERE " . substr($where_c,0,-2) . " GROUP BY year,month,day");
			$is_cached = $db->execQuery("SELECT cache_id FROM tbl_cache_json WHERE " . substr($where_c,0,-2) . " GROUP BY year,month,day",array(),"count");

			if($is_cached > 0) {
				//die("SELECT json FROM tbl_cache_json WHERE " . substr($where_c,0,-2));
				$json = $db->execQuery("SELECT json, CONCAT(year,'-',month,'-',day) date FROM tbl_cache_json WHERE " . substr($where_c,0,-2) . " GROUP BY year,month,day",array(),"num");

				//$jsona = html_entity_decode(html_entity_decode($json[0][0]));
				$jsona = array(
					'as_of'=>'',
					'm_util'=>'',
					'names'=>array(),
					'users'=>array()
				);

				$count_jtmp = 0;
				
				foreach($json as $u) {
					$uu = html_entity_decode(html_entity_decode($u[0]));
					$uu = json_decode($uu, true);	
					//$uu = $uu['users'][$count_jtmp]['users'][$count_jtmp];
					//echo $uu['users']; die();
					/*jtmp = html_entity_decode(html_entity_decode($u[0]));
					$jtmp = json_decode($jtmp, true);*/
					$jsona['as_of'] = $uu['as_of'];
					$jsona['m_util'] = $uu['m_util'];
					$jsona['names'] = $uu['names'];

					foreach($uu['users'] as $ux) {
						$jsona['users'][] = $this->monitoringResultArrangement($ux, $u[1]); 
					}


					$count_jtmp++;
				}

				//$jsona = html_entity_decode(html_entity_decode(json_encode($json[0][0])));
				// just for now

				if(!empty($campaign_id)) {
					foreach($json as $ja) {
						$fetch_cached = json_decode(html_entity_decode(html_entity_decode($ja[0])), true);

						foreach($fetch_cached['users'] as $user) {
							$user[] = $ja[1];
							if(!empty($team_id)) {
								// 13 campaign id
								// 14 team id
								if($user[13] == $campaign_id && $user[14] == $team_id) {
									$team_user = $this->monitoringResultArrangement($user, $ja[1]); 
									array_push($users, $team_user);
								}
							} else {
								if($user[13] == $campaign_id) {
									$team_user = $this->monitoringResultArrangement($user, $ja[1]);
									array_push($users, $team_user);
								}
							}
						}

						//print_r($users); die();
					}

					return array(
						'users' => $users
					);	
				}
			} else {
				// cache data based on date selected 
				$this->cacheNewData( $db, $date, date("Y-m-d H:i:s"), $this->global['site']['id'], $campaign_id, $team_id );

				// retrieve the new data cached based on date selected
				$json = $db->execQuery("SELECT json, CONCAT(year,'-',month,'-',day) date FROM tbl_cache_json WHERE year='" . $date_explode[0] . "' AND month='" . $date_explode[1] . "' AND day='" . $date_explode[2] . "' AND timezone='" . $timezone . "' ORDER BY year,month,day",array(),"num");
				$jsona = html_entity_decode(html_entity_decode($json[0][0]));

				if(!empty($campaign_id)) {
					$fetch_cached = json_decode($jsona, true);
					$users = array();

					foreach($fetch_cached['users'] as $user) {
						if(!empty($team_id)) {
							// 13 campaign id
							// 14 team id
							if($user[13] == $campaign_id && $user[14] == $team_id) {
								array_push($users, $user);
							}
						} else {
							if($user[13] == $campaign_id) {
								array_push($users, $user);
							}
						}
					}

					return array(
						'users' => $users
					);
				}
			}	
		} else {
			echo "wew";
		}
		
		return $jsona;
	}
	
	public function cacheNewData( $db, $param_date, $param_currtime, $param_site_id, $param_campaign_id, $param_team_id ) {
		$date_explode = explode("-", $param_date);
		$success_cached = false;

		$is_cached = $db->execQuery("SELECT cache_id FROM tbl_cache_json WHERE year=:year AND month=:month AND day=:day",array(
			':year'=>$date_explode[0],
			':month'=>$date_explode[1],
			':day'=>$date_explode[2]
		),"count");

		if($is_cached > 0) {
			$this->extractMonitoringData( $db, $param_date, $param_currtime, $this->global['init']['default_timezone'], $param_site_id, $param_campaign_id, $param_team_id );

			$return = array(
				'users'=>$this->users,
				'm_util'=>$this->maxUtilization[0],
				'as_of'=>$this->maxUtilization[1],
				'names'=>$this->maxUtilization[2]
			);

			$upd_cache_local = $db->execQuery("UPDATE tbl_cache_json SET
													json=:json,
													last_updated=:lastup
												WHERE year=:year
												AND month=:month
												AND day=:day
												AND timezone=:tz",array(
													':year'=>$date_explode[0],
													':month'=>$date_explode[1],
													':day'=>$date_explode[2],
													':lastup'=>$param_currtime,
													':json'=>json_encode($return),
													':tz'=>$this->global['init']['default_timezone']
												),"update");

			if($upd_cache_local) {
				$this->extractMonitoringData( $db, $param_date, $param_currtime, $this->global['init']['est_timezone'], $param_site_id, $param_campaign_id, $param_team_id );

				$return = array(
					'users'=>$this->users,
					'm_util'=>$this->maxUtilization[0],
					'as_of'=>$this->maxUtilization[1],
					'names'=>$this->maxUtilization[2]
				);

				$date_explode = explode("-", $param_date);

				$upd_cache_est = $db->execQuery("UPDATE tbl_cache_json SET
														json=:json,
														last_updated=:lastup
													WHERE year=:year
													AND month=:month
													AND day=:day
													AND timezone=:tz",array(
														':year'=>$date_explode[0],
														':month'=>$date_explode[1],
														':day'=>$date_explode[2],
														':lastup'=>$param_currtime,
														':json'=>json_encode($return),
														':tz'=>$this->global['init']['est_timezone']
													),"update");

				$success_cached = ($upd_cache_est) ? true : false;

			}
		} else {
			$this->extractMonitoringData( $db, $param_date, $param_currtime, $this->global['init']['default_timezone'], $param_site_id, $param_campaign_id, $param_team_id );

			$return = array(
				'users'=>$this->users,
				'm_util'=>$this->maxUtilization[0],
				'as_of'=>$this->maxUtilization[1],
				'names'=>$this->maxUtilization[2]
			);
			
			$ins_cache_local = $db->execQuery("INSERT INTO tbl_cache_json
										(
											year,
											month,
											day,
											timezone,
											json,
											last_updated
										)
										VALUES
										(
											:year,
											:month,
											:day,
											:tz,
											:json,
											:lastup
										)",array(
											':json'=>json_encode($return),
											':year'=>$date_explode[0],
											':month'=>$date_explode[1],
											':day'=>$date_explode[2],
											':tz'=>$this->global['init']['default_timezone'],
											':lastup'=>$param_currtime
										),"insert");

			if($ins_cache_local) {
				$this->extractMonitoringData( $db, $param_date, $param_currtime, $this->global['init']['est_timezone'], $param_site_id, $param_campaign_id, $param_team_id );

				$return = array(
					'users'=>$this->users,
					'm_util'=>$this->maxUtilization[0],
					'as_of'=>$this->maxUtilization[1],
					'names'=>$this->maxUtilization[2]
				);

				$date_explode = explode("-", $param_date);

				$ins_cache_est = $db->execQuery("INSERT INTO tbl_cache_json
											(
												year,
												month,
												day,
												timezone,
												json,
												last_updated
											)
											VALUES
											(
												:year,
												:month,
												:day,
												:tz,
												:json,
												:lastup
											)",array(
												':json'=>json_encode($return),
												':year'=>$date_explode[0],
												':month'=>$date_explode[1],
												':day'=>$date_explode[2],
												':tz'=>$this->global['init']['est_timezone'],
												':lastup'=>$param_currtime
											),"insert");

				$success_cached = ($ins_cache_est) ? true : false;
			}
		}

		return $success_cached;
	}

	public function extractCachedData( $filename, $campaign_id, $team_id ) {

		$cache_json_path = "../cron/json/";

		$cached_data = file_get_contents($cache_json_path . $filename);
		
		if(!empty($campaign_id)) {
			$fetch_cached = json_decode($cached_data, true);
			$users = array();

			foreach($fetch_cached['users'] as $user) {
				if(!empty($team_id)) {
					// 13 campaign id
					// 14 team id
					if($user[13] == $campaign_id && $user[14] == $team_id) {
						array_push($users, $user);
					}
				} else {
					if($user[13] == $campaign_id) {
						array_push($users, $user);
					}
				}
			}

			return array(
				'users' => $users
			);
		}

		return json_decode($cached_data, true);
	}
	
	private function setData( $agent, $camp, $team, $date_req, $start, $end, $from, $to, $sched_st, $sched_en, $pre, $post, $setType ) {

		if($setType == 'set') {
		
			$this->agent = $agent;
			$this->campaign = $camp;
			$this->team = $team;
			$this->date_req = $date_req;
			$this->st_stamp = $start . ",";
			$this->en_stamp = $end . ",";
			$this->timefrom = $from;
			$this->timeto = $to;
			$this->curr_agent = $agent;
			$this->last_date = $date_req;
			$this->last_st = $from;
			$this->last_en = $to;
			$this->sched_start = $sched_st;
			$this->sched_end = $sched_en;
			$this->get_pre_shift = $pre;
			$this->get_post_shift = $post;
			
		} else if($setType == 'concat') {
		
			$this->agent = $agent;
			$this->campaign = $camp;
			$this->team = $team;
			$this->date_req = $date_req;
			$this->st_stamp .= $start . ",";
			$this->en_stamp .= $end . ",";
			$this->timefrom = $from;
			$this->timeto = $to;
			$this->curr_agent = $agent;
			$this->last_date = $date_req;
			$this->last_st = $from;
			$this->last_en = $to;
			$this->sched_start = $sched_st;
			$this->sched_end = $sched_en;
			$this->get_pre_shift = $pre;
			$this->get_post_shift = $post;
			
		}	
	
	}
	
	
	private function compressData( $user, $camp, $team, $date_req, $in, $out, $from, $to, $remarks, $break, $pre, $post, $staffed ) {
		$tmp['Username'] = $user;
		$tmp['Campaign'] = $camp;
		$tmp['Team'] = $team;
		$tmp['DateBegin'] = $date_req;
		$tmp['In'] = $in;
		$tmp['Out'] = $out;
		$tmp['From'] = $from;
		$tmp['To'] = $to;
		$tmp['Remarks'] = $remarks;
		$tmp['Break'] = $break;
		$tmp['Pre'] = $pre;
		$tmp['Post'] = $post;
		$tmp['StaffedTime'] = $staffed;
		
		$this->users[$this->cell]['Agent'] = $tmp;
		$this->cell++;
	}
	
	
	private function checkRemarksExt( $curr_dt ) {
		$remarks = "";
		if(array_key_exists($this->agents[$this->agt_count][0], $this->scheds_info)) {
							
			foreach($this->scheds_info[$this->agents[$this->agt_count][0]] as $val) {
				if($val[2] == 1) {			
					$remarks = "Leave";
				} else {
					if($val[0].' '.$val[1] > $curr_dt) {
							$remarks = "Today's Schedule";
					} else {
							$remarks = "Absent";
					}
				}
			}
			
		} else {
			$remarks = "Rest Day";
		}
		
		return $remarks;
	}
	
	
	private function checkRemarks( $shift_start, $shift_end, $in, $out, $pre, $post ) {
		
		$active_arr = array('0001-01-01','0000-00-00', '');
		$remarks = "";
		$late = 0;
		$undertime = 0;
		$exp_out = explode(" ", $out);
		$pre_hour = "";
		$pre_min = "";
		$post_hour = "";
		$post_min = "";
		$shift_from = "";
		$shift_to = "";
		
		if(strlen($pre) === 3) {
			$pre_hour = substr($pre, 0, 1);
			$pre_min = substr($pre, 1, 2);
			$shift_from = date("Y-m-d H:i:s", strtotime($shift_start . " - $pre_hour hours"));
			$shift_from = date("Y-m-d H:i:s", strtotime($shift_from . " - $pre_min minutes"));
		} else {
			$pre_hour = $pre;
			$pre_min = "";
			$shift_from = date("Y-m-d H:i:s", strtotime($shift_start . " - $pre_hour hours"));
		}
		
		if(strlen($post) === 3) {
			$post_hour = substr($post, 0, 1);
			$post_min = substr($post, 1, 2);
			$shift_to = date("Y-m-d H:i:s", strtotime($shift_end . " + $post_hour hours"));
			$shift_to = date("Y-m-d H:i:s", strtotime($shift_to . " + $post_min minutes"));
		} else {
			$post_hour = $post;
			$post_min = "";
			$shift_to = date("Y-m-d H:i:s", strtotime($shift_end . " + $post_hour hours"));
		}
		
		$late = ($shift_from < $in) ? 1 : 0;
		$undertime = ($shift_to > $out) ? 1 : 0;
		
		if(in_array($exp_out[0], $active_arr)) {
			$remarks = "Active";
		} else if($late == 1 && $undertime == 1) {
			$remarks = "Late / Undertime";
		} else if($late == 0 && $undertime == 1) {
			$remarks = "Undertime";
		} else if($late == 1 && $undertime == 0) {
			$remarks = "Late";
		} else if($late == 0 && $undertime == 0) {
			$remarks = "Present";
		}
		
		return $remarks;
	}

	private function prepostFormat( $prepost ) {
		$mk_text = "";
		$_hour = "";
		$_min = "";
		
		if(strlen($prepost) === 3) {
			$_hour = substr($prepost, 0, 1);
			$_min = substr($prepost, 1, 2);
			$mk_text .= $_hour . "hr ";
			$mk_text .= $_min . "min";
		} else {
			$_hour = $prepost;
			$_min = "";
			$mk_text .= $_hour . "hr ";
		}

		return ($prepost == "0") ? "0" : $mk_text;
	}

	private function newFormat($time) {
		$active_arr = array('0001-01-01','0000-00-00');
		$exp_dt = explode(" ", $time);
		$checker = (in_array($exp_dt[0], $active_arr)) ? "N/A" : date("h:iA", strtotime($time));
		
		return $checker;
	}

	private function convertDateTime( $datetime, $r_type ) {
		$new_format = "";
		$active_arr = array('0001-01-01','0000-00-00');
		$exp_dt = explode(" ", $datetime);
		
		if(in_array($exp_dt[0], $active_arr) && $r_type == 'hour') {
			$return = "N/A";
		} else {
			$new_format = date("Y-m-d h:iA", strtotime($datetime)); 
			$exp_format = explode(" ", $new_format);
			
			$return = "";
			if($r_type == 'date') {
				$return = $exp_format[0];
			} else if($r_type == 'hour') {
				$return = $exp_format[1];
			} else {
				$return = $new_format;
			}
		}
		
		return $return;
	}

	private function getStaffedTime( $in, $out ) {
		$staffed = "";
		$from = strtotime($in);
		$to =  strtotime($out);

		$staffed = $to - $from;
		return gmdate("H:i:s", $staffed);
	}

	public function getMaxUtilizationPerHr( $db, $date, $campaign, $team, $type, $tzone ) {	
	
		$hours = array(
						'00:00:00','01:00:00','02:00:00',
						'03:00:00','04:00:00','05:00:00',
						'06:00:00','07:00:00','08:00:00',
						'09:00:00','10:00:00','11:00:00',
						'12:00:00','13:00:00','14:00:00',
						'15:00:00','16:00:00','17:00:00',
						'18:00:00','19:00:00','20:00:00',
						'21:00:00','22:00:00','23:00:00'
				);
				
		$max_util = 0;
		$camp_fil = (!empty($campaign)) ? $campaign : 'NULL';
		$team_fil = (!empty($team)) ? $team : 'NULL';
		$as_of = '';
		$as_of_val = 0;
		$interval = $this->getTimeZone($type);
		//$rs_max = $db->execQuery("CALL usp_util(" . $interval . ", '" . $date . "', " . $camp_fil . ")", "rows");
		$rs_max = $db->execQuery("CALL usp_MaxUtilization_Uni_comment('" . $date . "','" . $tzone . "', NULL, " . $camp_fil . ", " . $team_fil . ")", array(),"num");
		
		foreach($rs_max as $max) {
			$max_util = $max[1];
			$as_of = $max[0] . ":00:00";
			$as_of_val = $max[0];
		}
		/*foreach($rs_max as $util) {
			for($i = 0; $i < 24; $i++) {
				$exp_util = explode(" ", $util[$i]);
				if($max_util === 0) {	
					$max_util = $exp_util[0];
					$as_of = $exp_util[1];
				} else {
					if($exp_util[0] > $max_util) {
						$max_util = $exp_util[0];
						$as_of = $exp_util[1];
					}
				}
			}
		}*/
		
		//$utilist = $db->execQuery("CALL usp_util_list(" . $interval . ", '" . $date . "', " . $camp_fil . ",'" . $rs_max[0] . "')", "rows");
		#$utilist = $db->execQuery("CALL usp_MaxUtilization_Uni_userList('" . $date . "','" . $tzone . "', NULL, NULL, NULL, " . $as_of_val . ");", "rows");		
		/* echo "<PRE>";
		print_r("CALL usp_MaxUtilization_Uni_userList('" . $date . "','" . $tzone . "', NULL, " . $camp_fil . ", " . $team_fil . ", " . $as_of_val . ");");
		echo "</PRE>";
		die(); #*/
		$utilist = $db->execQuery("CALL usp_MaxUtilization_Uni_userList('" . $date . "','" . $tzone . "', NULL, " . $camp_fil . ", " . $team_fil . ", " . $as_of_val . ");",array(),"num");		
		
		
		$max_list = array();
		
		foreach($utilist as $list) {
			$tmp['agent'] = $list[0];
			$tmp['start'] = $list[1];
			$tmp['end'] = $list[2];
			
			$max_list[] = $tmp;
		}
		
		
		return array($max_util, $as_of, $max_list);
		
	}	

	
	public function retrieveCacheDataPerUserWeekly( $db, $timezone, $date, $campaign_id, $team_id, $username ) {
		$date_explode = explode("-", $date);
		$json = "";
		$jsona = "";

		$weekdate = $this->getWeekRange($date);
		$users = array();
		$getcurrdate = date("Y-m-d");

		foreach($weekdate as $day) {
			$user_tmp = array();
			if($day > $getcurrdate) {
				$tmp = array();

				$tmp[0] = 'N/A';
				$tmp[1] = 'N/A';
				$tmp[2] = $username;
				$tmp[3] = 'N/A';
				$tmp[4] = 'N/A';
				$tmp[5] = 'N/A';
				$tmp[6] = '0.00';
				$tmp[7] = '0.00';
				$tmp[8] = '';
				$tmp[9] = '00:00:00';
				$tmp[10] = '00:00:00';
				$tmp[11] = 'N/A';
				$tmp[12] = 'N/A';
				$tmp[13] = $camp_id;
				$tmp[14] = $team_id;
				$tmp[15] = 0;
				$tmp[16] = 0;
				$tmp[17] = "";
				$tmp[18] = "";
				$tmp[19] = "";
				$tmp[20] = "";
				$tmp[21] = date_format(date_create($day),"M d Y");
				$user_tmp[] = $tmp;

				array_push($users, $user_tmp);
			} else {
				$date_explode = explode("-", $day);
				$is_cached = $db->execQuery("SELECT cache_id FROM tbl_cache_json WHERE year='" . $date_explode[0] . "' AND month='" . $date_explode[1] . "' AND day='" . $date_explode[2] . "'",array(),"count");

				if($is_cached > 0) {
					$json = $db->execQuery("SELECT json FROM tbl_cache_json WHERE year='" . $date_explode[0] . "' AND month='" . $date_explode[1] . "' AND day='" . $date_explode[2] . "' AND timezone='" . $timezone . "'",array(),"num");
					$jsona = html_entity_decode(html_entity_decode($json[0][0]));
					// just for now
					
					//if(!empty($campaign_id)) {
						$fetch_cached = json_decode($jsona, true);
						$has_rec = 0;

						foreach($fetch_cached['users'] as $user) {
							if($user[2] === $username) {
								$user[] = date_format(date_create($day),"M d Y");

								if(!empty($team_id)) {
									// 13 campaign id
									// 14 team id
									if($user[13] == $campaign_id && $user[14] == $team_id) {
										array_push($user_tmp, $user);
										$has_rec += 1;
									}
								} else {
									//if($user[13] == $campaign_id) {
										array_push($user_tmp, $user);
										$has_rec += 1;
									//}
								}
							}
						}

						if($has_rec >= 1) {
							array_push($users, $user_tmp);
						} else {
							$tmp = array();

							$tmp[0] = 'N/A';
							$tmp[1] = 'N/A';
							$tmp[2] = $username;
							$tmp[3] = 'N/A';
							$tmp[4] = 'N/A';
							$tmp[5] = 'N/A';
							$tmp[6] = '0.00';
							$tmp[7] = '0.00';
							$tmp[8] = '';
							$tmp[9] = '00:00:00';
							$tmp[10] = '00:00:00';
							$tmp[11] = 'N/A';
							$tmp[12] = 'N/A';
							$tmp[13] = $camp_id;
							$tmp[14] = $team_id;
							$tmp[15] = 0;
							$tmp[16] = 0;
							$tmp[17] = "";
							$tmp[18] = "";
							$tmp[19] = "";
							$tmp[20] = "";
							$tmp[21] = date_format(date_create($day),"M d Y");
							$user_tmp[] = $tmp;

							array_push($users, $user_tmp);
						}
					//}
				} else {
					$tmp = array();
                                                                                                                     
					$tmp[0] = 'N/A';
					$tmp[1] = 'N/A';
					$tmp[2] = $username;
					$tmp[3] = 'N/A';
					$tmp[4] = 'N/A';
					$tmp[5] = 'N/A';
					$tmp[6] = '0.00';
					$tmp[7] = '0.00';
					$tmp[8] = '';
					$tmp[9] = '00:00:00';
					$tmp[10] = '00:00:00';
					$tmp[11] = 'N/A';
					$tmp[12] = 'N/A';
					$tmp[13] = $camp_id;
					$tmp[14] = $team_id;
					$tmp[15] = 0;
					$tmp[16] = 0;
					$tmp[17] = "";
					$tmp[18] = "";
					$tmp[19] = "";
					$tmp[20] = "";
					$tmp[21] = date_format(date_create($day),"M d Y");
					$user_tmp[] = $tmp;

					array_push($users, $user_tmp);
				}
			}
		}

		return array(
			'users' => $users
		);
	} 
	
	
	private function getWeekRange($date) {

		$curr = $date;
		$timestamp = strtotime($curr);

		$day = date('D', $timestamp);
		$day_week = array('Mon'=>array(0,6),'Tue'=>array(1,5),'Wed'=>array(2,4),'Thu'=>array(3,3),'Fri'=>array(4,2),'Sat'=>array(5,1),'Sun'=>array(6,0));

		$arr_get_dates = array();
		foreach($day_week as $days => $arg) {
			if($days === $day) {
				
				for($i = $arg[0]; $i > 0; $i--) {
					array_push($arr_get_dates, date('Y-m-d', strtotime($curr . "-" . $i . " days")));
				}
				
				for($j = 0; $j <= $arg[1]; $j++) {
					array_push($arr_get_dates, date('Y-m-d', strtotime($curr . "+" . $j . " days")));
				}
			}
		}

		return $arr_get_dates; 
	}
	

	private function getCurrentUtilization( $db, $date, $campaign, $type ) {

		$addstamp = $this->tZoneConv('dt_stamp', $type);
		$addstampto = $this->tZoneConv('dt_stamp_end', $type);
		$compare = 	$this->dateTimeConv(date("Y-m-d H:i:s"), $type);
		$addFilter = (!empty($campaign)) ? "AND campaign='" . $campaign . "'" : "";
		$currentDate = date("Y-m-d H:i:s");
		
		$c_util = $db->execQuery("SELECT
									COUNT(DISTINCT(station)) AS Num,
									DATE_FORMAT('" . $currentDate . "', '%e %b %Y %h:%i %p') AS Date
								FROM
									tbl_record_log
								WHERE
									status = '" . $this->global['adconf']['islog'] . "'
									AND dt_stamp_end='0001-01-01 00:00:00'
									AND (
										dt_stamp >= CAST(DATE_ADD('" . $compare . "', INTERVAL -9 HOUR) AS CHAR CHARACTER SET utf8)
									)
									" . $addFilter . "
									GROUP BY username",array(),"count"); 
		return $c_util;
	}

	private function monitoringResultArrangement( $arr, $user ) {
		$result = array(
			$arr[0],
			$arr[1],
			$arr[2],
			$arr[3],
			$arr[4],
			$arr[5],
			$arr[6],
			$arr[7],
			$arr[8],
			$arr[9],
			$arr[10],
			$arr[11],
			$arr[12],
			$arr[13],
			$arr[14],
			$arr[15],
			$arr[16],
			$arr[17],
			$arr[18],
			$arr[19],
			$arr[20],
			$user,
			$arr[21]
		);	
		
		return $result;
	}
	
	private function tZoneConv( $time, $type ) {
		$conv = "";
		if($type == 'est') {
			$conv .= "DATE_SUB(CONCAT(" . $time . "), INTERVAL " . $this->global['adconf']['timezones']['est'] . " HOUR)";
		} else if($type == 'dst') {
			$conv .= "DATE_SUB(CONCAT(" . $time . "), INTERVAL " . $this->global['adconf']['timezones']['utc'] . " HOUR)";
		} else {
			$conv .= "CONCAT(" . $time . ")";
		}
		
		return $conv;
	}
	
	private function getTimeZone( $type ) { 
		$conv = 0;
		if($type == 'est') {
			$conv = $this->global['adconf']['timezones']['est'];
		} else if($type == 'dst') {
			$conv = $this->global['adconf']['timezones']['utc'];
		} else {
			$conv = $this->global['adconf']['timezones']['def'];
		}
		
		return $conv;
	}


	private function dateTimeConv( $datetime, $type ) {
		$conv = "";
		if($type == 'est') {
			$conv .= date('Y-m-d H:i:s', strtotime($datetime) - 12 * 3600);
		} else if($type == 'dst') {
			$conv .= date('Y-m-d H:i:s', strtotime($datetime) - 13 * 3600);
		} else {
			$conv .= date('Y-m-d H:i:s');
		}
		
		return $conv;
	}	
	
	
	private function computeTotalPauses( $start, $end ) {		
		$from = strtotime($start);
		$to = strtotime($end);

		$diff = $to - $from;
		$this->total_pauses += $diff;
	}
	
	private function getTotalPauses() {
		$return = gmdate("H:i:s", $this->total_pauses);
		$this->total_pauses = 0;
		return $return;
	}
}