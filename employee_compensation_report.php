<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$sql = "SELECT * FROM v_employee WHERE emp_status = '1'";
	$qry = mysql_query($sql);
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<? require_once('inc/datepicker.php'); ?>
</head>

<body>
	<p id="title">Employee Compensation Report</p>
	<form method="Post" onSubmit="return valMe();" action="employee_compensation_report_result.php" target="_blank">
	<div class="estimate_approval">
	<table>
		<tr>
			<td width="150">Date</td>
			<td width="10" align="center">:</td>
			<td align="center" width="20">from</td>
			<td width="125"><input type="text" id="txtdatefrom" name="txtdatefrom" readonly class="date-pick" style="width: 100px"></td>
			<td align="center" width="20">to</td>
			<td width="125"><input type="text" id="txtdateto" name="txtdateto" readonly class="date-pick" style="width: 100px"></td>
		</tr>
		<tr>
			<td>Employee</td>
			<td align="center">:</td>
			<td colspan="4">
				<select name="txtempl" id="txtempl" style="width: 100px">
					<option value="">Please select Employee</option>
					<? while($row = mysql_fetch_array($qry)){ ?>
					<option value="<?=$row['emp_id'];?>"><?=$row['employee'];?></option>
					<? } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Work Type</td>
			<td align="center">:</td>
			<td colspan="4"><input type="text" name="txtjobtype" id="txtjobtype" style="width: 100px" /></td>
		</tr>
		<tr>
			<td>Work Order Ref#</td>
			<td align="center">:</td>
			<td colspan="4"><input type="text" name="txtworefno" id="txtworefno" style="width: 100px" /></td>
		</tr>
		<tr>
			<td >&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td colspan="3"><input type="submit" name="save" value="" style="cursor: pointer;"></td>
		</tr>
	</table>
	</div>
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="emp_compensation" />
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