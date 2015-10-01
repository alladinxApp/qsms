<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$qry = " SELECT * FROM v_customer order by lastname";
	$result = $dbo->query($qry);

	$qrypaymode = "SELECT * FROM v_payment";
	$respaymode = $dbo->query($qrypaymode);

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$totalsales = 0;
		$totallabor = 0;
		$totallubricants = 0;
		$totalsublet = 0;
		$totalparts = 0;
		$totaldiscount = 0;
		$ln = null;
		$where = null;
		$ptype = $_POST['txtptype'];
		$cust = $_POST['txtcust'];
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		if(empty($dtfrom) && empty($dtto)){
			$dtfrom = date("Y-m-d 00:00");
			$dtto = date("Y-m-d 23:59");
		}

		if(!empty($cust)){
			$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$cust'";
			$rescustomer = $dbo->query($qrycustomer);

			foreach($rescustomer as $rowcustomer){
				$custname = $rowcustomer['custname'];
			}

			$where .= "AND customer_id = '$cust'";
		}

		if(!empty($ptype)){
			$qrypayment = "SELECT * FROM v_payment WHERE payment_id = '$ptype'";
			$respayment = $dbo->query($qrypayment);

			foreach($respayment as $rowpayment){
				$paymentmode = $rowpayment['payment'];
			}

			$where .= "AND payment_id = '$ptype'";
		}

		$sql_lbs_master = "SELECT * FROM v_sales 
 			WHERE 1 AND v_sales.transaction_date between '$dtfrom' AND '$dtto' $where
 			ORDER BY v_sales.transaction_date";
		$qry_lbs_master = mysql_query($sql_lbs_master);
		$qry_mst = mysql_query($sql_lbs_master);

		$estrefno = null;
		while($row_mst = mysql_fetch_array($qry_mst)){
			$estrefno .= "'$row_mst[estimate_refno]',";
		}
		$estrefno = rtrim($estrefno,",");
		
		$job = null;
		$sql_lbs_detail = "SELECT * FROM v_service_detail_job WHERE estimate_refno IN($estrefno)";
		$qry_lbs_detail = mysql_query($sql_lbs_detail);
		while($row_lbs_detail = mysql_fetch_array($qry_lbs_detail)){
			$job .= $row_lbs_detail['job_name'] . ",";
		}
		$job = rtrim($job,",");
		
		$ln .= "SALES SUMMARY REPORT\r\n\r\n";
		
		$ln .= "From: ," . $dtfrom . "\r\n";
		$ln .= "To: ," . $dtto . "\r\n";

		if(!empty($cust)){
			$ln .= "Customer: ," . $cust . "\r\n";
		}else{
			$ln .= "Customer: ,ALL\r\n";
		}
		
		if(!empty($ptype)){
			$ln .= "Payment Type: ," . $paymentmode . "\r\n";
		}else{
			$ln .= "Payment Type: ,ALL\r\n";
		}
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<? require_once('inc/datepicker.php'); ?>
</head>

<body>
	<p id="title">Sales Report</p>
	<form method="Post" onSubmit="return valMe();" action="sales_report_result.php" target="_blank">
	<div class="estimate_approval">
	<table>
		<tr>
			<td width="150">Date</td>
			<td width="10" align="center">:</td>
			<td align="center" width="20">from</td>
			<td width="125"><input type="text" id="txtdatefrom" name="txtdatefrom" readonly class="date-pick" style="width: 100"></td>
			<td align="center" width="20">to</td>
			<td width="125"><input type="text" id="txtdateto" name="txtdateto" readonly class="date-pick" style="width: 100"></td>
		</tr>
		<tr>
			<td>Customer</td>
			<td align="center">:</td>
			<td colspan="4"><select name="txtcust" id="txtcust">
				<option value="">ALL</option>
				<? foreach($result as $row){ ?>
				<option value="<?=$row['cust_id'];?>"><?=$row['custname'];?></option>
				<? } ?>
			</select></td>
		</tr>
		<tr>
			<td>Payment Type</td>
			<td align="center">:</td>
			<td colspan="4"><select name="txtptype" id="txtptype">
				<option value="">ALL</option>
				<? foreach($respaymode as $rowpaymode){ ?>
				<option value="<?=$rowpaymode['payment_id'];?>"><?=$rowpaymode['payment'];?></option>
				<? } ?>
			</select></td>
		</tr>
		<tr>
			<td >&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td colspan="3"><input type="submit" name="save" value="" style="cursor: pointer;"></td>
		</tr>
	</table>
	</div>
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="salesreport" />
	</form>
	<hr noshade style="clear: both;"/><br />
	<script type="text/javascript">
		function valMe(){
			var dtfrom = document.getElementById("txtdatefrom");
			var dtto = document.getElementById("txtdateto");
			
			if(dtfrom.value == ""){
				alert("Please enter date from!");
				return false;
			}else if(dtto.value == ""){
				alert("Please enter date to!");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>