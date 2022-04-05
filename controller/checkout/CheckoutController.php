<?php

use REJ\Libs\User;
use REJ\System\Session;

class CheckoutController extends REJController {

	public function indexAction() {
        //$this->checkUserRegistration();

		return $this->view(array(),'','client');
	}

}