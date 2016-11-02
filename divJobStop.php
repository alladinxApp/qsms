<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$worefno = $_GET['worefno'];
	
	$getempqry = new v_service_master;
	$getempres = $dbo->query($getempqry->Query("WHERE wo_refno = '$worefno'"));
	foreach($getempres as $rowEMP){
		$empid = $rowEMP['emp_id'];
		$estimaterefno = $rowEMP['estimate_refno'];
		if($rowEMP['po_refno'] != '0'){
			$po_refno = $rowEMP['po_refno'];
		}else{
			$po_refno = '0';
		}
	}
	$billing_refno = getNewNum('BILLING');
	
	$check_date = dateFormat($today,"Y-m-d H:i");
	$qry = null;
	$qry .= "UPDATE tbl_jobclock_checkin_checkout SET check_out = '$check_date' WHERE wo_refno = '$worefno'; ";
	$qry .= "UPDATE tbl_jobclock_master SET job_end = '$today', job_status = '2' WHERE wo_refno = '$worefno'; ";
	$qry .= "UPDATE tbl_service_master SET trans_status = '5' WHERE wo_refno = '$worefno'; ";
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
	</tr>
</table>
</fieldset>	

<!--<fieldset form="form_highschool" name="form_highschool">
<legend><p id="title">TECHNICIAN PERFORMANCE:</p></legend>
<table>
	<tr>
		<td class ="label"><label name="lbl_plateno">Technician:</label></td>
		<td class ="input"><input type="text" name="txtTechnician" id="txtTechnician" value="<?=$technician;?>" readonly style="width:235px"></td>
		<td></td>
		<td class ="label"><label name="lbl_year">Standard Working Hours:</label></td>
		<td class ="input"><input type="text" name="txtStdWorkingHrs" id="txtStdWorkingHrs" value="<?=$stdworkinghrs;?>" style="width:100px" id="">
		<td></td>
		<td class ="label"><label name="lbl_make">(Less) Idle Hours:</label></td>
		<td class ="input"><input type="text" name="txtTotalIdleHrs" id="txtTotalIdleHrs" value="<?=$totalidlehrs;?>" style="width:118px" id=""></td> 
	</tr>		
	<tr>
		<td class ="label"><label name="lbl_model">Total Working Hours</label></td>
		<td class ="input"><input type="text" name="txtTotalWorkingHrs" id="txtTotalWorkingHrs" value="<?=$totalworkinghrs;?>" style="width:235px"></td>
		<td></td>
		<td class ="label"><label name="lbl_color">Actual Working Hours:</label></td>
		<td class ="input"><input type="text" name="txtActualWorkingHrs" id="txtActualWorkingHrs" value="<?=$actualworkinghrs;?>" style="width:100px"></td>
		<td></td>
		<td class ="label"><label name="lbl_color">Variance:</label></td>
		<td class ="input"><input type="text" name="txtVariance" id="txtVariance" value="<?=$variance;?>" style="width:118px"></td>
		<td></td>
	</tr>
</table>
</fieldset>-->