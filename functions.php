<?
	require_once("conf/db_connection.php");
	
	function formatNum($pano,$noOfDigit){
		$length = strlen($pano);
		$dif = $noOfDigit - $length;
		
		$digit = null;
		for($i = 1; $i<=$dif; $i++){
			$digit .= '0';
		}
		
		return $digit.$pano;
	}
	function getNewNum($control_type,$plus=false){
		$sql = "SELECT * FROM v_controlno WHERE control_type = '$control_type'";
		$qry = mysql_query($sql);
		$row = mysql_fetch_array($qry);
		
		$digit = $row['digit'];
		$control_code = $row['control_code'];
		
		if($plus == false){
			$lastseqno = $row['lastseqno'] + 1;
		}else{
			$lastseqno = $row['lastseqno'] + $plus;
		}
		
		$newnum = formatNum($lastseqno,$digit);
		return $control_code.$newnum;
	}
	function genRandomNumber($length) {
		$characters = '123456789';
		$string = '';    

		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters))];
		}

		return $string;
	}
	function dateFormat($date,$format){
		$newdate = null;
		if(!empty($date)){
			$newdate = date($format,strtotime($date));
		}
		
		return $newdate;
	}
	function TimeDiff($firstTime,$lastTime){
		$firstTime = strtotime($firstTime);
		$lastTime = strtotime($lastTime);

		// perform subtraction to get the difference (in seconds) between times
		$timeDiff = $lastTime-$firstTime;

		// return the difference
		return $timeDiff;
	}
	function generatePassword($password){
		$salt = 'FastQuickService';
		$newpass = md5(sha1($salt.$password));
		return $newpass;
	}
	function exportRowData($data,$headertype,$filename){
		header("Content-type: application/octet-stream");
		
		switch($headertype){
			case "excel":
				header("Content-type: application/vnd.ms-excel");
				break;
			default:
				break;
		}
		
		header( "Content-disposition: filename=" . $filename );
		print "$data";
	}
	function chkMenuAccess($menu,$user,$url){
		$sql_chkmenu = "SELECT * FROM v_user_access WHERE menu_id = '$menu' AND user_id = '$user' AND status = '1'";
		$qry_chkmenu = mysql_query($sql_chkmenu);
		$num_chkmenu = mysql_num_rows($qry_chkmenu);
		
		if($num_chkmenu == 0){
			echo '<script>alert("You have no access to this page! please contact the administrator to add your permission.");</script>';
			echo '<script>window.location="' . $url . '";</script>';
			exit();
		}
	}
	function BreakMe($string,$end){
		$str = explode(" ",$string);
		$strcnt = count($str);
		$strlen = strlen($string);
		
		$str1 = null;
		$start = 0;
		$start1 = 0;
		$end1 = 0;
		$cnt = 0;
		
		for($i=0; $i < $strcnt; $i++){
			$start1 += $start;
			$end1 += $end;
			$cnt += strlen($str[$i] . " ");
			if($cnt > $end){
				$str1 .= '|';
				$cnt = 0;
			}
			$str1 .= $str[$i] . ' ';
			
			$start1 += $end;
			$end1 += $end;
		}
		return $str1;
	}

	function getVatValue(){
		$sql = "SELECT * FROM v_configuration WHERE id = '1' AND config_type = 'vat_value'";
		$qry = mysql_query($sql);

		while($row = mysql_fetch_array($qry)){
			$val = $row['value'];
		}

		return $val / 100;
	}
?>