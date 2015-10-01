<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$qryservices = "SELECT * FROM v_service_master";
	$resservices = $dbo->query($qryservices);
?>
<fieldset form="form_estimate_list" name="form_estimate_list">
<p id="title">Estimate LIST</p>
<div class="divEstimateList">
<table width="2200">
	<tr>
		<th width="10"></th>
		<th width="20">#</th>
		<th width="150">Estimate Reference No</th>
		<th width="160">Work Order Reference No</th>
		<th width="150">Transaction Date</th>
		<th width="200">Customer Name</th>
		<th width="100">Plate No</th>
		<th width="100">Make</th>
		<th width="50">Year</th>
		<th width="100">Model</th>
		<th width="100">Color</th>
		<th width="200">Variant</th>
		<th width="200">Engine No</th>
		<th width="200">Chassis No</th>
		<th width="200">Payment Mode</th>
		<th width="100">Total Amount</th>
		<th width="100">Status</th>
	</tr>
</table>
</div>
</fieldset>