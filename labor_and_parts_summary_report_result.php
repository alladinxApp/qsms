<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$totalsales = 0;
		$totallabor = 0;
		$totallubricants = 0;
		$totalsublet = 0;
		$totalparts = 0;
		$totaldiscount = 0;
		$ln = null;
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		$sql_lbs_master = "SELECT * FROM v_laborandpartssummary 
 			WHERE v_laborandpartssummary.transaction_date between '$dtfrom' AND '$dtto'
 			ORDER BY v_laborandpartssummary.transaction_date";
		$qry_lbs_master = mysql_query($sql_lbs_master);
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
	<p id="title">Labor and Parts Summary Report Result</p>
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
	<table width="1150">
		<tr>
			<th width="10">#</th>
			<th width="100">Labor</th>
			<th width="100">Lubricants</th>
			<th width="100">Sublet/Others</th>
			<th width="100">Spare Parts</th>
			<th width="100">VAT</th>
			<th width="100">Discount</th>
			<th width="100">Total</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_lbs_master)){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$totallabor += $row['labor'];
				$totallubricants += $row['lubricants'];
				$totalsublet += $row['sublet'];
				$totalparts += $row['parts'];
				$totaldiscount += $row['discount'];
				$totalsales += $row['total_amount'];
				
				$discounted = $row['subtotal_amount'] - $row['discount'];
				$vat = (($discounted / 100) * $row['vat']);
				$totalvat += $vat;
				$style = $bg;
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['labor'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['lubricants'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['sublet'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['parts'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($vat,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['discount'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['total_amount'],2);?></td>
		</tr>
		<? 
			$cnt++; } $style .= 'border-top: 3px double #000;';
		?>
		<tr>
			<td></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totallabor,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totallubricants,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalsublet,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalparts,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalvat,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totaldiscount,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalsales,2);?></td>
		</tr>
		
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="laborandpartssummaryreport" />
	</form>
	<? } ?>
</body>
</html>