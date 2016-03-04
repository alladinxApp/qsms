<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		
		$ln = null;
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		$sql_invoice = "SELECT * FROM v_invoice 
 			WHERE v_invoice.billing_date between '$dtfrom' AND '$dtto'
 			ORDER BY v_invoice.billing_date";
		$qry_invoice = mysql_query($sql_invoice);
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
	<p id="title">Invoice Report Result</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtdatefrom" name="txtdatefrom" value="<?=$from;?>">
	<input type="hidden" id="txtdateto" name="txtdateto" value="<?=$to;?>">
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
	</table>
	<div class="divEstimateList">
	<table width="1600">
		<tr>
			<th width="10">#</th>
			<th width="100">Posting Date</th>
			<th width="100">Billing Ref#</th>
			<th width="100">Customer Code</th>
			<th width="200">Customer Name</th>
			<th width="100">Item#</th>
			<th width="140">Item Desc</th>
			<th width="100">Unit Price</th>
			<th width="100">Gross Total</th>
			<th width="100">Plate No</th>
			<th width="100">Conduction Sticker</th>
			<th width="100">Make</th>
			<th width="100">Color</th>
			<th width="100">Year</th>
			<th width="150">Variants</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_invoice)){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$style = $bg;
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['billing_date'],"F d Y");?></td>
			<td style="<?=$style;?>"><?=$row['billing_refno'];?></td>
			<td style="<?=$style;?>"><?=$row['customer_id'];?></td>
			<td style="<?=$style;?>"><?=$row['custname'];?></td>
			<td style="<?=$style;?>"><?=$row['parts_id'];?></td>
			<td style="<?=$style;?>"><?=$row['parts'];?></td>
			<td align="right" style="<?=$style;?>"><?=$row['amount'];?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['gross_total'],2);?></td>
			<td align="center" style="<?=$style;?>"><?=$row['plate_no'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['conduction_sticker'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['make'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['color'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['yeardesc'];?></td>
			<td style="<?=$style;?>"><?=$row['variant'];?></td>
		</tr>
		<? $cnt++; } ?>
		
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="invoicereport" />
	</form>
	<? } ?>
</body>
</html>