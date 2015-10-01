<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$qrycust = " SELECT * FROM v_customer order by lastname";
	$rescust = $dbo->query($qrycust);

	$qryunits = "SELECT * FROM v_vehicleinfo order by vehicle_id";
	$resunits = $dbo->query($qryunits);

	$qryjob = "SELECT * FROM v_job order by job";
	$resjob = $dbo->query($qryjob);
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<? require_once('inc/datepicker.php'); ?>
</head>

<body>
	<p id="title">History Report</p>
	<form method="Post" onSubmit="return valMe();" action="history_report_result.php" target="_blank">
	<div class="estimate_approval">
	<table>
		<tr>
			<td width="150">Date</td>
			<td width="10" align="center">:</td>
			<td align="center" width="20">from</td>
			<td width="125"><input type="text" id="txtdatefrom" name="txtdatefrom" readonly class="date-pick" style="width: 100"></td>
			<td align="center" width="20">to</td>
			<td width="125"><input type="text" id="txtdateto" name="txtdateto" readonly class="date-pick" style="width: 100"></td>
		</tr>
		<tr>
			<td>Customer</td>
			<td align="center">:</td>
			<td colspan="4"><select name="txtcust" id="txtcust">
				<option value="">ALL</option>
				<? foreach($rescust as $rowcust){ ?>
				<option value="<?=$rowcust['cust_id'];?>"><?=$rowcust['custname'];?></option>
				<? } ?>
			</select></td>
		</tr>
		<tr>
			<td>Plate No</td>
			<td align="center">:</td>
			<td colspan="4"><select name="txtunits" id="txtunits">
				<option value="">ALL</option>
				<? foreach($resunits as $rowunits){ ?>
				<option value="<?=$rowunits['vehicle_id'];?>"><?=$rowunits['plate_no'];?></option>
				<? } ?>
			</select></td>
		</tr>
		<tr>
			<td>Service Type</td>
			<td align="center">:</td>
			<td colspan="4"><select name="txtjob" id="txtjob">
				<option value="">ALL</option>
				<? foreach($resjob as $rowjob){ ?>
				<option value="<?=$rowjob['job_id'];?>"><?=$rowjob['job'];?></option>
				<? } ?>
			</select></td>
		</tr>
		<tr>
			<td >&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td colspan="3"><input type="submit" name="save" value="" style="cursor: pointer;"></td>
		</tr>
	</table>
	</div>
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="historyreport" />
	</form>
	<hr noshade style="clear: both;"/><br />
	<script type="text/javascript">
		function valMe(){
			var dtfrom = document.getElementById("txtdatefrom");
			var dtto = document.getElementById("txtdateto");
			
			if(dtfrom.value == ""){
				alert("Please enter date from!");
				return false;
			}else if(dtto.value == ""){
				alert("Please enter date to!");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>