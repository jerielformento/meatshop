<?php 
namespace REJ\Model;

class StudentsModel {
	
	public $fields = array(
        'id' => array(
            'field'=>'', 
            'validate'=>'text', 
            'min'=>0, 
            'max'=>0
        ),
        'nickname' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>2, 
            'max'=>20
        ),
        'first_name' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>2, 
            'max'=>30
        ),
        'middle_name' => array(
            'field'=>'require', 
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
        'birthdate' => array(
            'field'=>'require', 
            'validate'=>'date', 
            'format'=>'m/d/Y',
            'min'=>10, 
            'max'=>10
        ),
        'school' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>0, 
            'max'=>0
        ),
        'mobile_number' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>11, 
            'max'=>13
        ),
        'email_address' => array(
            'field'=>'', 
            'validate'=>'email', 
            'min'=>0, 
            'max'=>45
        ),
        'status' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>0, 
            'max'=>0
        ),
        'current_address' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>1, 
            'max'=>150
        ),
        'photo_path' => array(
            'field'=>'require', 
            'validate'=>'photo', 
            'min'=>3, 
            'max'=>150
        ),
    );
	
}