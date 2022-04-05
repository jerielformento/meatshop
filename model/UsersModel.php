<?php 

class UsersModel extends Model {
	
    public $db;
    private $prefix;
    
	public $fields = array(
        'id' => array(
            'field'=>'', 
            'validate'=>'text', 
            'min'=>0, 
            'max'=>0
        ),
        'user_id' => array(
            'field'=>'', 
            'validate'=>'int', 
            'min'=>0, 
            'max'=>0
        ),
        'first_name' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>2, 
            'max'=>30
        ),
        'middle_name' => array(
            'field'=>'', 
            'validate'=>'text', 
            'min'=>0, 
            'max'=>30
        ),
        'last_name' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>2, 
            'max'=>30
        ),
        'username' => array(
            'field'=>'require', 
            'validate'=>'username', 
            'min'=>6, 
            'max'=>100
        ),
        'password' => array(
            'field'=>'require', 
            'validate'=>'password', 
            'min'=>8, 
            'max'=>30
        ),
        'confirm_password' => array(
            'field'=>'require', 
            'validate'=>'password', 
            'min'=>8, 
            'max'=>30
        ),
        'email_address' => array(
            'field'=>'', 
            'validate'=>'email', 
            'min'=>0, 
            'max'=>45
        ),
        'privilege' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>0, 
            'max'=>0
        )
    );

    public function __construct( $connection ) {
        $this->prefix = $connection['prefix'];
        $this->db = new Model( $connection );
    }
	
    public function checkExist( $username ) {
        return $this->db->execQuery("SELECT user_id, full_name, username, password, priv_id FROM " . $this->prefix . "users WHERE username=:user",array(':user'=>$username),"rows");
    }

    public function getPagesAccess( $privilege ) {
        return $this->db->execQuery("SELECT 
                                            acs.pa_id page_id, 
                                            p.page url,
                                            p.page_name name,
                                            p.page_icon icon
                                        FROM 
                                            " . $this->prefix . "pages_access AS acs 
                                        INNER JOIN 
                                            " . $this->prefix . "pages AS p 
                                        ON acs.pa_id=p.p_id 
                                        WHERE acs.privilege=:priv GROUP BY p.p_id ORDER BY p.arrange_no ASC",array(':priv'=>$privilege),"rows");
    }
}