<?php 

/* ---------------------------------------------------*
*				-- Main Controller Class --		      |
*-----------------------------------------------------|
*													  |
*													  |
*													  |
*													  |
*													  |
*													  |
*													  |
*													  |
*													  |
* ----------------------------------------------------*/

use REJ\Libs\User;
use REJ\Libs\Validate;
use REJ\System\Session;

class REJController {

    /* global variables declaration */
	public $getret = array();

	public $global_var = array();

	public $get_req = array();

	public $sys_message = "";

	private $site_url = "";

	private $site_title = "";

	public $site_page = "";

	private $view_path = "";

	private $error_path = "";

	private $maintenance_path = "";

    protected $table_prefix = "";
    
    /* set global variables */
	public function __construct( $app, $req ) { 
		$this->global_var = $app;
        $this->table_prefix = $this->global_var['connection']['prefix'];
		$this->get_req = $req;
		$this->error_path = $app['files_path']['error'];
		$this->maintenance_path = $app['files_path']['maintenance'];
		$this->site_url = $app['site']['url'];
		$this->site_id = $app['site']['id'];
		$this->site_desc = $app['site']['desc'];
		$this->site_title = $app['site']['title'];
		$this->site_page = $app['site']['page'];
	} 

    /* set the page view */
	public function setView( $view ) {
		$this->view_path = $view;
	}
    
    /* get the page view or return the result */
	protected function view( $arr = array(), $type = null, $template = 'default', $html = null ) {
		if($type == 'json') {
			$this->getret = json_encode($arr);
			echo json_encode($arr);
		} else if($type == 'html') {
			echo $html;
		} else {
			$this->getret = $arr;

			$this->getHeader($template);
			require (file_exists($this->view_path)) ? $this->view_path : '../' . $this->error_path[0] . '/' . $this->error_path[1];
			$this->getFooter($template); 
		}
	}
    
    /* include page header */
	private function getHeader( $template ) {
		require '../' . $this->global_var['files_path']['system'] . '/template/' . $this->global_var['template'][$template]['header'] . '.php';
	}

    
    /* check the request variable */
	public function setreq( $request ) {
		return (isset($this->get_req[$request])) ? true : false;
	}

    /* post request variable */
	protected function post( $name ) {
        $vd = new Validate();
        $val = trim($this->get_req[$name]);
		return $val;
	}
	
    /* unset request variable */
	protected function unset_req( $name ) {
		unset($this->get_req[$name]);
	}

    /* get request variable */
	protected function get( $name ) {
		return $this->get_req[$name];	
	}
    
    /* get body request variable */
    protected function getBody( $model ) {
        $validate = new Validate();
        $request = [];
        $errors = [];
        
        foreach($this->get_req as $key => $value) {
            $result = $validate->sanitize($value, $model[$key]);
            $request[$key] = $result[0];
            
            if($result[1][0] == 1) {
                $errors[$key] = $result[1][1];
            }
        }
        
        return array('data'=>$request, 'errors'=>$errors);
    }

	protected function fileset( $files ) {
		if(isset($_FILES)) {
			return true;
		} else {
			return false;
		}
	}
    
    /* get form header error */
    public function getHeaderError() {
        if(!empty($this->getret['errors']['header'])) {
            if($this->getret['errors']['header'][0] == "success") {
                echo '<div class="alert alert-' . $this->getret['errors']['header'][0] . '" role="alert"><i class="fa fa-fw fa-check"></i> ' . $this->getret['errors']['header'][1] . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button></div>';    
            } else if($this->getret['errors']['header'][0] == "danger") {
                echo '<div class="alert alert-' . $this->getret['errors']['header'][0] . '" role="alert"><i class="fa fa-fw fa-times"></i> ' . $this->getret['errors']['header'][1] . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button></div>';    
            }
        }    
    }
    
    public function saveData( $field ) {
        if(!empty($this->getret['data'][$field])) {
            return $this->getret['data'][$field];
        }    
    }

    /* check the currently active page */
	public function isActivePage( $url, $type = null ) {
		if($type === "echo") {
			echo ($url === $this->getSitePage()) ? 'class="n-active"' : '';
		} else {
			return ($url === $this->getSitePage()) ? 'class="n-active"' : '';
		}
	}

	/* include page footer */
	private function getFooter( $template ) {
		require '../' . $this->global_var['files_path']['system'] . '/template/' . $this->global_var['template'][$template]['footer'] . '.php';
	}

    /* get and fetch pages navigation */
	protected function getPageNavigation() {
		$session = new Session();
        
		if($session->set($this->global_var['session']['prefix'] . $this->global_var['session']['var']['user_id'])) {
			foreach($session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['pages_access']) as $crt_mnu) {
				echo "<li " . $this->isActivePage($crt_mnu[1]) . "><a href=\"" . $this->global_var['site']['url'] . "/" . $crt_mnu[1] . "\"><span class=\"" . $crt_mnu[3] . "\"></span> &nbsp;" . $crt_mnu[2] . "</a></li>";
			}	
		}
	}

    /* display the current page header */
	protected function getCurrentPageHeader() {
		$session = new Session();
		if($session->set($this->global_var['session']['prefix'] . $this->global_var['session']['var']['user_id'])) {
			foreach($session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['pages_access']) as $crt_mnu) {
				if($crt_mnu[1] === $this->getSitePage()) { 
                    $url = $_GET['url'];
                    $_url = explode("/", $url);
                    echo '<div class="d-sm-flex align-items-center justify-content-between mb-3">';
                    echo '<p class="mb-0 text-dark" id="curr-page" pid="' . $crt_mnu[1] . '"><i class="' . $crt_mnu[3] . '"></i> &nbsp;<span class="text-dark">' . $crt_mnu[2] . '</span>' . ((!empty($_url[1])) ? "<span class='text-gray-600'> / " . ucfirst($_url[1]) ."</span>" : "") . '</p>';
                    echo '</div>';
				}
			}	
		}
	}

	public function getBreadcrumbs() {
		$url = $_GET['url'];
		$_url = explode("/", $url);
		return ucfirst($_url[0]);
	}

	public function getSiteUrl() {
		echo $this->site_url;
	}
    
    public function getUrl() {
		return $this->site_url;
	}

	public function getSiteName() {
		return $this->site_desc;
	}

	public function getSiteTitle() {
		echo $this->site_title;
	}

	public function getSitePage() {
		return $this->site_page;
	}
	
	public function getActivePage() {
		echo str_replace("/","",$_SERVER['REQUEST_URI']);
	}

	public function getRoute( $ctrl ) {
		echo (array_key_exists($ctrl, $this->global_var['routes'])) ? '/' . $ctrl : '/';
	}


	public function toRoute( $page ) {
		header("Location: " . $this->global_var['routes'][$page]['path']);
	}


	public function error404() {
		require (file_exists($this->view_path)) ? $this->view_path : '../' . $this->error_path[0] . '/' . $this->error_path[1];
	}
	
	public function errorMaintenance() {
		require (file_exists($this->view_path)) ? $this->view_path : '../' . $this->maintenance_path[0] . '/' . $this->maintenance_path[1];
	}

	public function getSessionClass( $type ) {
		$session = new Session();
		if($type == "body") {
			echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['body_class']);
		} else {
			echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['nav_class']);
		}
	}

	public function dbOpen() {
		$conn = new Model($this->global_var['connection']);
		return $conn;
	}
	
	public function getSessPrefix() {
        return $this->global_var['session']['prefix'];
    }

	public function isLDAPUser( $username, $password ) {
		$ldap = new LDAP();
		return $ldap->bindUserToLDAP( $username, $password, $this->global_var['ldap'] );
	}


	public function setMessageAlert( $text, $type, $store_sess = false ) {
		$this->sys_message = "";
		$this->sys_message .= '<div class="alert alert-' . $type . ' alert-dismissible" role="alert">';
		$this->sys_message .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
		$this->sys_message .= ($type === "success") ? "<span class=\"fa fa-check-circle fa-lg\"></span>&nbsp;" : "<span class=\"fa fa-exclamation-circle fa-lg\"></span>&nbsp;";
		$this->sys_message .= " " . $text . "</div>";
		
		if($store_sess) {
			$session = new Session();
			$session->create('msg_alert', $this->sys_message);
		}
	}


	public function getMessageAlert( $is_sess = false ) {
		
		if(!$is_sess) {
			echo $this->sys_message;
		} else {
			$session = new Session();
			echo ($session->set('msg_alert')) ? $session->get('msg_alert') : '';
			$session->un_set('msg_alert');
		}
		
		$this->sys_message = "";
	}


	protected function printR( $message = array() ) {
		echo "<pre>";
		print_r($message);
		echo "</pre>";
	}

	/* User session */
	public function getLoginUsername() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['username']);
	}
	
	public function getReferralLink() {
		$session = new Session();
		echo $this->site_url . 'register?urc=' . $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['ref_link']);
	}
	
	public function getActivateId() {
		$session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['ref_link']);
	}
	
	public function getFullName() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['full_name']);
	}
	
	public function getEmailAddress() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['email_addr']);
	}
	
	public function getBreakStatus() {
		$session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['break_status']);
	}
    
    public function getGender() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['gender']);
	}
    
    public function getDepartment() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['department']);
	}
    
    public function getPosition() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['position']);
	}
    
    public function getCivilStatus() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['civil_status']);
	}
    
    public function getBirthDate() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['birthday']);
	}
	
	public function getLoginEmail() {
		$session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['email_addr']); 
	}
	
	public function getLoginFirstname() {
		$session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['firstname']); 
	}
    
    public function getLoginId() {
        $session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['user_id']);
    }
	
	public function getLoginUname() {
        $session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['username']);
    }
	
	public function getGPointsBal() {
        $session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['gpoints']);
    }
	
	public function getMobileNo() {
        $session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['mobile']);
    }
	
	public function getUsername() {
        $session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['username']);
    }
	
	public function getTeamId() {
		$session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['team_id']);
	}
	
    public function getTeamName() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['team_name']);
	}
    
	public function getCampId() {
		$session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['camp_id']);
	}
    
    public function getCampName() {
		$session = new Session();
		echo $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['camp_name']);
	}
	
	public function getPageRecordAccess() {
		$session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['rec_acs']);
	}
	
	public function getUserPrivilege() {
		$session = new Session();
		return $session->get($this->global_var['session']['prefix'] . $this->global_var['session']['var']['priv_id']);
	}
	
	public function setRegisterError( $text, $type ) {
		$session = new Session();
		$this->sys_message = "";
		$this->sys_message .= '<div class="alert alert-' . $type . ' alert-dismissible" role="alert">';
		$this->sys_message .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
		$this->sys_message .= ($type === "success") ? "<span class=\"glyphicon glyphicon-ok-sign\"></span>&nbsp;" : "<span class=\"glyphicon glyphicon-exclamation-sign\"></span>&nbsp;";
		$this->sys_message .= " " . $text . "</div>";
		$session->create('_error',$this->sys_message);
	}
	
	public function setActivityLog( $db, $uup, $uupd, $action ) {
		return $db->execQuery("INSERT INTO " . $this->getTablePrefix() . "users_activity 
			( 
				user_updater, 
				user_updated, 
				action, 
				date_added, 
				ip 
			)
			VALUES
			(
				:uup,
				:uupd,
				:action,
				:date,
				:ip
			)",array(
				':uup' => $uup,
				':uupd' => $uupd,
				':action' => $action,
				':date' => date("Y-m-d H:i:s"),
				':ip' => $_SERVER['REMOTE_ADDR']
		),"insert");
	}
	
	public function getRegisterError() {
		$session = new Session();
		if($session->set('_error')) {
			echo $session->get('_error');
			$session->un_set('_error');
		}
	}
	
	protected function getClientIp() {
		return $_SERVER['REMOTE_ADDR'];
	}
	
	protected function requestMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	public function getActivities( $module, $action ) {
		return $this->global_var['activities'][$parent][$action];
	}

	public function checkUserRegistration() {
        $session = new Session();

		if(!$session->set($this->global_var['session']['prefix'] . $this->global_var['session']['var']['user_id'])) {
			header('Location: /register');
		}
    }

	public function isLoginCustomer() {
        $session = new Session();

		if(!$session->set($this->global_var['session']['prefix'] . $this->global_var['session']['var']['user_id'])) {
			return false;
		} else {
			return true;
		}
    }
}