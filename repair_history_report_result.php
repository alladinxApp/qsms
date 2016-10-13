<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$sql_customer = "SELECT * FROM tbl_customer";
	$qry_customer = mysql_query($sql_customer);

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$cust = $_POST['txtcust'];
		$unit = $_POST['txtunits'];
		$job = $_POST['txtjob'];
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		$totalsales = 0;
		$cnt = 0;
		$ave_sales = 0;

		// if(!empty($cust)){
		// 	$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$cust'";
		// 	$rescustomer = $dbo->query($qrycustomer);

		// 	foreach($rescustomer as $rowcustomer){
		// 		$custname = $rowcustomer['custname'];
		// 	}

		// 	$customers = " AND customer_id = '$cust' ";
		// }
		
		// if(!empty($unit)){
		// 	$qryvehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$unit'";
		// 	$resvehicle = $dbo->query($qryvehicle);

		// 	foreach($resvehicle as $rowvehicle){
		// 		$plateno = $rowvehicle['plate_no'];
		// 	}

		// 	$units = " AND vehicle_id = '$unit' ";
		// }

		$sql_sh_master = "SELECT
					tbl_service_master.estimate_refno   AS estimate_refno,
					tbl_service_master.wo_refno         AS wo_refno,
					tbl_service_master.transaction_date AS transaction_date,
					tbl_service_master.customer_id      AS customer_id,
					CONCAT(tbl_customer.firstname,' ',tbl_customer.middlename,' ',tbl_customer.lastname) AS customername,
					tbl_vehicleinfo.plate_no            AS plate_no,
					tbl_year.year                       AS year,
					tbl_make.make                       AS make,
					tbl_model.model                     AS model,
					tbl_service_master.odometer         AS odometer,
					tbl_service_master.total_amount     AS total_amount
				FROM tbl_service_master
					JOIN tbl_vehicleinfo
						ON tbl_vehicleinfo.vehicle_id = tbl_service_master.vehicle_id
					JOIN tbl_year
						ON tbl_year.year_id = tbl_vehicleinfo.year
					JOIN tbl_make
						ON tbl_make.make_id = tbl_vehicleinfo.make
					JOIN tbl_model
						ON tbl_model.model_id = tbl_vehicleinfo.model
					JOIN tbl_customer
						ON tbl_customer.cust_id = tbl_service_master.customer_id
					JOIN tbl_billing
						ON tbl_billing.wo_refno = tbl_service_master.wo_refno
				WHERE tbl_service_master.transaction_date between '$dtfrom' AND '$dtto'
				$units $customers
				ORDER BY tbl_service_master.transaction_date DESC";

		$qry_sh_master = mysql_query($sql_sh_master);
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<? require_once('inc/datepicker.php'); ?>
</head>
<style>
	div.divEstimateList{ width: 710px; }
</style>
<body>
	<p id="title">Repair History Report Result</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtdatefrom" name="txtdatefrom" value="<?=$from;?>">
	<input type="hidden" id="txtdateto" name="txtdateto" value="<?=$to;?>">
	<input type="hidden" id="txtplateno" name="txtplateno" value="<?=$unit;?>">
	<input type="hidden" id="txtcust" name="txtcust" value="<?=$cust;?>">
	<input type="hidden" id="txtjob" name="txtjob" value="<?=$job;?>">
	<table width="500px">
		<tr>
			<td width="120">Date From </td>
			<td width="10">:</td>
			<td width="300"><? //=dateFormat($from,"F d, Y");?></td>
		</tr>
		<tr>
			<td >Date To </td>
			<td>:</td>
			<td ><? //=dateFormat($to,"F d, Y");?></td>
		</tr>
		<tr>
			<td >Customer </td>
			<td>:</td>
			<td ><? //if(!empty($cust)){ echo $custname; }else{ echo 'ALL'; } ?></td>
		</tr>
		<tr>
			<td >Plate No </td>
			<td>:</td>
			<td ><? //if(!empty($unit)){ echo $plateno; }else{ echo 'ALL'; } ?></td>
		</tr>
		<tr>
			<td >Service Type: </td>
			<td>:</td>
			<td ><? //if(!empty($job)){ echo $jobname; }else{ echo 'ALL'; } ?></td>
		</tr>
	</table>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<div class="divEstimateList">
	<?
		while($row_customer = mysql_fetch_array($qry_customer)){
			$custid = $row_customer['customer_id'];
			$custname = $row_customer['firstname'] . ' ' . $row_customer['middlename'] . ' ' . $row_customer['lastname'];
			while($row_history = mysql_fetch_array($qry_sh_master)){
				if($row_history['customer_id'] == $row_customer['cust_id']){
	?>
	<div style="width: 710px; font-size: 20px; font-weight: bold;" align="right"><?=$custname;?></div>
	<table width="710px">
		
	</table>
	<? 	} 	} 	} ?>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="repairhistory" />
	</form>
	<? } ?>
</body>
</html>