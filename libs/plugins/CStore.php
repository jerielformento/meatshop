<?php 

namespace REJ\Libs;

class CStore {
	
	
	/* global init variables */
	private $global;
	private $sess;
	private $error;
	
	
	/* CS variables */
	private $cs_config = array(
        'customer_group_id' => 1,
        'telephone' => 'n/a',
        'fax' => '',
        'custom_field' => '',
        'newsletter' => 0,
        'status' => 1,
        'safe' => 0,
        'approved' => 1,
        'token' => '',
        'salt' => 'bdae0b1c7',
        'pwd' => '157d077335b11a15063e826ce4db1e0da5dbf3c4'
    );
    
    private $customer_add = array(
        'christian.oriane',
        'jollie.balmores',
        'robert.delatorre',
        'alponiano.jore',
        'aileen.dilao',
        'john.evangelista',
        'denmark.zuniga',
        'mark.salazar',
        'jedidiah.maalindog',
        'necileen.agbayani',
        'marialyn.bagaipo',
        'leslie.empredo',
        'hiero.ruiz',
        'gamaliel.manuel',
        'bennylyn.socorro',
        'mark.francisco',
        'alliana.torreon',
        'dave.guianalan',
        'crisby.delgado',
        'kristofferson.vinta',
        'clyde.ancheta',
        'cyde.cabiara',
        'mark.manuel',
        'jaymel.alpecho',
        'sherylene.bathan',
        'czarina.reobilo',
        'judilyn.torrejos',
        'abigail.tuazon',
        'marjorie.yangyang',
        'kris.toledo',
        'jerico.devero',
        'trisha.perez',
        'rochelle.badion',
        'renz.cana',
        'gerald.berdin',
        'jan.sigue',
        'sean.desabille',
        'monique.dalida',
        'krys.magturo',
        'raiza.macabodbod',
        'judy.balading',
        'louwella.guanzon',
        'bradice.mercado',
        'belinda.avelino'
    );
	
	
	public function __construct( $glob_var ) {
		$this->global = $glob_var;
		$this->sess = $glob_var['session'];
		$this->error = $glob_var['error_file_path'];
	}

	
	/* This function will log users activity for auditing */
	public function addCustomer( $username, $firstname, $lastname, $db ) {
        $salt = $this->generateSalt();
        
        $ins = $db->execQuery("INSERT INTO cs_store.cs_customer
                            (
                                customer_group_id,
                                firstname,
                                lastname,
                                email,
                                telephone,
                                fax,
                                custom_field,
                                ip,
                                newsletter,
                                salt,
                                password,
                                status,
                                approved,
                                safe,
                                token,
                                date_added
                            )
                            VALUES
                            (
                                :cusgrp,
                                :fname,
                                :lname,
                                :email,
                                :tel,
                                :fax,
                                :cusfield,
                                :ip,
                                :news,
                                :salt,
                                :pwd,
                                :status,
                                :approve,
                                :safe,
                                :token,
                                :date
                            )
                            ",array(
                                ':cusgrp' => $this->cs_config['customer_group_id'],
                                ':fname' => $firstname,
                                ':lname' => $lastname,
                                ':email' => $username,
                                ':tel' => $this->cs_config['telephone'],
                                ':fax' => $this->cs_config['fax'],
                                ':cusfield' => $this->cs_config['custom_field'],
                                ':ip' => $_SERVER['REMOTE_ADDR'],
                                ':news' => $this->cs_config['newsletter'],
                                ':salt' => $this->cs_config['salt'],
                                ':pwd' => $this->cs_config['pwd'],
                                ':status' => $this->cs_config['status'],
                                ':approve' => $this->cs_config['approved'],
                                ':safe' => $this->cs_config['safe'],
                                ':token' => $this->cs_config['token'],
                                ':date' => date("Y-m-d H:i:s")
                            ),"insert");
        
        if($ins) {
            return true;
        } else {
            return false;
        }
      //$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(serialize($data['custom_field'])) . "', newsletter = '" . (int)$data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', safe = '" . (int)$data['safe'] . "', date_added = NOW()");
        
	}
    
    
    public function addMultiCustomer( $cus_arr, $db ) {
        $salt = $this->generateSalt();
        
        $customers = "";
        foreach($cus_arr as $c) {
            $customers .= "(" . $this->cs_config['customer_group_id'] . ",'" . $c[0] . "','" . $c[1] . "','" . $c[2] . "','" . $this->cs_config['telephone'] . "','" . $this->cs_config['fax'] . "','" . $this->cs_config['customer_group_id'] . "','" . $_SERVER['REMOTE_ADDR'] . "'," . $this->cs_config['newsletter'] . ",'" . $this->cs_config['salt'] . "','" . $this->cs_config['pwd'] . "'," . $this->cs_config['status'] . "," . $this->cs_config['approved'] . "," . $this->cs_config['safe'] . ",'" . $this->cs_config['token'] . "','" . date("Y-m-d H:i:s") . "'),";
        }
        
        $ins = $db->execQuery("INSERT INTO cs_store.cs_customer
                            (
                                customer_group_id,
                                firstname,
                                lastname,
                                email,
                                telephone,
                                fax,
                                custom_field,
                                ip,
                                newsletter,
                                salt,
                                password,
                                status,
                                approved,
                                safe,
                                token,
                                date_added
                            )
                            VALUES" . substr($customers,0,-1),array(),"insert");
        
        if($ins) {
            return true;
        } else {
            return false;
        }
      //$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(serialize($data['custom_field'])) . "', newsletter = '" . (int)$data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', safe = '" . (int)$data['safe'] . "', date_added = NOW()");
        
	}
    
    
    public function removeCustomer( $username, $db ) {
        $del = $db->execQuery("DELETE FROM cs_store.cs_customer WHERE email=:user",array(':user'=>$username),"delete");
        if($del) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function addMissingCustomer($db) {
        foreach($this->customer_add as $cus) {
            $find = $db->execQuery("SELECT customer_id FROM cs_store.cs_customer WHERE email=:user",array(':user'=>$cus),"rows");
            if(count($find) == 0) {
                $get_user = $db->execQuery("SELECT u.user_username uname,p.profile_fname fname,p.profile_lname lname FROM tbl_users u 
                                LEFT JOIN tbl_profiles p ON p.user_username=u.user_username WHERE u.user_username=:uid",array(':uid'=>$cus),"rows");
                
                if($this->addCustomer($get_user[0]['uname'],$get_user[0]['fname'],$get_user[0]['lname'], $db)) {
                    echo $get_user['uname'] . ' ok<br/>';
                } else {
                    echo $get_user['uname'] . ' not ok<br/>';
                }
            } else {
                echo $cus . ' existing<br/>';
            }
        }
    }
    
    private function generateSalt() {
        return substr(md5(uniqid(rand(), true)), 0, 9);
    }
	
}