<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		if(empty($dtfrom) && empty($dtto)){
			$dtfrom = date("Y-m-d 00:00");
			$dtto = date("Y-m-d 23:59");
		}

		$sql = "SELECT tbl_rr_dtl.*,tbl_items.SAP_item_code,tbl_items.item_description FROM tbl_rr_dtl
					JOIN tbl_rr_mst ON tbl_rr_mst.rr_reference_no = tbl_rr_dtl.rr_reference_no
					JOIN tbl_items ON tbl_items.item_code = tbl_rr_dtl.item_code
	 			WHERE 1 AND tbl_rr_dtl.rr_date between '$dtfrom' AND '$dtto' $where
	 			ORDER BY tbl_rr_dtl.rr_date";
		$qry = mysql_query($sql);

		$ln .= "PDN REPORT\r\n\r\n";
		
		$ln .= "From: ," . $dtfrom . "\r\n";
		$ln .= "To: ," . $dtto . "\r\n";
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
	<p id="title">PDN Report Result</p>
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
	<table width="1350">
		<tr>
			<th width="10">#</th>
			<th width="150">LineNum</th>
			<th width="150">ItemCode</th>
			<th width="150">ItemDescription</th>
			<th width="150">Quantity</th>
			<th width="150">Price</th>
			<th width="150">WarehouseCode</th>
			<th width="150">ProjectCode</th>
			<th width="150">VatGroup</th>
			<th width="150">COGSCostingCode2</th>
			<th width="150">COGSCostingCode3</th>
			<th width="150">COGSCostingCode4</th>
			<th width="150">COGSCostingCode5</th>
		</tr>
		<? 
			$cnt = 1; 
			while($row = mysql_fetch_array($qry)){ 
				$bg = null;
				if($cnt%2){
					$bg = 'background: #eee;';
				}
				$style = $bg;
		?>
		<tr>
			<td align="center" style="<?=$style;?>"><?=$cnt;?></td>
			<td style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"><?=$row['SAP_item_code'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['item_description'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['quantity'];?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['price'],2);?></td>
			<td align="center" style="<?=$style;?>">P2</td>
			<td align="center" style="<?=$style;?>">SUC</td>
			<td align="center" style="<?=$style;?>">V4</td>
			<td align="center" style="<?=$style;?>">PO1</td>
			<td align="center" style="<?=$style;?>">SUC</td>
			<td align="center" style="<?=$style;?>">Z10</td>
		</tr>
		<? $cnt++; } ?>		
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="pdnreport" />
	</form>
	<? } ?>
</body>
</html>