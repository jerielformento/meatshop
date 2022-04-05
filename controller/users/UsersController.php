<?php

use REJ\Libs\User;
use REJ\System\Session;
use REJ\Model\UsersModel;
    
class UsersController extends REJController {

	public function indexAction() {
		return $this->view(array());
	}
    
    public function fetchUserListAction() {
        $db = $this->dbOpen();
        
        $key = ($this->setreq('key') && $this->post('key')) ? $this->post('key') : '';

		$addFilter = (!empty($key)) ? "WHERE u.username LIKE '%" . $key . "%'" : "";
        
		$lst = (int) $this->post('start');
		$len = 15;

		$cusers = $db->execQuery("SELECT user_id FROM " . $this->table_prefix . "users",array(),"count");

		$users = $db->execQuery("SELECT 
									MD5(u.user_id) user_id,
									u.username,
                                    u.first_name,
                                    u.middle_name,
                                    u.last_name,
                                    u.email_address,
									(SELECT title FROM " . $this->table_prefix . "user_privileges pv WHERE pv.priv_id=u.privilege LIMIT 1) privilege,
									u.date_created
								FROM " . $this->table_prefix . "users u " . $addFilter . "
                                ORDER BY u.date_created DESC LIMIT " . $lst . "," . $len. ";",array(),"rows");
        
		$pagination = array();
		$count_pagi = $cusers;
		$pagination['pages'] = ceil($count_pagi / 15);
		$pagination['count'] = $count_pagi;

		$this->view(array(
			'userlist' => $users,
			'limit' => $pagination
		),'json');
    }
    
    public function addAction() {
        $db = $this->dbOpen();
        
		$return = array();
        
        if($this->setreq('submit')) {
            $model = new UsersModel();
            $request = $this->getBody( $model->fields ); 

            if(count($request['errors']) == 0) {
                foreach($request['data'] as $key => $val) {
                    ${$key} = $val;
                }

                if($password === $confirm_password) {
                    $get_priv = $db->execQuery("SELECT priv_id, title FROM " . $this->table_prefix . "user_privileges WHERE MD5(priv_id)=:priv_id LIMIT 1",array(':priv_id'=>$privilege),"rows");

                    if(count($get_priv) > 0) {
                        $check_exist = $db->execQuery("SELECT user_id FROM " . $this->table_prefix . "users WHERE username=:user",array(':user' => $username),"rows");

                        if(count($check_exist) === 0) {
                            $options = [
                                'cost' => 8,
                            ];

                            $hash_pwd = password_hash($password, PASSWORD_BCRYPT, $options);

                            $insert = $db->execQuery("INSERT INTO " . $this->table_prefix . "users
                                                        (username, password, privilege, first_name, middle_name, last_name, email_address, date_created)
                                               VALUES
                                               (:uname,:pwd,:priv,:fname,:mname,:lname,:email,:date)", 
                                               array(
                                                   ':uname'=>$username,
                                                   ':pwd'=>$hash_pwd,
                                                   ':priv'=>$get_priv[0]['priv_id'],
                                                   ':fname'=>$first_name,
                                                   ':mname'=>$middle_name,
                                                   ':lname'=>$last_name,
                                                   ':email'=>$email_address,
                                                   ':date'=>date("Y-m-d H:i:s")
                                               ), "insert");

                            if($insert) {
                                $request['errors']['header'][0] = "success";
                                $request['errors']['header'][1] = "Data has been saved!";
                        
                                return $this->view(array('data'=>[], 'errors'=>$request['errors']));
                            } else {
                                $request['errors']['header'][0] = "danger";
                                $request['errors']['header'][1] = "Saving record has been failed!";
                        
                                return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                            }   
                        } else {
                            $request['errors']['username'] = "Username is already exist";
                        
                            return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                        }
                    } else {
                        $request['errors']['privilege'] = "Invalid option";
                        
                        return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                    }
                } else {
                    $request['errors']['password'] = "Password doesn't match";
                    $request['errors']['confirm_password'] = "Password doesn't match";
                    
                    return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                }
            }
            
            return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
        }
        
        return $this->view(array());
    }
    
    public function editAction() {
        $db = $this->dbOpen();
        
        if($this->setreq('submit')) {
            $model = new UsersModel();
            $request = $this->getBody( $model->fields );
            
            if(count($request['errors']) == 0) {
                $id = $this->post('id');
                foreach($request['data'] as $key => $val) {
                    ${$key} = $val;
                }
                
                if($password === $confirm_password) {
                    $get_priv = $db->execQuery("SELECT priv_id, title FROM " . $this->table_prefix . "user_privileges WHERE MD5(priv_id)=:priv_id LIMIT 1",array(':priv_id'=>$privilege),"rows");

                    if(count($get_priv) > 0) {
                        $check_exist = $db->execQuery("SELECT user_id FROM " . $this->table_prefix . "users WHERE MD5(user_id)=:id",array(':id' => $id),"rows");

                        if(count($check_exist) > 0) {
                            $check_dupli_uname = $db->execQuery("SELECT user_id FROM " . $this->table_prefix . "users WHERE username=:user AND MD5(user_id) <> :id",array(':user'=>$username,':id'=>$id),"rows");

                            if(count($check_dupli_uname) === 0) {

                                if($password === "$1default") {
                                    $update = $db->execQuery("UPDATE " . $this->table_prefix . "users
                                                    SET
                                                    username=:uname,
                                                    privilege=:priv,
                                                    first_name=:fname,
                                                    middle_name=:mname,
                                                    last_name=:lname,
                                                    email_address=:email
                                                    WHERE MD5(user_id)=:id", 
                                                    array(
                                                        ':id'=>$id,
                                                        ':uname'=>$username,
                                                        ':priv'=>$get_priv[0]['priv_id'],
                                                        ':fname'=>$first_name,
                                                        ':mname'=>$middle_name,
                                                        ':lname'=>$last_name,
                                                        ':email'=>$email_address
                                                    ), "update");
                                } else {
                                    $options = [
                                        'cost' => 8,
                                    ];

                                    $hash_pwd = password_hash($password, PASSWORD_BCRYPT, $options);

                                    $update = $db->execQuery("UPDATE " . $this->table_prefix . "users
                                                    SET
                                                    username=:uname,
                                                    password=:pwd,
                                                    privilege=:priv,
                                                    first_name=:fname,
                                                    middle_name=:mname,
                                                    last_name=:lname,
                                                    email_address=:email
                                                    WHERE MD5(user_id)=:id", 
                                                    array(
                                                        ':id'=>$id,
                                                        ':uname'=>$username,
                                                        ':pwd'=>$hash_pwd,
                                                        ':priv'=>$get_priv[0]['priv_id'],
                                                        ':fname'=>$first_name,
                                                        ':mname'=>$middle_name,
                                                        ':lname'=>$last_name,
                                                        ':email'=>$email_address
                                                    ), "update");    
                                }

                                if($update) {
                                    $request['errors']['header'][0] = "success";
                                    $request['errors']['header'][1] = "Data has been updated!";
                                    
                                    return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                                } else {
                                    $request['errors']['header'][0] = "danger";
                                    $request['errors']['header'][1] = "Saving record has been failed!";
                                    
                                    return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                                }   
                            } else {
                                $request['errors']['username'] = "Username is already exist";
                            
                                return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                            }
                        } else {
                            $request['errors']['username'] = "User not found!";
                            
                            return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                        }
                    } else {
                        $request['errors']['privilege'] = "Invalid option";
                        
                        return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                    }
                } else {
                    $request['errors']['password'] = "Password doesn't match";
                    $request['errors']['confirm_password'] = "Password doesn't match";
                    
                    return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
                }   
            }
            
            return $this->view(array('data'=>$request['data'], 'errors'=>$request['errors']));
        } else {
            if($this->setreq('id')) {
                $id = $this->get('id');

                $user = $db->execQuery("SELECT 
                                        md5(u.user_id) user_id,
                                        u.username,
                                        u.first_name,
                                        u.middle_name,
                                        u.last_name,
                                        u.email_address,
                                        pr.title,
                                        md5(u.privilege) privilege
                                    FROM " . $this->table_prefix . "users u
                                    LEFT JOIN " . $this->table_prefix . "user_privileges pr ON u.privilege = pr.priv_id
                                    WHERE md5(u.user_id)=:id",array(':id'=>$id),"rows");

                $user[0]['id'] = $id;
                return $this->view(array('data'=>$user[0]));
            }
        }
        
        return $this->view(array());
    }
    
    public function deleteAction() {
        $db = $this->dbOpen();

		if($this->setreq('id') && !empty($this->post('id'))) {
			$id = $this->post('id');

			$delu = $db->execQuery("DELETE FROM " . $this->table_prefix . "users WHERE MD5(user_id)=:id",array(':id'=>$id),"delete");

			if($delu) {
				$return = array('message'=>array('success','<strong>Success!</strong> User has been deleted!'));
			} else {
				$return = array('message'=>array('danger','<strong>Error!</strong> Deleting user has been failed!'));	
			}
		} else {
			$return = array('message'=>array('danger','<strong>Error!</strong> Permission denied!'));
		}

		$this->view($return, 'json');
    }
    
    public function getPrivilegeList( $id = null ) {
		$db = $this->dbOpen();
		
		$edit_mode = (!empty($id)) ? $id : "";

		$res = $db->execQuery("SELECT md5(priv_id), title FROM " . $this->table_prefix . "user_privileges",array(),"num");

		$html = "";				
		$html .= (!empty($edit_mode)) ? "<option value=\"\">-- Select --</option>" : "<option value=\"\" selected>-- Select --</option>";

		foreach($res as $rows) {
			if(!empty($edit_mode)) {
				if($rows[0] == $edit_mode) {
					$html .= "<option value=\"" . $rows[0] . "\" selected>" . $rows[1] . "</option>";
				} else {
					$html .= "<option value=\"" . $rows[0] . "\">" . $rows[1] . "</option>";
				}
			} else {
				$html .= "<option value=\"" . $rows[0] . "\">" . $rows[1] . "</option>";
			}
		}

		return $html;
	}
}