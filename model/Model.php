<?php 

class Model extends Database {
	
	public $host = '';
	public $db = '';
	public $user = '';
	public $pass = '';
	
	public function __construct( $conn ) {
		$this->host = $conn['host'];
		$this->db = $conn['database'];
		$this->user = $conn['username'];
		$this->pass = $conn['password'];
		
		parent::openCon();
	}
	
}