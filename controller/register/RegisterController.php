<?php

use REJ\Libs\User;
use REJ\System\Session;

class RegisterController extends REJController {

	public function indexAction() {
		return $this->view(array(),'','client');
	}

}