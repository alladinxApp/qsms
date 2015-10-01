<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");

	$qry = " SELECT * FROM v_customer order by lastname";
	$result = $dbo->query($qry);

	$qry1 = " SELECT * FROM v_customer order by lastname";
	$result1 = $dbo->query($qry1);
	
	$qrymdl = "SELECT * FROM v_model ORDER BY model";
	$resmdl = $dbo->query($qrymdl);
	
	$qryyr = "SELECT * FROM v_year ORDER BY year DESC";
	$resyr = $dbo->query($qryyr);
	
	$qrymke = "SELECT * FROM v_make ORDER BY make";
	$resmke = $dbo->query($qrymke);
	
	$qryclr = "SELECT * FROM v_color ORDER BY color";
	$resclr = $dbo->query($qryclr);
?>
<table>
	<tr>
		<td class ="label"><label name="lbl_vehicle_id">Vehicle Code:</label></td>
		<td class ="input"><input type="text" name="vehicle_id" value="[SYSTEM GENERATED]" readonly style="width:235px"></td>
		<td></td>
		<td class ="label"><label name="lbl_plateno">Conduction Sticker:</label></td>
		<td class ="input"><input type="text" name="conductionsticker" id="conductionsticker" value="" style="width:100px"></td>
	</tr>
	<tr>
		<td class ="label"><label name="lbl_plateno">Plate Number:</label></td>
		<td class ="input"><input type="text" name="plateno" id="plateno" value="" style="width:235px"></td>
		<td></td>
		<td class ="label"><label name="lbl_year">Year:</label></td>
		<td class ="input"><select name="year" id="year" style="width: 100px;">
			<option value=""></option>
			<? foreach($resyr as $rowyr){ ?>
			<option value="<?=$rowyr['year_id'];?>"><?=$rowyr['year'];?></option>
			<? } ?>
		</select></td>
		<td></td>
		<td class ="label"><label name="lbl_make">Make:</label></td>
		<td class ="input"><select name="make" id="make" style="width: 150px;">
			<option value=""></option>
			<? foreach($resmke as $rowmke){ ?>
			<option value="<?=$rowmke['make_id'];?>"><?=$rowmke['make'];?></option>
			<? } ?>
		</select></td>
	</tr>		
	<tr>
		<td class ="label"><label name="lbl_model">Model:</label></td>
		<td class ="input"><select name="model" id="model" style="width: 235px;" onChange="getModelVariant(this.value);">
			<option value=""></option>
			<? foreach($resmdl as $rowmdl){ ?>
			<option value="<?=$rowmdl['model_id'];?>"><?=$rowmdl['model'];?></option>
			<? } ?>
		</select></td>
		<td></td>
		<td class ="label"><label name="lbl_color">Color:</label></td>
		<td class ="input"><select name="color" id="color" style="width: 100px;">
			<option value=""></option>
			<? foreach($resclr as $rowclr){ ?>
			<option value="<?=$rowclr['color_id'];?>"><?=$rowclr['color'];?></option>
			<? } ?>
		</select></td>
		<td></td>
			<td class ="label"><label name="lbl_variant">Variant:</label></td>
		<td class ="input"><span id="divModelVariant"><input type="text" name="variant" id="variant" readonly value="" style="width:200px" id=""></span></td> 
	</tr>
	<tr>
		<td class ="label"><label name="lbl_engine_no">Engine Number:</label></td>
		<td class ="input"><input type="text" name="engineno" id="engineno" value="" style="width:235px" id=""></td>
		<td></td>
		<td class ="label"><label name="lbl_color">Chassis Number</label></td>
		<td class ="input"><input type="text" name="chassisno" id="chassisno" value="" style="width:200px"></td>
		<td></td>
		<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
		<td class ="input"><input type="text" name="serialno" id="serialno" value="" style="width:118px" id=""></td>
	</tr>
	<tr>
		<td class ="label"><label name="lbl_vehicledescription">Odometer:</label></td>
		<td class ="input"><input type="text" name="odometer" id="odometer" value="" style="width:235px" id=""></td>
	</tr>
</table>
<input type="hidden" name="vehicleType" id="vehicleType" value="0" />