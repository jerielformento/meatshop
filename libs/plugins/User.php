<?php 

namespace REJ\Libs;

use REJ\System\Session;

class User { 
	
	private $sess;
	private $error;
	private $global;

	public function __construct( $glob_var ) {
		$this->global = $glob_var;
		$this->sess = $glob_var['session'];
		$this->error = $glob_var['error_file_path'];
	}

	
	public function isLogin( $url ) {
		$session = new Session();

		if(!$session->set($this->sess['prefix'] . $this->sess['var']['user_id']) && empty($url)) {
			/*if(!empty($url) && $url === 'administrator') {
				$this->redirect($this->global['routes']['administrator']['path']);
			} else {
				$this->redirect($this->global['routes']['login']['path']);
			}*/
			//$this->redirect($this->global['routes']['default']['path']);
			//$this->redirect('/');
		} else if($session->set($this->sess['prefix'] . $this->sess['var']['user_id']) && $url === $this->global['routes']['login']['path']) {
			//die($session->get($this->sess['prefix'] . $this->sess['var']['priv_id']));
			if($session->get($this->sess['prefix'] . $this->sess['var']['priv_id']) == 1) {
				$this->redirect('/dashboard');
			} else {
				$this->redirect('/');
			}
		}
	}

	
	public function isLogout() {
		$session = new Session();
	
		foreach($this->sess['var'] as $sessvar) {
			$session->un_set($this->sess['prefix'] . $sessvar);
		}

		$session->destroy();
		$this->redirect('/administrator');
	}


	public function setUserSession( $sess_arr ) {
		$session = new Session();
		
		foreach($sess_arr as $sess_key => $sess_val) {
			$session->create($this->sess['prefix'] . $sess_key, $sess_val);
		}
		
		$session->generateId();
	}
	

	public function userPageAccess( $url ) {
		$session = new Session();
		$permission = 0;

		if($session->set($this->sess['prefix'] . $this->sess['var']['user_id'])) {
			if($url === $this->global['routes']['logout']['path']) {
				$permission = 1;
			} else {
				foreach($session->get($this->sess['prefix'] . $this->sess['var']['pages_access']) as $menu)  {
					$permission += ($menu[1] === $url) ? 1 : 0;
				}

				if($permission === 0) {
					foreach($this->global['public_routes'] as $ppg) {
						if($url === $ppg['path']) {
							$permission += 1;
						}
					}
				}
			}
		} else if(!$session->set($this->sess['prefix'] . $this->sess['var']['user_id']) && (empty($url) || $url === $this->global['routes']['administrator']['path'])) {
			$permission = 1;
		} else if($session->set($this->sess['prefix'] . $this->sess['var']['user_id'])) {
			foreach($this->global['public_routes'] as $ppg) {
				$permission += ($url === $ppg['path']) ? 1 : 0;
			}
		} else {
			foreach($this->global['api_routes'] as $ppg) {
				$permission += ($url === $ppg['path']) ? 1 : 0;
			}
		}

		return $permission;
	}
	
	private function writeLogs( $user, $timestamp ) {
		$file = 'login_logs.txt';
		$write = $user . " successfully logged in " . $timestamp;
		$get_all = "";

		if(file_exists($file)) {
			$get_all = file_get_contents($file) . "\n" . $write;
		}

		file_put_contents($file, $get_all);
	}


	public function redirect( $page ) {
		header("Location: " . $page);
	}


	public function error404( $global ) {
		include '../' . $global['files_path']['controller'] . '/' . $global['routes']['default']['path'] . '/' . ucfirst($global['routes']['default']['path']) . 'Controller.php';
		
		$path = '../' . $global['files_path']['view'] . '/error.php';
		$path_controller = ucfirst($global['routes']['default']['path']) . 'Controller';

		$controller = new $path_controller($global, '');
		$controller->setView($path);
		$controller->error404();
	}
	
	public function errorMaintenance( $global ) {
		include '../' . $global['files_path']['controller'] . '/' . $global['routes']['default']['path'] . '/' . ucfirst($global['routes']['default']['path']) . 'Controller.php';
		
		$path = '../' . $global['files_path']['view'] . '/maintenance.php';
		$path_controller = ucfirst($global['routes']['default']['path']) . 'Controller';

		$controller = new $path_controller($global, '');
		$controller->setView($path);
		$controller->errorMaintenance();
	}
	
	public function genActivationCode($length = 11) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public function genSecurityCode($length = 4) {
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public function genSaltChars($length = 3) {
		$characters = '708a91b2c3d4e5f6';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
}