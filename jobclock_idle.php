<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$worefno = $_GET['worefno'];
	
	$qryservices = "SELECT * FROM v_service_master WHERE wo_refno = '$worefno'";
	$resservices = $dbo->query($qryservices);
	
	foreach($resservices as $rowSERVICES){
		$estimaterefno = $rowSERVICES['estimate_refno'];
		$techid = $rowSERVICES['emp_id'];
		$technician = $rowSERVICES['tech_name'];
		$transstatus = $rowSERVICES['trans_status'];
	}
	
	$qryidle = "SELECT * FROM v_idle ORDER BY idle_id";
	$residle = $dbo->query($qryidle);
	
	$qryidletime = "SELECT * FROM v_idle_time";
	$residletime = $dbo->query($qryidletime);
	$residletime1 = $dbo->query($qryidletime);
	
	$qryidletimehistorymst = "SELECT * FROM v_jobclock_master WHERE wo_refno = '$worefno'";
	$residletimehistorymst = $dbo->query($qryidletimehistorymst);
	$numidletimehistorymst = 0;
	
	$qrycheckinout = "SELECT * FROM v_jobclock_checkin_checkout WHERE wo_refno = '$worefno'";
	$rowcheckinout = $dbo->query($qrycheckinout);
	
	foreach($residletimehistorymst as $rowIDLETIMEHISTORYMST){
		$jobstart = $rowIDLETIMEHISTORYMST['job_start'];
		$jobend = $rowIDLETIMEHISTORYMST['job_end'];
		$stdworkkinghrs = $rowIDLETIMEHISTORYMST['std_working_hrs'];
		$totalidlehrs = $rowIDLETIMEHISTORYMST['total_idle_hrs'];
		$totalworkinghrs = $rowIDLETIMEHISTORYMST['total_working_hrs'];
		$actualworkinghrs = $rowIDLETIMEHISTORYMST['actual_working_hrs'];
		$variance = $rowIDLETIMEHISTORYMST['variance'];
		$jobstatus = $rowIDLETIMEHISTORYMST['job_status'];
		$numidletimehistorymst++;
	}
	
	$qryidletimehistorydtl = "SELECT * FROM v_jobclock_detail WHERE wo_refno = '$worefno'";
	$rowidletimehistorydtl = $dbo->query($qryidletimehistorydtl);
	
	function date_difference($date1timestamp, $date2timestamp) {
		$all = round(($date1timestamp - $date2timestamp) / 60);
		$d = floor ($all / 1440);
		$h = floor (($all - $d * 1440) / 60);
		$m = $all - ($d * 1440) - ($h * 60);
		//Since you need just hours and mins
		return array('hours'=>$h, 'mins'=>$m);
	}
?>
<html>
<head>
<title</title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<script type="text/javascript">
	function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp = false;	
		try{
			xmlhttp = new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp = false;
				}
			}
		}
		return xmlhttp;
	}

	function AddNewIdleTime(worefno){
		var idletype = document.getElementById("txtIdleType").value;
		var idletypestart = document.getElementById("txtIdleTypeStart").value;
		var idletypeend = document.getElementById("txtIdleTypeEnd").value;
		var remarks = document.getElementById("txtRemarks").value;
		
		var strURL = "divIdleTimeHistory.php?type="+idletype+"&start="+idletypestart+"&end="+idletypeend+"&remarks="+remarks+"&worefno="+worefno;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divIdleTimeHistory').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function StartJob(worefno){
		var strURL = "divJobStart.php?worefno="+worefno;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divWorkClock').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function StopJob(worefno){
		var strURL = "divJobStop.php?worefno="+worefno;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divWorkClock').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function JobCheckIn(worefno){
		var strURL = "divJobCheckIn.php?worefno="+worefno;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divJobCheckInOut').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	function JobCheckOut(worefno){
		var strURL = "divJobCheckOut.php?worefno="+worefno;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divJobCheckInOut').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	
</script>
<style type="text/css">
	div.divIdleTimeHistory{ width: 750px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.divIdleTimeHistory table{ border: 1px solid #ccc; font-size: 12px; padding: 0; }
	div.divIdleTimeHistory table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
	div.divIdleTimeHistory table td{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }
	
	div.divCheckInOutTable{ width: 520px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.divCheckInOutTable table{ border: 1px solid #ccc; font-size: 12px; padding: 0; }
	div.divCheckInOutTable table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
	div.divCheckInOutTable table td{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }
</style>
<body>
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">REFERENCES</p></legend>
	<table>
		<tr>
			<td width="100"><span class="label">ESTIMATE NO.</span>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$estimaterefno;?></td>
			<td width="10">&nbsp;</td>
			<td width="120"><span class="label">WORK ORDER NO.</span></td>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$worefno;?></td>
		</tr>
	</table>
	</fieldset>
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">TECHNICIAN</p></legend>
	<span id="divEmpInfo">
	<table>
		<tr>
			<td width="110"><span class="label">Technician Code: </span></td>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$techid;?></td>
			<td width="110"><span class="label">Technician Name: </span></td>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$technician;?></td>
		</tr>
	</table>
	</fieldset>
	
	<? if(!empty($jobstart)){ ?>
	<span id="divCheckInOut">
	<fieldset form="form_highschool" name="form_highschool">
	<legend><p id="title">WORK CHECK IN-OUT CREATION:</p></legend>
	<? if(!empty($jobstart) || empty($jobend)){ ?>
	<table>
		<tr>
			<td class ="input" style="text-align: center; width: 220px;">
			<? 	if($transstatus != '5'){ ?>
			<img src="images/start.png" width="169" height="50" onClick="JobCheckIn('<?=$worefno;?>');" style="cursor: pointer;">
			<? } else { echo '<img src="images/start.png" width="169" height="50"'; }?>
			</td>
			<td class ="input" style="text-align: center; width: 220px;">
			<?	if($transstatus != '5'){ ?>
			<img src="images/STOP.png" width="169" height="50" onClick="JobCheckOut('<?=$worefno;?>');" style="cursor: pointer;">
			<? 
				} else { echo '<img src="images/STOP.png" width="169" height="50"'; }?>
			</td>
		</tr>
	</table><br />
	<? } ?>
	<div class="divCheckInOutTable">
	<span id="divJobCheckInOut">
	<table>
		<tr>
			<th width="20">#</th>
			<th width="150">Check Date</th>
			<th width="100">Check IN</th>
			<th width="100">Check OUT</th>
			<th width="150">TOTAL No. of HOURS</th>
		</tr>
		<? 
			$cnt = 1; 
			$totalworkinghrs = 0;
			$totalhrs = 0;
			foreach($rowcheckinout as $rowCHECKINOUT){ 
				$totalmins = strtotime($rowCHECKINOUT['check_out']) - strtotime($rowCHECKINOUT['check_in']) / 60 / 60;
		?>
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
	</span>
	<div class="divIdleTimeHistory">
	</div>
    </fieldset>
	</span>
	<? } ?>
	<? if(!empty($jobstart)){ ?>
	<fieldset form="form_costestimate" name="form_costestimate">
	<legend><p id="title">IDLE TIME HISTORY:</p></legend>
	<? if(empty($jobend)){ ?>
	<table>
		<tr>
			<td><span class="label">Idle Type: </span></td>
			<td><select name="txtIdleType" id="txtIdleType" <?=$disabled;?>>
				<option value="">Please select Idle Type</option>
				<? foreach($residle as $rowIDLE){ ?>
				<option value="<?=$rowIDLE['idle_id'];?>"><?=$rowIDLE['idle_name'];?></option>
				<? } ?>
			</select></td>
			<td><select name="txtIdleTypeStart" id="txtIdleTypeStart" <?=$disabled;?>>
				<option value="">Start Time</option>
				<? foreach($residletime as $rowIDLETIME){ ?>
				<option value="<?=$rowIDLETIME['drop_id'];?>"><?=$rowIDLETIME['drop_data'];?></option>
				<? } ?>
			</select></td>
			<td><select name="txtIdleTypeEnd" id="txtIdleTypeEnd" <?=$disabled;?>>
				<option value="">End Time</option>
				<? foreach($residletime1 as $rowIDLETIME1){ ?>
				<option value="<?=$rowIDLETIME1['drop_id'];?>"><?=$rowIDLETIME1['drop_data'];?></option>
				<? } ?>
			</select></td>
			<td>Remarks: </td>
			<td><input type="text" name="txtRemarks" id="txtRemarks" <?=$disabled;?> /></td>
			<td><input type="submit" name="btnAdd" id="btnAdd" value="" onClick="AddNewIdleTime('<?=$worefno;?>');" /></td>
		</tr>
	</table>
	<? } ?>
	<div class="divIdleTimeHistory">
	<span id="divIdleTimeHistory">
	<table>
		<tr>
			<th width="20">#</th>
			<th width="100">IDLE TYPE</th>
			<th width="100">START TIME</th>
			<th width="100">END TIME</th>
			<th width="100">TOTAL HOURS</th>
			<th width="300">REMARKS</th>
		</tr>
		<? foreach($rowidletimehistorydtl as $rowIDLETIMEHISTORY){ $totalhrs = 0; ?>
		<tr>
			<td><?=$rowIDLETIMEHISTORY['seqno'];?></td>
			<td><?=$rowIDLETIMEHISTORY['idle_name'];?></td>
			<td align="center"><?=dateFormat($rowIDLETIMEHISTORY['time_start'],"h:i");?></td>
			<td align="center"><?=dateFormat($rowIDLETIMEHISTORY['time_end'],"h:i");?></td>
			<?
				$difference = TimeDiff($rowIDLETIMEHISTORY['time_start'],$rowIDLETIMEHISTORY['time_end']) / 60 . " minutes";
				$totalidletime += $difference;
			?>
			<td align="right"><?=$difference;?></td>
			<td><?=$rowIDLETIMEHISTORY['remarks'];?></td>
		</tr>
		<? } ?>
		<tr>
			<td colspan="4" align="right">TOTAL IDLE TIME:</td>
			<td colspan="2" align="left"><b><?=$totalidletime / 60;?> hr(s)</b></td>
		</tr>
	</table>
	</span>
	</div>
	</fieldset>
	<? } ?>
</body>
</html>