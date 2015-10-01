<?
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$year = $_POST['txtyear'];
		$totalunits = 0;
		$totaljan = 0;
		$totalfeb = 0;
		$totalmar = 0;
		$totalapr = 0;
		$totalmay = 0;
		$totaljun = 0;
		$totaljul = 0;
		$totalaug = 0;
		$totalsep = 0;
		$totaloct = 0;
		$totalnov = 0;
		$totaldec = 0;
		$ln = null;
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		$sql_vs_master = "SELECT * FROM v_vehiclesummary 
				WHERE v_vehiclesummary.year = '$year'";
		$qry_vs_master = mysql_query($sql_vs_master);
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
	<p id="title">Vehicle Summary Report</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtyear" name="txtyear" value="<?=$year;?>">
	<div class="divEstimateList">
	<table width="1210">
		<tr>
			<th width="10">#</th>
			<th width="100">January</th>
			<th width="100">February</th>
			<th width="100">March</th>
			<th width="100">April</th>
			<th width="100">May</th>
			<th width="100">June</th>
			<th width="100">July</th>
			<th width="100">August</th>
			<th width="100">September</th>
			<th width="100">October</th>
			<th width="100">November</th>
			<th width="100">December</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_vs_master)){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$totalsales += $row['total_amount'];

				$style = $bg;

				$totaljan += $row['january'];
				$totalfeb += $row['february'];
				$totalmar += $row['march'];
				$totalapr += $row['april'];
				$totalmay += $row['may'];
				$totaljun += $row['june'];
				$totaljul += $row['july'];
				$totalaug += $row['august'];
				$totalsep += $row['september'];
				$totaloct += $row['october'];
				$totalnov += $row['november'];
				$totaldec += $row['december'];
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td align="center" style="<?=$style;?>"><?=$row['january'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['february'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['march'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['april'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['may'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['june'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['july'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['august'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['september'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['october'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['november'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['december'];?></td>
		</tr>
		<? 
				$cnt++; 
			} 

			$style .= 'border-top: 3px double #000; background: #fff;';

			$totalunits = $totaljan + $totalfeb + $totalmar + $totalapr + $totalmay + $totaljun
								 + $totaljul + $totalaug + $totalsep + $totaloct + $totalnov + $totaldec;
		 ?>
		<tr>
			<td></td>
			<td align="right" colspan="11" style="<?=$style;?>">Total Units>>>></td>
			<td align="center" style="<?=$style;?>"><?=$totalunits;?> Units</td>
		</tr>
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="vehiclesummaryreport" />
	</form>
	<? } ?>
</body>
</html>