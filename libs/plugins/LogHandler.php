<?php 

namespace REJ\Libs;

class LogHandler {
	
	
	/* global init variables */
	private $global;
	private $sess;
	private $error;
	
	
	/* logs handler variables */
	private $user_updater;
	private $user_updated;
	private $user_action;
	private $user_ip;
	
	
	public function __construct( $glob_var ) {
		$this->global = $glob_var;
		$this->sess = $glob_var['session'];
		$this->error = $glob_var['error_file_path'];
	}

	
	/* This function will log users activity for auditing */
	public function addUserActivityLog( $user_updater, $user_updated, $user_action, $user_ip, $db ) {
		$db = $db;
		
		$this->user_updater = $user_updater;
		$this->user_updated = $user_updated;
		$this->user_action = $user_action;
		$this->user_ip = $user_ip;
		
		/* This execution doesn't have a return value or response */
		$db->execQuery("INSERT INTO tbl_user_activity (user_updater, user_updated, action, date_added, ip)
						VALUES(:uupr,:uupd,:action,:date,:ip)",array(
							':uupr' => $this->user_updater,
							':uupd' => $this->user_updated,
							':action' => $this->user_action,
							':date' => date("Y-m-d H:i:s"),
							':ip' => $this->user_ip
						),"insert");
	}
	
}