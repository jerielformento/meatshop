<?php 

namespace REJ\Libs;

class Schedules {

	private $comments = array(
							'VL'=>'VL',
							'RDOT'=>array('RD OT','RDOT'),
							'DS'=>'Double Shift:',
							'IDLE'=>'IDLE:',
							'ML'=>'Maternity Leave',
							'OFS'=>'On Floating Status',
							'SPND'=>'Suspended',
							'OB'=>'OB'
						); 

	private $time_format = array(
							'1'=>array(array('A','01'),array('AM','01'),array('P','13'),array('PM','13')),
							'2'=>array(array('A','02'),array('AM','02'),array('P','14'),array('PM','14')),
							'3'=>array(array('A','03'),array('AM','03'),array('P','15'),array('PM','15')),
							'4'=>array(array('A','04'),array('AM','04'),array('P','16'),array('PM','16')),
							'5'=>array(array('A','05'),array('AM','05'),array('P','17'),array('PM','17')),
							'6'=>array(array('A','06'),array('AM','06'),array('P','18'),array('PM','18')),
							'7'=>array(array('A','07'),array('AM','07'),array('P','19'),array('PM','19')),
							'8'=>array(array('A','08'),array('AM','08'),array('P','20'),array('PM','20')),
							'9'=>array(array('A','09'),array('AM','09'),array('P','21'),array('PM','21')),
							'10'=>array(array('A','10'),array('AM','10'),array('P','22'),array('PM','22')),
							'11'=>array(array('A','11'),array('AM','11'),array('P','23'),array('PM','23')),
							'12'=>array(array('A','00'),array('AM','00'),array('P','12'),array('PM','12'))
						);


	//$day_week = array('Mon'=>array(1,5),'Tue'=>array(2,4),'Wed'=>array(3,3),'Thu'=>array(4,2),'Fri'=>array(5,1),'Sat'=>array(6,0),'Sun'=>array(0,6));

		
	private $day_week_sun_mon = array('Mon'=>array(1,5),'Tue'=>array(2,4),'Wed'=>array(3,3),'Thu'=>array(4,2),'Fri'=>array(5,1),'Sat'=>array(6,0),'Sun'=>array(0,6));
	private $day_week_mon_sun = array('Mon'=>array(0,6),'Tue'=>array(1,5),'Wed'=>array(2,4),'Thu'=>array(3,3),'Fri'=>array(4,2),'Sat'=>array(5,1),'Sun'=>array(6,0));


	public function validateComment( $c ) {
		$rt = array();

		if($c != null && !empty($c)) {
			$cm = explode("\n", $c);
			
			if($cm[1] == $this->comments['VL'] || $cm[0] == $this->comments['ML'] || $cm[1] == $this->comments['OFS'] || $cm[0] == $this->comments['SPND'] || $cm[1] == $this->comments['OB']) {
				$rt = array(
					'VL' => 1,
					'RDOT' => 0,
					'DS' => array(0, "", ""),
					'IDLE' => array(0, ""),
					'OB' => 0
				);
			} else if(in_array($cm[0],$this->comments['RDOT'])) {
				$rt = array(
					'VL' => 0,
					'RDOT' => 1,
					'DS' => array(0, "", ""),
					'IDLE' => array(0, ""),
					'OB' => 0
				);
			} else if($cm[0] == $this->comments['DS']) {
				$rt = array(
					'VL' => 0,
					'RDOT' => 0,
					'DS' => array(1, $cm[1], $cm[2]),
					'IDLE' => array(0, ""),
					'OB' => 0
				);
			} else if($cm[0] == $this->comments['IDLE']) {
				$rt = array(
					'VL' => 0,
					'RDOT' => 0,
					'DS' => array(0, "", ""),
					'IDLE' => array(1, $cm[1]),
					'OB' => 0
				);
			} else if($cm[0] == $this->comments['OB']) {
				$rt = array(
					'VL' => 0,
					'RDOT' => 0,
					'DS' => array(0, "", ""),
					'IDLE' => array(0, ""),
					'OB' => 1
				);
			}

			
		} else {
			$rt = array(
				'VL' => 0,
				'RDOT' => 0,
				'DS' => array(0, "", ""),
				'IDLE' => array(0, ""),
				'OB' => 0
			);
		}

		return $rt;

	}


	function getWeekRange($date, $view) {
		$curr = $date;
		$timestamp = strtotime($curr);
		
		$day = date('D', $timestamp);
		

		$arr_get_dates = array();
		$get_day_week = ($view == "SUN") ? $this->day_week_sun_mon : $this->day_week_mon_sun;
		
		foreach($get_day_week as $days => $arg) {
			if($days === $day) {
				
				for($i = $arg[0]; $i > 0; $i--) {
					array_push($arr_get_dates, date('Y-m-d', strtotime($curr . "-" . $i . " days")));
				}
				
				for($j = 0; $j <= $arg[1]; $j++) {
					array_push($arr_get_dates, date('Y-m-d', strtotime($curr . "+" . $j . " days")));
				}
			}
		}

		return $arr_get_dates; 
	}


	function timeFormatConverter( $time ) {
		$str = substr($time, -1);
		$rs = "";

		$get_time = "";
		$get_mins = "";
		$period = "";

		if($str === 'A') {	
			if(strlen($time) == 5) {
				$get_time = substr($time,0,2);
				$get_mins = substr($time,2,2);
			} else {
				$get_time = substr($time,0,1);
				$get_mins = substr($time,1,2);
			}

			$period = $str;
		} else if($str === 'P') {
			if(strlen($time) == 5) {
				$get_time = substr($time,0,2);
				$get_mins = substr($time,2,2);
			} else {
				$get_time = substr($time,0,1);
				$get_mins = substr($time,1,2);
			}

			$period = $str;
			//echo $get_time;
		} else if($str === 'M') {
			if(strlen($time) == 3) {
				$get_time = substr($time,0,1);
			} else {
				$get_time = substr($time,0,2);
			}

			$period = substr($time,-2);
			//echo $get_time;
		} else {
			echo "invalid time " . $str;
		}

		//echo $period;
		for($i = 1; $i <= 12; $i++) {
			if($get_time == $i) {
				foreach($this->time_format[$i] as $t) {
					if($period == $t[0]) {
						$rs = $t[1] . ':' . ((!empty($get_mins)) ? $get_mins : '00') . ':00';
					}
				}
			}
		}

		return $rs;
	}


	function timeFormatChecker( $time ) {
		$str = substr($time, -1);
		$rs = "";

		$get_time = "";
		$get_mins = "";
		$period = "";

		if($str === 'A') {	
			return 0;
		} else if($str === 'P') {
			return 0;
		} else if($str === 'M') {
			return 0;
		} else {
			return 1;
		}

		return 1;
	}

	public function convertTime( $time ) {
		$expt = explode("-", $time);
		$start = date("gA", strtotime($expt[0]));
		$end = date("gA", strtotime($expt[1]));
		
		return $start . '-' . $end;
	}
}