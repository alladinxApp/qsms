<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$qrycust = " SELECT * FROM v_customer order by lastname";
	$rescust = $dbo->query($qrycust);

	$qryunits = "SELECT * FROM v_vehicleinfo order by vehicle_id";
	$resunits = $dbo->query($qryunits);

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$cust = $_POST['txtcust'];
		$unit = $_POST['txtunits'];
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		$totalsales = 0;
		$cnt = 0;
		$ave_sales = 0;

		if(!empty($cust)){
			$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$cust'";
			$rescustomer = $dbo->query($qrycustomer);

			foreach($rescustomer as $rowcustomer){
				$custname = $rowcustomer['custname'];
			}

			$customers = " AND customer_id = '$cust' ";
		}
		
		if(!empty($unit)){
			$qryvehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$unit'";
			$resvehicle = $dbo->query($qryvehicle);

			foreach($resvehicle as $rowvehicle){
				$plateno = $rowvehicle['plate_no'];
			}

			$units = " AND vehicle_id = '$unit' ";
		}

		$sql_spu_master = "SELECT * FROM v_salesperunit 
				WHERE v_salesperunit.transaction_date between '$dtfrom' AND '$dtto'
				$units $customers
				ORDER BY v_salesperunit.transaction_date";
		$qry_spu_master = mysql_query($sql_spu_master);
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<? require_once('inc/datepicker.php'); ?>
</head>

<body>
	<p id="title">Sales per Unit Report</p>
	<form method="Post" onSubmit="return valMe();" action="sales_per_unit_report_result.php" target="_blank">
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
			<td >&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td colspan="3"><input type="submit" name="save" value="" style="cursor: pointer;"></td>
		</tr>
	</table>
	</div>
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="salesperunitreport" />
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