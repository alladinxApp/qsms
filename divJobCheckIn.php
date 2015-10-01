<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$worefno = $_GET['worefno'];
	$checkdate = dateFormat($today,"Y-m-d");
	
	$sqlchkcheckinout = "SELECT * FROM v_jobclock_checkin_checkout WHERE check_date = '$checkdate'";
	$qrychkcheckinout = mysql_query($sqlchkcheckinout);
	$numchkcheckinout = mysql_num_rows($qrychkcheckinout);
	
	if($numchkcheckinout > 0){
		$sql = "UPDATE tbl_jobclock_checkin_checkout SET check_in = '$today' WHERE wo_refno = '$worefno'";
	}else{
		$sql = "INSERT INTO tbl_jobclock_checkin_checkout(wo_refno,check_date,check_in) VALUES('$worefno','$today','$today')";
	}
	$qry = mysql_query($sql);
	
	$qrycheckinout = "SELECT * FROM v_jobclock_checkin_checkout WHERE wo_refno = '$worefno'";
	$rescheckinout = mysql_query($qrycheckinout);
	while($row = mysql_fetch_array($rescheckinout)){
		$rowcheckincheckout[] = array("wo_refno" => $row['wo_refno'],
								"check_date" => $row['check_date'],
								"check_in" => $row['check_in'],
								"check_out" => $row['check_out']);
	}
?>
<table>
	<tr>
		<th width="20">#</th>
		<th width="150">Check Date</th>
		<th width="100">Check IN</th>
		<th width="100">Check OUT</th>
		<th width="150">TOTAL No. of HOURS</th>
	</tr>
	<? $cnt = 1; $totalworkinghrs =0; foreach($rowcheckincheckout as $rowCHECKINOUT){ ?>
	<tr>
		<td><?=$cnt++;?></td>
		<td><?=dateFormat($rowCHECKINOUT['check_date'],"F d, Y");?></td>
		<td align="center"><?=dateFormat($rowCHECKINOUT['check_in'],"H:i");?></td>
		<td align="center">&nbsp;<? if(!empty($rowCHECKINOUT['check_out'])){ echo dateFormat($rowCHECKINOUT['check_out'],"H:i"); } ?></td>
		<?
			$diff = 0;
			if(!empty($rowCHECKINOUT['check_out'])){
				$checkout = $rowCHECKINOUT['check_out'];
			}else{
				$checkout = $today;
			}
			$from_time = strtotime($rowCHECKINOUT['check_in']);
			$to_time = strtotime($checkout);
			$diff = $to_time - $from_time;
			$temp = $diff / 3600;
			
			$hours = floor($temp); 
			$temp = 60 * ($temp - $hours);
			
			$minutes = floor($temp); 
			$temp = 60 * ($temp - $minutes); 
			
			$totalhrs += $temp;
			$totalmins += $minutes;
		?>
		<td align="center"><?=$hours . "." . $minutes;?> hr(s) </td>
	</tr>
		<? 
			}
			if($minutes > 60){
				$minutes = $minutes / 60;
			}
			$totalworkinghours = $hours + ($minutes * .10);
		?>
	<tr>
		<td colspan="4" align="right">TOTAL HOURS WORKED:</td>
		<td colspan="2" align="left"><b><?=$totalworkinghours;?> hr(s)</b></td>
	</tr>
</table>