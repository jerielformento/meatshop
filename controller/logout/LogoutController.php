<?php

use REJ\Libs\User;
use REJ\System\Session;

class LogoutController extends REJController {

	public function indexAction() {
		$session = new Session();
		$users = new User( $this->global_var );
		$db = $this->dbOpen();
		
		if($session->set($this->global_var['session']['prefix'] . $this->global_var['session']['var']['username'])) {
			$users->isLogout();
						
			$json = array(
				'success' => true,
				'message' => 'Thank you for using CDEP AES System!',
			);

			echo json_encode($json);
		}
	}

}