<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:00";
		$dtto = $to . " 23:59:59";
		$dt = date("Ymdhis");

		if(empty($dtfrom) && empty($dtto)){
			$dtfrom = date("Y-m-d 00:00");
			$dtto = date("Y-m-d 23:59");
		}

		$sql = "SELECT tbl_rr_mst.*,tbl_suppliers.supplier_code FROM tbl_rr_mst
					JOIN tbl_po_mst ON tbl_po_mst.po_reference_no = tbl_rr_mst.po_reference_no
					JOIN tbl_suppliers ON tbl_suppliers.supplier_code = tbl_po_mst.supplier_code
				WHERE tbl_rr_mst.rr_date BETWEEN '$dtfrom' AND '$dtto'
 				ORDER BY tbl_rr_mst.rr_date";
		$qry = mysql_query($sql);
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
	div.divEstimateList{ height: 400px; width: 1300px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; overflow: scroll; }
	div.divEstimateList table{ border: 1px solid #ccc; font-size: 12px; }
	div.divEstimateList table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
</style>
<body>
	<p id="title">OPDN Report Result</p>
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
			<th width="150">DocType</th>
			<th width="150">DocDate</th>
			<th width="150">DocDueDate</th>
			<th width="150">CardCode</th>
			<th width="150">NumAtCard</th>
			<th width="150">DocTotal</th>
			<th width="150">JournalMemo</th>
			<th width="150">SalesPersonCode</th>
			<th width="150">TaxDate</th>
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
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['billing_date'],"m/d/Y");?></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['billing_date'],"m/d/Y");?></td>
			<td style="<?=$style;?>"><?=$row['supplier_code'];?></td>
			<td style="<?=$style;?>"></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['billing_amount'],2);?></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
		</tr>
		<? $cnt++; } ?>
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="opdnreport" />
	</form>
	<? } ?>
</body>
</html>