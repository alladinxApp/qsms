<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

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

		if(!empty($job)){
			$qryjob = "SELECT * FROM v_job WHERE job_id = '$job'";
			$resjob = $dbo->query($qryjob);

			foreach($resvehicle as $rowvehicle){
				$jobname = $rowvehicle['job'];
			}

			$jobs = " AND id = '$job' ";
		}

		$sql_sh_master = "SELECT * FROM v_sales
				WHERE v_sales.transaction_date between '$dtfrom' AND '$dtto'
				$units $customers $jobs
				ORDER BY v_sales.transaction_date";
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
	div.divEstimateList{ height: 400px; width: 610px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.divEstimateList table{ border: 1px solid #ccc; font-size: 12px; }
	div.divEstimateList table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
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
			<td width="300"><?=dateFormat($from,"F d, Y");?></td>
		</tr>
		<tr>
			<td >Date To </td>
			<td>:</td>
			<td ><?=dateFormat($to,"F d, Y");?></td>
		</tr>
		<tr>
			<td >Customer </td>
			<td>:</td>
			<td ><? if(!empty($cust)){ echo $custname; }else{ echo 'ALL'; } ?></td>
		</tr>
		<tr>
			<td >Plate No </td>
			<td>:</td>
			<td ><? if(!empty($unit)){ echo $plateno; }else{ echo 'ALL'; } ?></td>
		</tr>
		<tr>
			<td >Service Type: </td>
			<td>:</td>
			<td ><? if(!empty($job)){ echo $jobname; }else{ echo 'ALL'; } ?></td>
		</tr>
	</table>
	<div class="divEstimateList">
	<table width="710px">
		<tr>
			<th width="10">#</th>
			<th width="100">Plate No</th>
			<th width="200">Service Remarks</th>
			<th width="100">Date</th>
			<th width="300">Customer</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_sh_master)){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$style = $bg;
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td style="<?=$style;?>"><?=$row['plate_no'];?></td>
			<td style="<?=$style;?>"><?=$row['remarks'];?></td>
			<td style="<?=$style;?>" align="center"><?=dateFormat($row['transaction_date'],"m/d/Y");?></td>
			<td style="<?=$style;?>"><?=$row['customername'];?></td>
		</tr>
		<? $cnt++; } ?>
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="repairhistory" />
	</form>
	<? } ?>
</body>
</html>