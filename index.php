<?php

use REJ\Libs\User; // User Class
use REJ\Libs\LDAP; // LDAP Connection Class
use REJ\System\Session; // Session Class

class LoginController extends REJController {
	
	public function indexAction() {
		return $this->view(array()); // render a view from view/login/index.php
	}
    
	public function childAction() {
        $conn = $this->dbOpen(); // open a database connection

		$rows = $conn->execQuery(
            "SELECT * FROM table_name",
            array(),
            "rows"
        );

		$this->view($rows, 'json');
		
	}

}