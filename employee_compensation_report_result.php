<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$empl = $_POST['txtempl'];
		$jobtype = $_POST['txtjobtype'];
		$worefno = $_POST['txtworefno'];
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		if(!empty($empl)){
			$sql_emp = "SELECT * FROM v_employee WHERE emp_id = '$empl'";
			$qry_emp = mysql_query($sql_emp);
			while($row_emp = mysql_fetch_array($qry_emp)){
				$emp_name = $row_emp['employee'];
			}
		}

		if(empty($dtfrom) && empty($dtto)){
			$dtfrom = date("Y-m-d 00:00");
			$dtto = date("Y-m-d 23:59");
		}

		$where = null;

		if(!empty($empl)){
			$where .= " AND emp_id = '$empl' ";
		}

		if(!empty($jobtype)){
			$where .= " AND jobname LIKE '%$jobtype%' ";
		}

		if(!empty($worefno)){
			$where .= " AND workorder_refno LIKE '%$worefno%' ";
		}

		$sql_emplcompensation = "SELECT * FROM v_employeecompensation
 			WHERE 1 AND v_employeecompensation.transaction_date between '$dtfrom' AND '$dtto' $where
 			ORDER BY v_employeecompensation.transaction_date";
		$qry_emplcompensation = mysql_query($sql_emplcompensation);
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
	<p id="title">Employee Compensation Report Result</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtdatefrom" name="txtdatefrom" value="<?=$from;?>">
	<input type="hidden" id="txtdateto" name="txtdateto" value="<?=$to;?>">
	<input type="hidden" id="txtjobtype" name="txtjobtype" value="<?=$jobtype;?>">
	<input type="hidden" id="txtempl" name="txtempl" value="<?=$empl;?>">
	<input type="hidden" id="txtworefno" name="txtworefno" value="<?=$worefno;?>">
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
			<td >Employee </td>
			<td>:</td>
			<td ><?=$emp_name;?></td>
		</tr>
		<tr>
			<td >Work Type </td>
			<td>:</td>
			<td ><?=$jobtype;?></td>
		</tr>
		<tr>
			<td >Work Order Ref# </td>
			<td>:</td>
			<td ><?=$worefno;?></td>
		</tr>
	</table>
	<div class="divEstimateList">
	<table width="610">
		<tr>
			<th width="10">#</th>
			<th width="200">Employee Name</th>
			<th width="100">Transaction Date</th>
			<th width="100">Work Order Ref#</th>
			<th width="100">Amount</th>
			<th width="100">Work Type</th>
		</tr>
		<?
			$cnt = 1; 
			$total = 0;
			while($row = mysql_fetch_array($qry_emplcompensation)){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$style = $bg;
				$total += $row['amount'];
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td style="<?=$style;?>"><?=$row['tech_name'];?></td>
			<td style="<?=$style;?>"><?=dateFormat($row['transaction_date'],"Y-m-d");?></td>
			<td style="<?=$style;?>"><?=$row['workorder_refno'];?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['amount'],2);?></td>
			<td style="<?=$style;?>"><?=$row['jobname'];?></td>
		</tr>
		<? $cnt++; }?>

		<tr>
			<td align="right" colspan="4" >Total >>>>></td>
			<td align="right" style="border-top: 3px double #000;"><?=number_format($total,2);?></td>
			<td>&nbsp;</td>
		</tr>
		
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="employee_compensation" />
	</form>
	<? } ?>
</body>
</html>