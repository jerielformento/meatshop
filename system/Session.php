<?php

namespace REJ\System;

class Session {


	public function __construct() {
		if(session_id() == '') {
			session_start();
		}
	}


	public function create( $name, $val, $is_arr = false ) {
		if($is_arr) {
			$_SESSION[$name][] = $val;
		} else {
			$_SESSION[$name] = $val;
		}
	}


	public function un_set( $session_name ) {
		unset($_SESSION[$session_name]);
	}


	public function get( $session_name ) {
		return $_SESSION[$session_name];
	}


	public function set( $session_name ) {
		return (isset($_SESSION[$session_name])) ? true : false;
	}


	public function generateId() {
		session_regenerate_id(true);
	}


	public function destroy() {
		session_destroy();
	}

}