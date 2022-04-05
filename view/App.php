<?php 

use REJ\Libs\User;

class App {
	
	/* -- set public pages that accessible for all users permission. -- */
	private $public_pages = array("profile");

	/* -- set public pages for client permission. -- */
	private $client_pages = array("products","cart","checkout","register");

	/* -- global var handler -- */
	public $global = array();

	public $arr_controller = array();

	public $getret = array();

	public $request = array();

	public function __construct( $global ) {
		/* -- get global var config. -- */
		$this->global = $global;
		$this->init($this->global['init']);
		$this->pluginsManager($this->global['custom_libs']);
		/* -- pass url path -- */
		$this->getPage((isset($_GET['url'])) ? $_GET['url'] : '');
	}
	
	public function getPage($url) {
		$user = new User( $this->global );
		if($this->global['site']['maintenance'] === 0 || in_array($_SERVER['REMOTE_ADDR'], $this->global['site']['admin_ip'])) {
			//if(in_array($_SERVER['REMOTE_ADDR'], $this->global['site']['allow_ip'])) {
				$get_last_slash = substr($url, -1);
				$permission = 0;

				switch($_SERVER['REQUEST_METHOD']) {
					case 'GET': $this->request = $_GET; break;
					case 'POST': $this->request = $_POST; break;
					default:
				}


				if($get_last_slash !== '/') {
					$_url = explode("/", $url);

					$user->isLogin( $_url[0] );
					$this->global['site']['page'] = $_url[0];
					//die($_url[0]);

					if(count($_url) <= 1) {
						if(!empty($_url[0])) {
							if(array_key_exists($_url[0], $this->global['routes'])) {
								$path = '../' . $this->global['files_path']['view'] . '/' . $this->global['routes'][$_url[0]]['path'] . '/index.php';

								$permission = $user->userPageAccess( $_url[0] );

								if($permission === 1) {
									include_once '../' . $this->global['files_path']['controller'] . '/' . $_url[0] . '/' . ucfirst($_url[0]) . 'Controller.php';
									$path_controller = ucfirst($_url[0]) . 'Controller';

									$controller = new $path_controller($this->global, $this->request);
									$controller->setView($path);

									$method_arr = get_class_methods($controller);

									$func_exist = 0;
									foreach($method_arr as $method) {
										if($method != '__construct') {
											if($method == 'indexAction') {
												$func_exist = 1;
											}
										}
									}

									if($func_exist == 1) {
										$controller->indexAction();
									} else {
										$this->error404($this->global);
									}
								} else {
									if(in_array($_url[0], $this->client_pages)) {
										include_once '../' . $this->global['files_path']['controller'] . '/' . $_url[0] . '/' . ucfirst($_url[0]) . 'Controller.php';
										$path_controller = ucfirst($_url[0]) . 'Controller';

										$controller = new $path_controller($this->global, $this->request);
										$controller->setView($path);

										$method_arr = get_class_methods($controller);

										$func_exist = 0;
										foreach($method_arr as $method) {
											if($method != '__construct') {
												if($method == 'indexAction') {
													$func_exist = 1;
												}
											}
										}

										if($func_exist == 1) {
											$controller->indexAction();
										} else {
											$this->error404($this->global);
										}
									} else {
										$this->error404($this->global);
									}
								}	

							} else {
								$this->error404($this->global);
							}

						} else {

							$path = '../' . $this->global['files_path']['view'] . '/' . $this->global['routes']['client']['path'] . '/index.php';

							include_once '../' . $this->global['files_path']['controller'] . '/' . $this->global['routes']['client']['path'] . '/' . ucfirst($this->global['routes']['client']['path']) . 'Controller.php';
							$path_controller = ucfirst($this->global['routes']['client']['path']) . 'Controller';

							$controller = new $path_controller($this->global, $this->request);
							$controller->setView($path);

							$method_arr = get_class_methods($controller);

							$func_exist = 0;
							foreach($method_arr as $method) {
								if($method != '__construct') {
									if($method == 'indexAction') {
										$func_exist = 1;
									}
								}
							}

							if($func_exist == 1) {
								$controller->indexAction();
							} else {
								$this->error404($this->global);
							}

						}
					} else { // has child controller
						if($_url[0] === 'index') {
							if(array_key_exists('default', $this->global['routes'])) {

								if(array_key_exists(1, $_url)) {
									$path = '../' . $this->global['files_path']['view'] . '/' . $this->global['routes']['default']['path'] . '/' . $_url[1] . '.php';

									//$permission = $user->userPageAccess( $_url[0] );
									$permission = 1;

									if($permission === 1) {
										include_once '../' . $this->global['files_path']['controller'] . '/' . $_url[0] . '/' . ucfirst($_url[0]) . 'Controller.php';
										$path_controller = ucfirst($_url[0]) . 'Controller';
										$controller = new $path_controller($this->global, $this->request);
										$controller->setView($path);

										$method_arr = get_class_methods($controller);

										$func_exist = 0;
										foreach($method_arr as $method) {
											if($method != '__construct') {
												if($method == $_url[1] . 'Action') {
													$func_exist = 1;
												}
											}
										}

										if($func_exist == 1) {
											$controller->{$_url[1] . 'Action'}();
										} else {
											$this->error404($this->global);
										}

									} else {
										$this->error404($this->global);
									}	
								} else {
									$path = '../' . $this->global['files_path']['view'] . '/' . $this->global['routes'][$_url[0]]['path'] . '/index.php';

									$permission = $user->userPageAccess( $_url[0] );

									if($permission === 1) {
										include_once '../' . $this->global['files_path']['controller'] . '/' . $_url[0] . '/' . ucfirst($_url[0]) . 'Controller.php';
										$path_controller = ucfirst($_url[0]) . 'Controller';
										$controller = new $path_controller($this->global, $this->request);
										$controller->setView($path);

										$method_arr = get_class_methods($controller);

										$func_exist = 0;
										foreach($method_arr as $method) {
											if($method != '__construct') {
												if($method == $_url[1] . 'Action') {
													$func_exist = 1;
												}
											}
										}

										if($func_exist == 1) {
											$controller->{$_url[1] . 'Action'}();
										} else {
											$this->error404($this->global);
										}

									} else {
										$this->error404($this->global);
									}	
								}
							} else {
								$this->error404($this->global);
							}
						} else {
							if(array_key_exists($_url[0], $this->global['routes'])) {
								if(array_key_exists(1, $_url)) {
                                    
									$path = '../' . $this->global['files_path']['view'] . '/' . $this->global['routes'][$_url[0]]['path'] . '/' . $_url[1] . '.php';
                                    
									$permission = $user->userPageAccess( $_url[0] );

									if($permission === 1) {
										include_once '../' . $this->global['files_path']['controller'] . '/' . $_url[0] . '/' . ucfirst($_url[0]) . 'Controller.php';
										$path_controller = ucfirst($_url[0]) . 'Controller';
										$controller = new $path_controller($this->global, $this->request);
										$controller->setView($path);

										$method_arr = get_class_methods($controller);

										$func_exist = 0;
										foreach($method_arr as $method) {
											if($method != '__construct') {
												if($method == $_url[1] . 'Action') {
													$func_exist = 1;
												}
											}
										}

										if($func_exist == 1) {
											$controller->{$_url[1] . 'Action'}();
										} else {
											$this->error404($this->global);
										}

									} else {
										if(in_array($_url[0], $this->client_pages)) {

											include_once '../' . $this->global['files_path']['controller'] . '/' . $_url[0] . '/' . ucfirst($_url[0]) . 'Controller.php';
											$path_controller = ucfirst($_url[0]) . 'Controller';
											$controller = new $path_controller($this->global, $this->request);
											$controller->setView($path);
										
											$method_arr = get_class_methods($controller);
										
											$func_exist = 0;
											foreach($method_arr as $method) {
												if($method != '__construct') {
													if($method == $_url[1] . 'Action') {
														$func_exist = 1;
													}
												}
											}
										
											if($func_exist == 1) {
												$controller->{$_url[1] . 'Action'}();
											} else {
												$this->error404($this->global);
											}
										} else {
											$this->error404($this->global);
										}
										//$this->error404($this->global);
									}	
								} else {
									$path = '../' . $this->global['files_path']['view'] . '/' . $this->global['routes'][$_url[0]]['path'] . '/index.php';

									$permission = $user->userPageAccess( $_url[0] );

									if($permission === 1) {
										include_once '../' . $this->global['files_path']['controller'] . '/' . $_url[0] . '/' . ucfirst($_url[0]) . 'Controller.php';
										$path_controller = ucfirst($_url[0]) . 'Controller';
										$controller = new $path_controller($this->global, $this->request);
										$controller->setView($path);

										$method_arr = get_class_methods($controller);

										$func_exist = 0;
										foreach($method_arr as $method) {
											if($method != '__construct') {
												if($method == $_url[1] . 'Action') {
													$func_exist = 1;
												}
											}
										}

										if($func_exist == 1) {
											$controller->{$_url[1] . 'Action'}();
										} else {
											$this->error404($this->global);
										}

									} else {
										$this->error404($this->global);
									}	
								}
							} else {
								$this->error404($this->global);
							}
						}
					}
				} else {
					$this->error404($this->global);
				}
			/*} else {
				$this->error404($this->global);
			}*/
		} else {
			$this->errorMaintenance($this->global);
		}
		
	}


	public function error404( $global ) {
		include_once '../' . $global['files_path']['controller'] . '/' . $global['routes']['default']['path'] . '/' . ucfirst($global['routes']['default']['path']) . 'Controller.php';
		
		$path = '../' . $global['files_path']['view'] . '/error.php';
		$path_controller = ucfirst($global['routes']['default']['path']) . 'Controller';

		$controller = new $path_controller($global, '');
		$controller->setView($path);
		$controller->error404();
	}

	public function errorMaintenance( $global ) {
		include_once '../' . $global['files_path']['controller'] . '/' . $global['routes']['default']['path'] . '/' . ucfirst($global['routes']['default']['path']) . 'Controller.php';
		
		$path = '../' . $global['files_path']['view'] . '/maintenance.php';
		$path_controller = ucfirst($global['routes']['default']['path']) . 'Controller';

		$controller = new $path_controller($global, '');
		$controller->setView($path);
		$controller->errorMaintenance();
	}

	private function init( $conf ) {

		date_default_timezone_set($conf['default_timezone']);

		ini_set('upload_max_filesize', $conf['upload_max_filesize']);

		ini_set('post_max_size', $conf['post_max_size']);

		ini_set('max_input_time', $conf['max_input_time']);

		//ini_set('max_execution_time', $conf['max_execution_time']);
		
		ini_set('memory_limit', $conf['memory_limit']);

	}



	private function pluginsManager( $custom_libs ) {
		foreach( $custom_libs as $path ) {
			require '../' . $path . '.php';
		}
	}


}