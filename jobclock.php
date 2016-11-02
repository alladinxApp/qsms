<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	chkMenuAccess('jobclock',$_SESSION['username'],'jobclock_list.php');
	
	$worefno = $_GET['worefno'];
	
	$qryservices = new v_service_master;
	$resservices = $dbo->query($qryservices->Query("WHERE wo_refno = '$worefno'"));
	
	foreach($resservices as $rowSERVICES){
		$estimaterefno = $rowSERVICES['estimate_refno'];
		$customerid = $rowSERVICES['customer_id'];
		$customername = $rowSERVICES['customername'];
		$plateno = $rowSERVICES['plate_no'];
		$yeardesc = $rowSERVICES['year_desc'];
		$makedesc = $rowSERVICES['make_desc'];
		$modeldesc = $rowSERVICES['model_desc'];
		$colordesc = $rowSERVICES['color_desc'];
		$variant = $rowSERVICES['variant'];
		$engineno = $rowSERVICES['engine_no'];
		$chassisno = $rowSERVICES['chassis_no'];
		$serialno = $rowSERVICES['serial_no'];
		$techid = $rowSERVICES['emp_id'];
		$technician = $rowSERVICES['tech_name'];
		$transstatus = $rowSERVICES['trans_status'];
		$promisedate = $rowSERVICES['promise_date'];
		$promisetime = $rowSERVICES['promise_time'];
	}
	
	$qryidletimehistorymst = "SELECT * FROM v_jobclock_master WHERE wo_refno = '$worefno'";
	$residletimehistorymst = $dbo->query($qryidletimehistorymst);
	foreach($residletimehistorymst as $row){
		$jobstatus = $row['job_status'];
		$jobstart = $row['job_start'];
		$jobend = $row['job_end'];
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
	<table><tr><td><a href="workorder_print.php?estimaterefno=<?=$estimaterefno;?>" target="_blank">
	<div style="width:100px; height:50px; text-align: center;"><img src="images/print_wo.png" border="0" style="pointer: cursor;" width="59" /></div></a></td></tr></table>
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
	<p id="title">work order repair information</p>
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<table>
		<tr>
			<td width="100"><span class="label">CUSTOMER CODE</span>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$customerid;?></td>
			<td width="10">&nbsp;</td>
			<td width="120"><span class="label">CUSTOMER NAME</span></td>
			<td width="300" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$customername;?></td>
		</tr>
	</table>
	</fieldset>
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">VEHICLE INFORMATION:</p></legend>
	<table width="790">
		<tr>
			<td width="50"><span class="label">Plate No: </span></td>
			<td width="130" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$plateno;?></td>
			<td width="50"><span class="label">Year: </span></td>
			<td width="130" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$yeardesc;?></td>
			<td width="50"><span class="label">Make: </span></td>
			<td width="100" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$makedesc;?></td>
		</tr>
		<tr>
			<td><span class="label">Variant: </span></td>
			<td style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$variant;?></td>
			<td><span class="label">Model: </span></td>
			<td style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$modeldesc;?></td>
			<td><span class="label">Color: </span></td>
			<td style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$colordesc;?></td>
		</tr>
		<tr>
			<td><span class="label">Engine No: </span></td>
			<td style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$engineno;?></td>
			<td><span class="label">Chassis No: </span></td>
			<td style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$chassisno;?></td>
			<td><span class="label">Serial No: </span></td>
			<td style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$serialno;?></td>
		</tr>
	</table>
    </fieldset>
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">TECHNICIAN</p></legend>
	<table>
		<tr>
			<td width="110"><span class="label">Technician Code: </span></td>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$techid;?></td>
			<td width="110"><span class="label">Technician Name: </span></td>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$technician;?></td>
		</tr>
		<tr>
			<td width="110"><span class="label">Promise Date: </span></td>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$promisedate;?></td>
			<td width="110"><span class="label">Promise Time: </span></td>
			<td width="150" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$promisetime;?></td>
		</tr>
	</table>
	</fieldset>
	<span id="divWorkClock">
	<fieldset form="form_highschool" name="form_highschool">
	<legend><p id="title">WORK CLOCK CREATION:</p></legend>
	<div >
	<table>
		<tr>
			<td width="220" style="text-align: center; font-size: 40px; color: #009900; font-weight: bold;"><? if(!empty($jobstart)){ echo dateFormat($jobstart,"F d"); } ?></td>
			<td width="220" style="text-align: center; font-size: 40px; color: #009900; font-weight: bold;"><?  if(!empty($jobend)){ echo dateFormat($jobend,"F d"); } ?></td>
		</tr>
		<tr>
			<td style="text-align: center; font-size: 40px; color: #ff0000; font-weight: bold;"><? if(!empty($jobstart)){ echo dateFormat($jobstart,"H:i"); } ?></td>
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
			<img src="images/complete.png" width="163" height="49" onClick="StopJob('<?=$worefno;?>');" style="cursor: pointer;">
			<? }if($jobstatus == '2' ){ echo '<img src="images/STOP.png" width="169" height="50"'; }?>
			</td>
			<? if($jobstart != null && $jobstart != "" && $jobstatus == 1) { ?>
			<td class ="input" style="text-align: center;">
				<a href="jobclock_idle.php?worefno=<?=$worefno;?>"><img src="images/idle2.png" width="169" height="50" style="float: left; cursor: pointer; border: none;"></a>
			</td>
			<? } ?>
		</tr>
	</table>
	</div>
    </fieldset>	
	</span>
</body>
</html>