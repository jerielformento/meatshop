<?php

use REJ\Libs\User;
use REJ\System\Session;

class DashboardController extends REJController {

	public function indexAction() {
		return $this->view(array());
	}

    public function addAction() {
        return $this->view(array());
    }

}