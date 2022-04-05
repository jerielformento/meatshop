<?php 

namespace REJ\Libs;

class SMS {

   	private $sess;
	private $error;
	private $global;

	public function __construct( $glob_var ) {
		$this->global = $glob_var;
		$this->sess = $glob_var['session'];
		$this->error = $glob_var['error_file_path'];
	}

	public function sendSMS( $mobile, $message, $port ) {

        // 1. Initialize an CURL session.
    	$ch = curl_init();

    	//2. Setting the request options, including sprcific URL.
    	//echo "http://172.16.1.91:80/sendsms?username=admin&password=passw0rd&phonenumber=" . $mobile . "&message=" . $message . "&port=" . $port . "&report=String&timeout=5<br/>";
    	//$filter_spacing = str_replace(" ", "%20", $message);
        //$filter_newline = str_replace("\n", "%0a", nl2br($filter_spacing));
    	//$formatted_msg = $filter_newline;
        $formatted_msg = $message;
        curl_setopt($ch,CURLOPT_URL, $this->global['sms_gateway']['file_path'] . "?username=" . $this->global['sms_gateway']['user'] . "&password=" . $this->global['sms_gateway']['password'] . "&phonenumber=" . $mobile . "&message=" . $formatted_msg . "&port=" . $port . "&report=" . $this->global['sms_gateway']['report'] . "&timeout=" . $this->global['sms_gateway']['timeout']);

    	// 3. Execute a CURL session and get the reply.
        $response = curl_exec($ch);

    	// 4. Release the Curl handle and close the CURL session.
        //echo $response;
        curl_close($ch);
    }


    public function isMobile($mobile) {
        if (preg_match("/^[0-9+]{" . $this->global['sms_gateway']['mobile_manager']['normal_len'] . "," . $this->global['sms_gateway']['mobile_manager']['custom_len'] . "}$/",$mobile)) {
            // valid mobile number
            if(strlen($mobile) == $this->global['sms_gateway']['mobile_manager']['custom_len']) {
                if(strpos($mobile, $this->global['sms_gateway']['mobile_manager']['custom_prefix']) === 0) {
                    return true;
                } else {
                    return false;
                }
            } else if(strlen($mobile) == $this->global['sms_gateway']['mobile_manager']['normal_len']) {
                if(strpos($mobile, $this->global['sms_gateway']['mobile_manager']['normal_prefix']) === 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getMobileNetwork($mobile, $network) {
        
       if (preg_match("/^[0-9+]{" . $this->global['sms_gateway']['mobile_manager']['normal_len'] . "," . $this->global['sms_gateway']['mobile_manager']['custom_len'] . "}$/",$mobile)) {
            // valid mobile number
            if(strlen($mobile) == $this->global['sms_gateway']['mobile_manager']['custom_len']) {
                if(strpos($mobile, $this->global['sms_gateway']['mobile_manager']['custom_prefix']) === 0) {
                    $prefix = substr($mobile, 3, 3);

                    foreach($network as $net) {
                        if($prefix == $net[0]) {
                            if($net[1] == 1) {
                                return array($this->global['sms_gateway']['mobile_manager']['globe_port'], $this->global['sms_gateway']['mobile_manager']['smart_port']);
                            } else if($net[1] == 2) {
                                return array($this->global['sms_gateway']['mobile_manager']['smart_port'], $this->global['sms_gateway']['mobile_manager']['globe_port']);
                            }
                        }
                    }

                    return "";
                } else {
                    return "";
                }
            } else if(strlen($mobile) == $this->global['sms_gateway']['mobile_manager']['normal_len']) {
                if(strpos($mobile, $this->global['sms_gateway']['mobile_manager']['normal_prefix']) === 0) {
                    $prefix = substr($mobile, 1, 3);

                    foreach($network as $net) {
                        if($prefix == $net[0]) {
                            if($net[1] == 1) {
                                return array($this->global['sms_gateway']['mobile_manager']['globe_port'], $this->global['sms_gateway']['mobile_manager']['smart_port']);
                            } else if($net[1] == 2) {
                                return array($this->global['sms_gateway']['mobile_manager']['smart_port'], $this->global['sms_gateway']['mobile_manager']['globe_port']);
                            }
                        }
                    }

                    return "";
                } else {
                    return "";
                }
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

}