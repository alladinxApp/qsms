<?
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$qryyr = "SELECT * FROM v_year";
	$resyr = $dbo->query($qryyr);

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
<body>
	<p id="title">Customer Summary Report</p>
	<form method="Post" action="customer_summary_report_result.php" target="_blank">
	<div class="estimate_approval">
	<table>
		<tr>
			<td width="150">Year</td>
			<td width="10" align="center">:</td>
			<td width="125"><select name="txtyear" id="txtyear">
				
				<option value="<?=date("Y") - 5;?>"><?=date("Y") - 5;?></option>
				<option value="<?=date("Y") - 4;?>"><?=date("Y") - 4;?></option>
				<option value="<?=date("Y") - 3;?>"><?=date("Y") - 3;?></option>
				<option value="<?=date("Y") - 2;?>"><?=date("Y") - 2;?></option>
				<option value="<?=date("Y") - 1;?>"><?=date("Y") - 1;?></option>
				<option selected value="<?=date("Y");?>"><?=date("Y");?></option>
				
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
	<input type="hidden" id="report" name="report" value="customersummaryreport" />
	</form>
	<hr noshade style="clear: both;"/><br />
</body>
</html>