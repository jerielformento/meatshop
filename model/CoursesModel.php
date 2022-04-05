<?php 
namespace REJ\Model;

class CoursesModel {
	
	public $fields = array(
        'id' => array(
            'field'=>'', 
            'validate'=>'text', 
            'min'=>0, 
            'max'=>0
        ),
        'course_id' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>3, 
            'max'=>0
        ),
        'course_type' => array(
            'field'=>'require', 
            'validate'=>'text', 
            'min'=>3, 
            'max'=>32
        ),
        'date_from' => array(
            'field'=>'require', 
            'validate'=>'date', 
            'format'=>'m/d/Y',
            'min'=>10, 
            'max'=>10
        ),
        'date_to' => array(
            'field'=>'require', 
            'validate'=>'date', 
            'format'=>'m/d/Y',
            'min'=>10, 
            'max'=>10
        ),
        'time_from' => array(
            'field'=>'require', 
            'validate'=>'time', 
            'format'=>'g:ia',
            'min'=>6, 
            'max'=>7
        ),
        'time_to' => array(
            'field'=>'require', 
            'validate'=>'time', 
            'format'=>'g:ia',
            'min'=>6, 
            'max'=>7
        ),
        'tuition_fee' => array(
            'field'=>'require', 
            'validate'=>'number', 
            'min'=>3, 
            'max'=>20
        ),
        'downpayment' => array(
            'field'=>'require', 
            'validate'=>'number', 
            'min'=>3, 
            'max'=>20
        ),
        'handout' => array(
            'field'=>'require', 
            'validate'=>'number', 
            'min'=>3, 
            'max'=>20
        )
    );
	
}