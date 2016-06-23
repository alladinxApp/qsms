<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$date = dateFormat($_POST['txtdate'],"Y-m-d");
		
		$item = "ALL ITEMS";
		if(!empty($_POST['txtitem'])){
			$item = $_POST['txtitem'];
		}

		$dtfrom = $date . " 00:00:000";
		$dtto = $date . " 23:59:000";
		$dt = date("Ymdhis");

		$itemdesc = "ALL ITEMS";
		$itemcode = null;
		if(!empty($_POST['txtitem'])){
			$itemcode = "SAP_item_code = '$item' AND ";

			$sql_item = "SELECT * FROM tbl_items WHERE SAP_item_code = '$item'";
			$qry_item = mysql_query($sql_item);
			while($row = mysql_fetch_array($qry_item)){
				$itemdesc = $row['item_description'];
			}
		}

		$sql_inv = "SELECT * FROM v_po_inventory
				WHERE $itemcode v_po_inventory.created_date between '$dtfrom' AND '$dtto'
				ORDER BY v_po_inventory.created_date";
		$qry_inv = mysql_query($sql_inv);
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
	<p id="title">PO Inventory Report Result</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtdate" name="txtdate" value="<?=$date;?>">
	<input type="hidden" id="txtitem" name="txtitem" value="<?=!empty($_POST['txtitem']) ? $item : '';?>">
	<table width="430">
		<tr>
			<td width="120">Date</td>
			<td width="10">:</td>
			<td width="300"><?=dateFormat($date,"F d, Y");?></td>
		</tr>
		<tr>
			<td >SAP Code: </td>
			<td>:</td>
			<td ><?=$item;?></td>
		</tr>
		<tr>
			<td >Item Description: </td>
			<td>:</td>
			<td ><?=$itemdesc;?></td>
		</tr>
	</table>
	<div class="divEstimateList">
	<table width="810">
		<tr>
			<th width="10">#</th>
			<th width="100">Item Code</th>
			<th width="100">SAP Code</th>
			<th width="200">Description</th>
			<th width="100">Starting Bal</th>
			<th width="100">Received</th>
			<th width="100">Received Date</th>
			<th width="100">Issued</th>
			<th width="100">Issued Date</th>
			<th width="100">Ending Bal</th>
			<th width="100">Remarks</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_inv)){

				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$style = $bg;
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td style="<?=$style;?>"><?=$row['item_code'];?></td>
			<td style="<?=$style;?>"><?=$row['SAP_item_code'];?></td>
			<td style="<?=$style;?>"><?=$row['item_description'];?></td>
			<td style="<?=$style;?>" align="center"><?=$row['beginning_balance'];?></td>
			<td style="<?=$style;?>" align="center"><?=$row['received'];?></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['received_date'],"M d, Y");?></td>
			<td style="<?=$style;?>" align="center"><?=$row['issued'];?></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['issued_date'],"M d, Y");?></td>
			<td style="<?=$style;?>" align="center"><?=$row['ending_balance'];?></td>
			<td style="<?=$style;?>"><?=$row['remarks'];?></td>
		</tr>
		<? $cnt++; } ?>
		
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="poinventoryreport" />
	</form>
	<? } ?>
</body>
</html>