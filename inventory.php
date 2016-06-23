<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$sql_items = "SELECT * FROM tbl_items WHERE status = '1'";
	$qry_items = mysql_query($sql_items);
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<? require_once('inc/datepicker.php'); ?>
</head>

<body>
	<p id="title">Inventory Report</p>
	<form method="Post" onSubmit="return valMe();" action="inventory_report_result.php" target="_blank">
	<div class="estimate_approval">
	<table>
		<tr>
			<td width="150">Date</td>
			<td width="10" align="center">:</td>
			<td width="125"><input type="text" id="txtdate" name="txtdate" readonly class="date-pick" style="width: 100"></td>
		</tr>
		<tr>
			<td width="150">Item / SAP Code</td>
			<td width="10" align="center">:</td>
			<td align="center" width="125"><select name="txtitem" id="txtitem">
				<option value="">All Items</option>
				<? while($row = mysql_fetch_array($qry_items)) {?>
				<option value="<?=$row['SAP_item_code'];?>"><?=$row['SAP_item_code'] . " - " . $row['item_description'];?></option>
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