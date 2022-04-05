<?php

require_once "SSH2.php";

class SSH {

	public $server = '172.16.0.76';
	public $port = '284';
	public $uname = 'root';
	public $passw = 'Q@IT_jabba08!!C';
	
	public function startSSH($type_script) {
		$ssh = new Net_SSH2($this->server, $this->port);
		$res = $this->execCMD($type_script);
		return $res;
	}
	
	public function execCMD($get_script) {
		$ssh = new Net_SSH2($this->server, $this->port);
		if(!empty($get_script)) {
			$stream = $ssh->exec($get_script);
			echo $stream;
		}
		
		return true;
	}
	
	public function getActiveCallsE11() {
		
		$ssh = new Net_SSH2($this->server, $this->port);
		if($ssh->login($this->uname, $this->passw)) {
			return $ssh->exec('ssh -T -i /root/.ssh/greenw-isdn-utilization -qp 284 root@172.16.64.90');
		} else {
			return 'Login failed.';
		}
	}
	
	public function getActiveCallsE12() {
		
		$ssh = new Net_SSH2($this->server, $this->port);
		if($ssh->login($this->uname, $this->passw)) {
			return $ssh->exec('ssh -T -i /root/.ssh/check-isdn-utilization -qp 284 root@172.16.64.92');
		} else {
			return 'Login failed.';
		}
	}

}

?>