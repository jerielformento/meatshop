<?php

use REJ\Libs\User;
use REJ\Libs\LDAP;
use REJ\System\Session;

class AdministratorController extends REJController {
	
	public function indexAction() {
		return $this->view(array(),'','login');
	}

	public function userLoginAction() {
		$conn = new UsersModel( $this->global_var['connection'] );
		$sess = new Session();
		$user = new User( $this->global_var );
		
		$username = $this->post('u-user');
		$password = $this->post('u-pass');
		$ip = $_SERVER['REMOTE_ADDR'];
		$datetime = date("Y-m-d H:i:s");
		
		if(!empty($username) && !empty($password)) {
            $options = [
                'cost' => 8,
            ];
        
            $get_user = $conn->checkExist($username);
            
            if(count($get_user) > 0) {
                if(password_verify($password, $get_user[0]['password'])) {
                    $sess->generateId();

                    foreach($get_user as $user_info) {
                        $count = 0;
                        $pages_arr = array();

                        $pages_access = $conn->getPagesAccess($user_info['priv_id']);

                        
                        $count = 0;
                        $pages_arr = array();
                        foreach($pages_access as $pg) {
                            $pages_arr[$count] = array($pg['page_id'], $pg['url'], $pg['name'], $pg['icon']);
                            $count++;
                        }

                        $user->setUserSession(array(
                            'uniqid' => $user_info['user_id'],
                            'usrn' => $user_info['username'],
                            'privid' => $user_info['privilege'],
                            'pgs_acs' => $pages_arr,
                            'sb_nav_class' => 'sidebar cust-side',
                            'sb_body_class' => 'col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 main',
                            'sidebar_status' => 1,
                            'bstatus' => 0,
                            'ipaddr' => $_SERVER['REMOTE_ADDR']
                        ));

                        $sess->un_set('msg');
                        $sess->un_set('user_attempt');
                        $sess->un_set($this->getSessPrefix() . '_usrattempt');

                        /* Login success redirect to home page */
                        $user->redirect("/dashboard");
                        break;
                    }
                } else {
                    $this->setMessageAlert("Login failed, please try again! 2",'danger', true);
                    $user->redirect('../login');
                }
            } else {
                $this->setMessageAlert("Login failed, user not found!",'danger', true);
                $user->redirect('../login');
            }
            
            
		} else { 
			$this->setMessageAlert("Login failed, please try again! 3",'danger', true);
			$user->redirect('../login');
		}
	}

}