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

<body>
	<p id="title">Invoice Report</p>
	<form method="Post" onSubmit="return valMe();" action="invoice_report_result.php" target="_blank">
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
			<td >&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td colspan="3"><input type="submit" name="save" value="" style="cursor: pointer;"></td>
		</tr>
	</table>
	</div>
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="invoicereport" />
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