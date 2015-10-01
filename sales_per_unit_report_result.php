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
<style>
	div.divEstimateList{ height: 400px; width: 800px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.divEstimateList table{ border: 1px solid #ccc; font-size: 12px; }
	div.divEstimateList table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
</style>
<body>
	<p id="title">Sales per Unit Report Result</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtdatefrom" name="txtdatefrom" value="<?=$from;?>">
	<input type="hidden" id="txtdateto" name="txtdateto" value="<?=$to;?>">
	<input type="hidden" id="txtplateno" name="txtplateno" value="<?=$unit;?>">
	<input type="hidden" id="txtcust" name="txtcust" value="<?=$cust;?>">
	<table width="100%">
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
	</table>
	<div class="divEstimateList">
	<table width="310">
		<tr>
			<th width="10">#</th>
			<th width="100">Plate No</th>
			<th width="150">Work Order Ref. No</th>
			<th width="100">Amount</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_spu_master)){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$totalsales += $row['total_amount'];

				$style = $bg;
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td style="<?=$style;?>"><?=$row['plate_no'];?></td>
			<td style="<?=$style;?>"><?=$row['wo_refno'];?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['total_amount'],2);?></td>
		</tr>
		<? $cnt++; } $style .= 'border-top: 3px double #000; background: #fff;'; $totalunits = $cnt - 1; $ave_sales = $totalsales / $totalunits; ?>
		<tr>
			<td align="right" colspan="3" style="<?=$style;?> border: 0;">Total Sales >>>>></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalsales,2);?></td>
		</tr>
		<tr>
			<td align="right" colspan="3" style="<?=$style;?> border: 0;">Average Sales >>>>></td>
			<td align="right" style="<?=$style;?> border: 0;"><?=number_format($ave_sales,2);?></td>
		</tr>
		<tr>
			<td align="right" colspan="3" style="<?=$style;?> border: 0;">Total Units >>>>></td>
			<td align="right" style="<?=$style;?> border: 0;"><?=$totalunits;?></td>
		</tr>
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="salesperunitreport" />
	</form>
	<? } ?>
</body>
</html>