<?php

use REJ\Libs\User;
use REJ\System\Session;

class CartController extends REJController {

	public function indexAction() {
		$this->checkUserRegistration();

		return $this->view(array(),'','client');
	}

	public function packAction() {
		$this->checkUserRegistration();
		return $this->view(array(),'','client');
	}

	public function shipAction() {
		$this->checkUserRegistration();
		return $this->view(array(),'','client');
	}

	public function completeAction() {
		$this->checkUserRegistration();
		return $this->view(array(),'','client');
	}
}