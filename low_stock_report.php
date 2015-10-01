<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$sql_ls = "SELECT * FROM v_lowstock WHERE is_low > 0";
	$qry_ls = mysql_query($sql_ls);
	$num_ls = mysql_num_rows($qry_ls);
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
	<p id="title">Low Stock Report</p>
	<form method="Post" action="export.php" target="_blank">
	<div class="divEstimateList">
	<table width="710">
		<tr>
			<th width="10">#</th>
			<th width="100">Parts Code</th>
			<th width="300">Description</th>
			<th width="100">Low Stock Qty</th>
			<th width="100">Stock On Hand</th>
			<th width="100">Received parts</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_ls)){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$style = $bg;
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td style="<?=$style;?>"><?=$row['parts_id'];?></td>
			<td style="<?=$style;?>"><?=$row['parts'];?></td>
			<td align="right" style="<?=$style;?>"><?=$row['parts_lowstock'];?></td>
			<td align="right" style="<?=$style;?>"><?=$row['part_onhand'];?></td>
			<td align="center" style="<?=$style;?>"><a href="receive_parts.php?partsid=<?=$row['parts_id'];?>"><img src="images/edit.png" border="0" width="30" /></a></td>
		</tr>
		<? $cnt++; }?>
		
	</table>
	</div>
	<? if($num_ls > 0){ ?>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<? } ?>
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="low_stock" />
	</form>
</body>
</html>