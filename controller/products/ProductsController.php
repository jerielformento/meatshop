<?php

use REJ\Libs\User;
use REJ\System\Session;

class ProductsController extends REJController {

	public function indexAction() {
		return $this->view(array(),'','client');
	}

	public function viewAction() {
		return $this->view(array(),'','client');
	}

}