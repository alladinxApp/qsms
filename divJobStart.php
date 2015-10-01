<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$worefno = $_GET['worefno'];
	
	// QUERY GET ESTIMATE REF NO
	$qrygetestimaterefno = "SELECT * FROM v_service_master WHERE wo_refno = '$worefno'";
	$resgetestimaterefno = $dbo->query($qrygetestimaterefno);
	foreach($resgetestimaterefno as $rowgetestimaterefno){
		$est_refno = $rowgetestimaterefno['estimate_refno'];
	}
	
	// GET STANDARD WORKING HOURS
	$qrygetstdhr = "SELECT SUM(stdworkinghr) as stdhr FROM v_service_detail_job WHERE estimate_refno = '$est_refno'";
	$resgetstdhr = $dbo->query($qrygetstdhr);
	foreach($resgetstdhr as $rowgetstdhr){
		$stdhr = $rowgetstdhr['stdhr'];
	}
	
	$check_date = dateFormat($today,"Y-m-d H:i");
	$qry = null;
	$qry .= "INSERT INTO tbl_jobclock_checkin_checkout(wo_refno,check_date,check_in) VALUES('$worefno','$check_date','$check_date'); ";
	$qry .= "UPDATE tbl_jobclock_master SET job_start = '$today', std_working_hrs = '$stdhr' WHERE wo_refno = '$worefno'; ";
	$qry .= "UPDATE tbl_service_master SET trans_status = '8' WHERE wo_refno = '$worefno'; ";
	$qry .= "UPDATE tbl_po_master SET trans_status = '2' WHERE wo_refno = '$worefno'; ";

	$res = $dbo->query($qry);

	// GET DATA OF JOBCLOCK MASTER
	$qryidletimehistorymst = "SELECT * FROM v_jobclock_master WHERE wo_refno = '$worefno'";
	$residletimehistorymst = mysql_query($qryidletimehistorymst);
	while($row = mysql_fetch_array($residletimehistorymst)){
		$jobstatus = $row['job_status'];
		$jobstart = $row['job_start'];
		$jobend = $row['job_end'];
	}
?>
<fieldset form="form_highschool" name="form_highschool">
<legend><p id="title">WORK CLOCK CREATION:</p></legend>
<table>
	<tr>
		<td width="220" style="text-align: center; font-size: 40px; color: #009900; font-weight: bold;"><?=dateFormat($jobstart,"F d");?></td>
		<td width="220" style="text-align: center; font-size: 40px; color: #009900; font-weight: bold;"><?  if(!empty($jobend)){ echo dateFormat($jobend,"F d"); } ?></td>
	</tr>
	<tr>
		<td style="text-align: center; font-size: 40px; color: #ff0000; font-weight: bold;"><?=dateFormat($jobstart,"H:i");?></td>
		<td style="text-align: center; font-size: 40px; color: #ff0000; font-weight: bold;"><? if(!empty($jobend)){ echo dateFormat($jobend,"H:i"); } ?></td>
	</tr>
	<tr>
		<td class ="input" style="text-align: center;">
		<? 	if($jobstart == null || $jobstart == "" || empty($jobstart)){ ?>
		<img src="images/start.png" width="169" height="50" onClick="StartJob('<?=$worefno;?>');" style="cursor: pointer;">
		<? } if($jobstart != null && $jobstart != "") { echo '<img src="images/start.png" width="169" height="50"'; }?>
		</td>
		<td class ="input" style="text-align: center;">
		<? 	if(($jobstart != null || $jobstart != "" || !empty($jobstart)) &&
				($jobend == null || $jobend == "" || empty($jobend))){ ?>
		<img src="images/STOP.png" width="169" height="50" onClick="StopJob('<?=$worefno;?>');" style="cursor: pointer;">
		<? }if($jobstatus == '2' ){ echo '<img src="images/STOP.png" width="169" height="50"'; }?>
		</td>
		<? if($jobstart != null && $jobstart != "") { ?>
		<td class ="input" style="text-align: center;">
			<a href="jobclock_idle.php?worefno=<?=$worefno;?>"><img src="images/idle2.png" width="169" height="50" style="float: left; cursor: pointer; border: none;"></a>
		</td>
		<? } ?>
	</tr>
</table>
</fieldset>