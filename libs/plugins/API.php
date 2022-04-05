<?php 

namespace REJ\Libs;

class API {
	
	public function __construct() {
		
	} 
	 
	/* --
		API function for sending Getty messages
		postMessage - get parameters
		sendGettyMessages - send data get from postMessage and save into database
	*/
	public function postMessage( $parameters ) {
		if(count($parameters) === 3) {
			if(isset($parameters['gettyuser']) && isset($parameters['title']) && isset($parameters['message'])) {
				$rs = $this->sendGettyMessages( $parameters['gettyuser'], $parameters['title'], $parameters['message'] );
				$this->apiReturn($rs['message'],$rs['status'],'json');
			} else {
				$this->apiReturn('Invalid parameters request.','failed','json');
			}	
		} else {
			$this->apiReturn('Invalid parameters length.','failed','json');
		}
	}
	
	public function sendGettyMessages( $to, $body, $title ) {
		$db = new Model();
		$vd = new Validate();
		
		$to = $db->escapeString($to);
		$title = $db->escapeString($body);
		$body = $db->escapeString($title);
		$sendType = 'agent';
		$prio = 2;

        $selectUser = 14168;
		$dateToday = date('Y-m-d H:i:s');
		$attachment = 'message-' . $dateToday . '-' . $vd->randomInteger(20);

		$rs = $db->execQuery("INSERT INTO 
								tbl_messages 
								(
									message_id, 
									message_title, 
									message_content, 
									message_attachment, 
									message_datecreated, 
									message_timestamp, 
									user_id, 
									messages_sendto, 
									message_sendto, 
									message_priority, 
									messages_read, 
									this_site
								)  VALUES (0, '" . $title . "', '" . $body . "','" . $attachment . "', '" . $dateToday . "', '" . $dateToday . "', " . $selectUser . ", '', '', '" . $prio . "', '', '" . __SITE__ . "')","insert");
								// $title, $content, $attachment, $dateToday, $dateToday, $selectUser, $priority, $globalSiteNameForDB

		$return = array();

		if($rs) { 
			$last_id = $db->last_id;
			
			// recipient_id messages_id, user_username recipient_read
			$sql_agents = "VALUES";
			
			$addFilter = "";
			$is_error = 0;
			if($sendType == 'team') {
			
				$agt_rs = $db->execQuery("SELECT agent FROM tbl_agents WHERE team_id='" . $to . "' AND archived=" . __NOTARCHVD__,"rows");
				
				foreach($agt_rs as $agent) {
					$sql_agents .= (!empty($agent[0])) ? "(0, '" . $last_id . "','" . $agent[0] . "',0)," : "";
					$db->execQuery("UPDATE tbl_total_messages_count SET count=(count+1) WHERE username='" . $agent[0] . "'","update");
				}
				
				$is_error += $db->execQuery("INSERT INTO tbl_recipients (recipient_id, messages_id, user_username, recipient_read) ".substr($sql_agents,0,-1), "insert");
				
			} else if($sendType == "agent") {
			
				$exp_to = explode(",", $to);
				foreach($exp_to as $agent) {
					$trim_agt = str_replace(" ", "", $agent);
					$sql_agents .= (!empty($trim_agt)) ? "(0, '" . $last_id . "','" . $trim_agt . "',0)," : "";
					$db->execQuery("UPDATE tbl_total_messages_count SET count=(count+1) WHERE username='" . $trim_agt . "'","update");
				}
				
				$is_error += $db->execQuery("INSERT INTO tbl_recipients (recipient_id, messages_id, user_username, recipient_read) ".substr($sql_agents,0,-1), "insert");
				
			}
			
			if($is_error > 0) {
				$return = array(
								'message'=>'Message has been successfully sent.',
								'status'=>'success'
					   		);
			} else {
				//$return = array('message'=>array('danger','<strong>Error!</strong> Sending message failed.'));
				$return = array(
								'message'=>'Sending message failed.',
								'status'=>'failed'
					   		);
			}
		} else {
			//$return = array('message'=>array('danger','<strong>Error!</strong> Sending message failed.'));
			$return = array(
								'message'=>'There\'s an error with your connection!',
								'status'=>'failed'
					   		);
		}
		
		return $return;
	}
	
	
	/* --
		API function creating Getty schedules
	*/
	
	public function postSchedules( $parameters ) {
		if(count($parameters) === 14) {
			if(isset($parameters['gettyuser'])) {
				
			} else {
				
			}
		} else {
			
		}	
	}
			   
	public function updateSchedules( $agent, id ) {
		$agent_info = $_POST['arr'];

		$return = "";
		$err_count = 0;

		$agent_u = $agent_info[$agt];
		$id = $agent_info[$sched_id];
		$id_pre = $agent_info[$pr_id];
		$id_post = $agent_info[$po_id];
		$campaign_u = $agent_info[$cmp_id];
		$team_id_u = $agent_info[$tm_id];
		$timefrom_u = $agent_info[$tf_num];
		$timeto_u = $agent_info[$tt_num];
		$prehour_u = $agent_info[$pre_h];
		$premin_u = $agent_info[$pre_m];
		$posthour_u = $agent_info[$post_h];
		$postmin_u = $agent_info[$post_m];
		$date_assigned_u = $agent_info[$date_ass];
		$date_assigned_u_nxtdy = date("Y-m-d", strtotime($date_assigned_u . " + 1 day"));

		$date_utc_st = gmdate('Y-m-d H:i:s', strtotime($date_assigned_u . " " . $timefrom_u));
		$date_utc_en = ($timefrom_u > $timeto_u) ? gmdate('Y-m-d H:i:s', strtotime($date_assigned_u_nxtdy . " " . $timeto_u)) : gmdate('Y-m-d H:i:s', strtotime($date_assigned_u . " " . $timeto_u));

		$date_local_st = $date_assigned_u . " " . $timefrom_u;
		$date_local_en = ($timefrom_u > $timeto_u) ? $date_assigned_u_nxtdy . " " . $timeto_u : $date_local_en = $date_assigned_u . " " . $timeto_u;


		if($prehour_u > 0 && $prehour_u != "0") {
			$has_min = 0;
			if($premin_u > 0) {
				$has_min = 1;
			}

			$get_date_utc_st = date("Y-m-d H:i:s", strtotime("-" . $prehour_u . " hours " . $date_assigned_u . " " . $timefrom_u));
			$pre_date_utc_st = gmdate('Y-m-d H:i:s', strtotime((($has_min == 1) ? "-" . $premin_u . " minutes " : "") . $get_date_utc_st));
			$pre_date_utc_en = gmdate('Y-m-d H:i:s', strtotime($date_assigned_u . " " . $timefrom_u));
			$get_date_local_st = date("Y-m-d H:i:s", strtotime("-" . $prehour_u . " hours "	. $date_assigned_u . " " . $timefrom_u));
			$pre_date_local_st = date("Y-m-d H:i:s", strtotime((($has_min == 1) ? "-" . $premin_u . " minutes " : "") . $get_date_local_st));
			$pre_date_local_en = $date_assigned_u . " " . $timefrom_u;

			if(!empty($id_pre) && $id_pre != 0) {
				$db->execQuery("UPDATE 
									tbl_schedules_uni
								SET
									start_date_local='" . $pre_date_local_st . "',
									end_date_local='" . $pre_date_local_en . "',
									start_date_utc='" . $pre_date_utc_st . "',
									end_date_utc='" . $pre_date_utc_en . "'
								WHERE sched_id=" . $id_pre,"update");
			} else {
				$db->execQuery("INSERT INTO
									tbl_schedules_uni
								(
									site_id,
									campaign_id,
									team_id,
									username,
									start_date_local,
									start_date_utc,
									end_date_local,
									end_date_utc,
									sched_type,
									is_leave
								)
								VALUES
								(
									" . __SITEID__ . ",
									" . $campaign_u . ",
									" . $team_id_u . ",
									'" . $agent_u . "',
									'" . $pre_date_local_st . "',
									'" . $pre_date_utc_st . "',
									'" . $pre_date_local_en . "',
									'" . $pre_date_utc_en . "',
									" . __PRES__ . ",
									" . __NOTLEAVE__ . "
								)","insert");
			}
		}

		if($posthour_u > 0 && $posthour_u != "0") {
			$has_min = 0;
			if($postmin_u > 0) {
				$has_min = 1;
			}

			$get_date_utc_st = date("Y-m-d H:i:s", strtotime("+" . $posthour_u . " hour " . (($timefrom_u > $timeto_u) ? $date_assigned_u_nxtdy : $date_assigned_u) . " " . $timeto_u));
			$post_date_utc_st = gmdate('Y-m-d H:i:s', strtotime((($has_min == 1) ? "+" . $postmin_u . " minutes" : "") . $get_date_utc_st));
			$post_date_utc_en = gmdate('Y-m-d H:i:s', strtotime((($timefrom_u > $timeto_u) ? $date_assigned_u_nxtdy : $date_assigned_u) . " " . $timeto_u));
			$post_date_local_st = (($timefrom_u > $timeto_u) ? $date_assigned_u_nxtdy : $date_assigned_u) . " " . $timeto_u;
			$get_date_local_en = date("Y-m-d H:i:s", strtotime("+" . $posthour_u . " hour " . (($timefrom_u > $timeto_u) ? $date_assigned_u_nxtdy : $date_assigned_u) . " " . $timeto_u));
			$post_date_local_en = date("Y-m-d H:i:s", strtotime((($has_min == 1) ? "+" . $postmin_u . " minutes" : "") . $get_date_local_en));

			if(!empty($id_post) && $id_post != 0) {
				$db->execQuery("UPDATE 
									tbl_schedules_uni
								SET
									start_date_local='" . $post_date_local_st . "',
									end_date_local='" . $post_date_local_en . "',
									start_date_utc='" . $post_date_utc_en . "',
									end_date_utc='" . $post_date_utc_st . "'
								WHERE sched_id=" . $id_post,"update");
			} else {

				$db->execQuery("INSERT INTO
									tbl_schedules_uni
								(
									site_id,
									campaign_id,
									team_id,
									username,
									start_date_local,
									start_date_utc,
									end_date_local,
									end_date_utc,
									sched_type,
									is_leave
								)
								VALUES
								(
									" . __SITEID__ . ",
									" . $campaign_u . ",
									" . $team_id_u . ",
									'" . $agent_u . "',
									'" . $post_date_local_st . "',
									'" . $post_date_utc_en . "',
									'" . $post_date_local_en . "',
									'" . $post_date_utc_st . "',
									" . __POSTS__ . ",
									" . __NOTLEAVE__ . "
								)","insert");
			}

		}


		$upd_sched = $db->execQuery("UPDATE 
										tbl_schedules_uni
									SET
										start_date_local='" . $date_local_st . "',
										end_date_local='" . $date_local_en . "',
										start_date_utc='" . $date_utc_st . "',
										end_date_utc='" . $date_utc_en . "'
									WHERE sched_id=" . $id,"update");


		$err_count = ($upd_sched) ? 0 : 1;

		$return = ($err_count == 0) ? array('message'=>array('success','<strong>Success!</strong> Schedule has been updated.')) : array('message'=>array('danger','<strong>Error!</strong> Some of schedule are not updated due to error.'));
		echo json_encode($return);
	}
	
	public function apiReturn( $message, $status, $return_type ) {
		if(strtoupper($return_type) == 'JSON') {
			$error_arr = array(
				'message'=>$message,
				'status'=>$status
			);
			
			echo json_encode($error_arr);
		} else {
			echo $message;	
		}	
	}
}