<?php

use REJ\Libs\User;
use REJ\System\Session;

class AdminController extends REJController {

	public function indexAction() {
		return $this->view(array());
	}

}