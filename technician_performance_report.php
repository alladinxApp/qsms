<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$qrytech = "SELECT * FROM v_employee";
	$restech = $dbo->query($qrytech);
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<? require_once('inc/datepicker.php'); ?>
</head>
<body>
	<p id="title">Technician Performance Report</p>
	<form method="Post" action="technician_performance_report_result.php" onSubmit="return valMe();" target="_blank">
	<div class="estimate_approval">
	<table>
		<tr>
			<td width="150">Date</td>
			<td width="10" align="center">:</td>
			<td align="center" width="20"><div align="left">from</div></td>
			<td width="125"><input type="text" id="txtdatefrom" name="txtdatefrom" readonly class="date-pick" style="width: 100"></td>
			<td align="center" width="20">to</td>
			<td width="125"><input type="text" id="txtdateto" name="txtdateto" readonly class="date-pick" style="width: 100"></td>
		</tr>
		<tr>
			<td width="150">Technician</td>
			<td width="10" align="center">:</td>
			<td align="center" width="20"><select name="txttech" id="txttech">
				<option value="">All Technician</option>
				<? foreach($restech as $Tech){?>
				<option value="<?=$Tech['emp_id'];?>"><?=$Tech['employee'];?></option>
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
	<input type="hidden" id="report" name="report" value="technicianperformance" />
	</form>
	<hr noshade style="clear: both;"/><br />
</form>
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