<?php

use REJ\Libs\User;
use REJ\System\Session;

class ClientController extends REJController {

	public function indexAction() {
		return $this->view(array(),'','client');
	}

}