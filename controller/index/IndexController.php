<?php

use REJ\Libs\User;
use REJ\System\Session;

class IndexController extends REJController {

	public function indexAction() {
		return $this->view(array());
	}
    
    public function getUsersNavAction() {
        $session = new Session();
        
		if($session->set($this->global_var['session']['prefix'] . $this->global_var['session']['var']['user_id'])) {
            $sidebar = array();

			foreach($session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['pages_access']) as $crt_mnu) {
				$tmp = array();
				if($this->getSitePage() === $crt_mnu[1]) {
					$tmp['url'] = $this->getUrl() . $crt_mnu[1];
					$tmp['name'] = $crt_mnu[2];
					$tmp['class'] = $crt_mnu[3] . ' fa-lg';
					$tmp['active'] = 1;
					$tmp['site'] = $crt_mnu[1];
					$tmp['wew'] = $this->getSitePage();
				} else {
					$tmp['url'] = $this->getUrl() . $crt_mnu[1];
					$tmp['name'] = $crt_mnu[2];
					$tmp['class'] = $crt_mnu[3] . ' fa-lg';
					$tmp['active'] = 0;
					$tmp['site'] = $crt_mnu[1];
					$tmp['wew'] = $this->getSitePage();
				}

				$sidebar[] = $tmp;
			}

            $return = array(
                'sidebar'=>$sidebar,
                'sbstatus'=>$session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['sb_status'])
            );

            $this->view($return, 'json');
        }
    }
	
	public function phpInfoAction() {
		echo phpinfo();
	}
	
	public function sidebarAction() {
		$session = new Session();
		$session->create($this->global_var['session']['prefix'] . $this->global_var['session']['var']['sb_status'], $this->get('action'));

		if($this->get('action') == 1) {
			$session->create($this->global_var['session']['prefix'] . $this->global_var['session']['var']['body_class'], 'col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 main');
			$session->create($this->global_var['session']['prefix'] . $this->global_var['session']['var']['nav_class'], 'sidebar cust-side');
		} else {
			$session->create($this->global_var['session']['prefix'] . $this->global_var['session']['var']['body_class'], 'col-sm-12 col-sm-offset-0 col-md-12 col-md-offset-0 main align-body');
			$session->create($this->global_var['session']['prefix'] . $this->global_var['session']['var']['nav_class'], 'sidebar cust-side align-nav');
		}
	}
    
    public function passAction() {
        $options = [
            'cost' => 8,
        ];
        
        echo password_hash("jerielf39", PASSWORD_BCRYPT, $options);
    }
    
    public function phpVersionAction() {
        echo phpinfo();
    }
}