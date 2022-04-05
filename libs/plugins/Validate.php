<?php 

namespace REJ\Libs;

class Validate {
    public $attribute = [];
    
    public const INPUT_REQUIRED = 'require';
    public const INPUT_EMAIL = 'email';
    public const INPUT_TEXT = 'text';
    public const INPUT_TEXTAREA = 'textarea';
    public const INPUT_NUMBER = 'number';
    public const INPUT_DATE = 'date';
    public const INPUT_TIME = 'time';
    public const INPUT_USERNAME = 'username';
    public const INPUT_PASSWORD = 'password';
    public const INPUT_CURRENCY = 'currency';

    public function __construct( $data = [] ) {
        $this->attribute = $data;    
    }
    
    public function sanitize( $input, $params = array() ) {
        $val = trim($input);
        $error = 0;
        $message = "";
        
        if(!empty($val)) {
            
            if($params['min'] !== 0) {
                if($this->minLength($val, $params['min'])) {
                    $error = 1;
                    $message = "Min. length of " . $params['min'] . " characters";
                    return array($val, array($error, $message));
                }
            }

            if($params['max'] !== 0) {
                if($this->maxLength($val, $params['max'])) {
                    $error = 1;
                    $message = "Max. length of " . $params['max'] . " characters";
                    return array($val, array($error, $message));
                }
            }
            
            if($params['validate'] === self::INPUT_TEXT) {
                
                $val = $val;

            } else if($params['validate'] === self::INPUT_TEXTAREA) {

                $val = $val;
                
            } else if($params['validate'] === self::INPUT_EMAIL) {
                
                if(!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    $error = 1;   
                    $message = "Invalid email format";
                    return array($val, array($error, $message));
                }
                
            } else if($params['validate'] === self::INPUT_NUMBER) {
                $val = str_replace(",","",$val);

                if(!$this->isNumeric($val)) {
                    $error = 1;   
                    $message = "Field should be numeric";
                    return array($val, array($error, $message));
                }
            } else if($params['validate'] === self::INPUT_DATE) {
                if(!$this->checkDateTime($val, $params['format'])) {
                    $error = 1;   
                    $message = "Invalid date format";
                    return array($val, array($error, $message));
                }
            } else if($params['validate'] === self::INPUT_TIME) {
                if(!$this->checkDateTime($val, $params['format'])) {
                    $error = 1;   
                    $message = "Invalid time format";
                    return array($val, array($error, $message));
                }
            } else if($params['validate'] === self::INPUT_USERNAME) {
                if(preg_match("/[^A-Za-z0-9.-_]/i",$val)) {
                    $error = 1;   
                    $message = "Invalid username format (special characters are not allowed)";
                    return array($val, array($error, $message));
                }
            } else if($params['validate'] === self::INPUT_PASSWORD) {
                if(!$this->passwordCheck($val)) {
                    $error = 1;   
                    $message = "Password should include at least one number and one special character for account security";
                    return array($val, array($error, $message));
                }
            }
        } else {
            if($params['field'] === 'require') {
                $error = 1;
                $message = "This field is required";
                return array($val, array($error, $message));
            }
        }
        
        return array($val, array($error, $message));
    }
    
    public function minLength($string, $arg) {
		if(strlen($string) < $arg) {
			return true;
		} else {
			return false;
		}
	}

	public function maxLength($string, $arg) {
		if(strlen($string) > $arg) {
			return true;
		} else {
			return false;
		}
	}
    
    public function isInt($val) {
		if(ctype_digit($val)) {
			return true;
		} else {
			return false;
		}
	}
    
    public function isNumeric($val) {
		if(is_numeric($val)) {
			return true;
		} else {
			return false;
		}
	}

    public function checkDateTime( $datetime, $format = 'Y-m-d H:i:s' ) {
        if(strtotime($datetime)) {
            if($datetime == date($format, strtotime($datetime))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function convertToValidPrice( $price ) {
        $price = str_replace(['-', ',', '$', ' '], '', $price);
        if(!is_numeric($price)) {
            $price = null;
        } else {
            if(strpos($price, '.') !== false) {
                $dollarExplode = explode('.', $price);
                $dollar = $dollarExplode[0];
                $cents = $dollarExplode[1];
                if(strlen($cents) === 0) {
                    $cents = '00';
                } elseif(strlen($cents) === 1) {
                    $cents = $cents.'0';
                } elseif(strlen($cents) > 2) {
                    $cents = substr($cents, 0, 2);
                }
                $price = $dollar.'.'.$cents;
            } else {
                $cents = '00';
                $price = $price.'.'.$cents;
            }
        }

        return $price;
    }
    
    public function pregMatch() {
        if(preg_match("/[^A-Za-z0-9.-_]/i",$uname)) {
            return true;
        } else {
            return false;
        }
    }

	public function rmSpecialChr( $string ) {
		$rm_whitespace = str_replace(' ', '', $string); 
		$res = preg_replace('/[^A-Za-z0-9\. -_]/', '', $rm_whitespace);
		return $res;
	}
    
    public function passwordCheck( $password ) {
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if(!$number || !$specialChars) {
            return false;
        }else{
            return true;
        }
    }
    
    public function isError( $field ) {
        if(!empty($this->attribute['errors'][$field])) {
            return '<div class="invalid-feedback">' . $this->attribute['errors'][$field] . '</div>';
        }
    }
    
    public function errorClass( $field ) {
        if(!empty($this->attribute['errors'][$field])) {
            return "is-invalid";
        }
    }
   
}