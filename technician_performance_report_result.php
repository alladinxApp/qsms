<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$tech = $_POST['txttech'];
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		$totalsales = 0;
		$cnt = 0;
		$ave_sales = 0;

		if(!empty($tech)){
			$qrytech = "SELECT * FROM v_employee where emp_id = '$tech'";
			$restech = $dbo->query($qrytech);

			foreach($restech as $rowtech){
				$empname = $rowtech['employee'];
			}

			$emp = " AND emp_id = '$tech' ";
		}

		$sql_tp_master = "SELECT * FROM v_technician_performance
				WHERE v_technician_performance.transaction_date between '$dtfrom' AND '$dtto'
				$emp
				ORDER BY v_technician_performance.transaction_date";
		$qry_tp_master = mysql_query($sql_tp_master);
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
	<p id="title">Technician Performance Report Result</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtdatefrom" name="txtdatefrom" value="<?=$from;?>">
	<input type="hidden" id="txtdateto" name="txtdateto" value="<?=$to;?>">
	<input type="hidden" id="txttech" name="txttech" value="<?=$tech;?>">
	<table width="430">
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
			<td >Technician: </td>
			<td>:</td>
			<td ><? if(!empty($tech)){ echo $empname; }else{ echo 'ALL'; } ?></td>
		</tr>
	</table>
	<div class="divEstimateList">
	<table width="810">
		<tr>
			<th width="10">#</th>
			<th width="150">Technician</th>
			<th width="150">Work Order Ref. No</th>
			<th width="150">Committed Date</th>
			<th width="150">Completion Date</th>
			<th width="100">Hit/Miss</th>
			<th width="100">No Of Hours</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_tp_master)){
				$jcmsttot_hrs = 0;

				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$style = $bg;
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td style="<?=$style;?>"><?=$row['tech_name'];?></td>
			<td style="<?=$style;?>"><?=$row['wo_refno'];?></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['committed_date'],"M d, Y");?></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['completion_date'],"M d, Y");?></td>
			<?
				if($row['committed_date'] < $row['completion_date']){
					$hit_miss = 'MISSED';
				}else{
					$hit_miss = 'HIT';
				}
				$sql_jcdtl = "SELECT * FROM v_jobclock_detail WHERE wo_refno = '$row[wo_refno]'";
				$qry_jcdtl = mysql_query($sql_jcdtl);

				$jcmsttot_hrs = (strtotime($row['job_end']) - strtotime($row['job_start']));

				$jcdtltot_hrs = 0;
				while($row_jcdtl = mysql_fetch_array($qry_jcdtl)){
					$jcdtltot_hrs += (strtotime($row_jcdtl['time_end']) - strtotime($row_jcdtl['time_start']));
				}

				$noofhrsmst = (($jcmsttot_hrs / 60) / 60);
				$noofhrsdtl = (($jcdtltot_hrs / 60) / 60);

				$totalnoofhrs = ($noofhrsmst - $noofhrsdtl);
			?>
			<td align="center" style="<?=$style;?>"><?=$hit_miss;?></td>
			<td align="center" style="<?=$style;?>"><?=number_format($totalnoofhrs,2);?></td>
		</tr>
		<? $cnt++; } $style .= 'border-top: 3px double #000; background: #fff;'; $totalunits = $cnt - 1; $ave_sales = $totalsales / $totalunits; ?>
		
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="technicianperformancereport" />
	</form>
	<? } ?>
</body>
</html>