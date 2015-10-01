<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$cust_id = $_GET['custid'];
	
	$qryplateno = "SELECT * FROM v_vehicleinfo WHERE customer_id = '$cust_id'";
	$resplateno = mysql_query($qryplateno);
?>
<table>
		<tr>
			<td class ="label"><label name="lbl_plateno">Plate Number:</label></td>
			<td class ="input"><span id="divPlateNos">
				<select name="plateno" id="plateno" onchange="return getCustVehicleInfo(this.value);" style="width: 235px;">
					<option value=""></option>
					<? while($row = mysql_fetch_array($resplateno)) { ?>
					<option value="<?=$row['vehicle_id'];?>"><?=$row['plate_no'];?></option>
					<? }?>
				</select>
			</span></td>
			<td></td>
			<td class ="label"><label name="lbl_year">Year:</label></td>
			<td class ="input"><select name="year" id="year" style="width: 100px;">
				<option value=""></option>
				<? 
					foreach($resyr as $rowyr){ 
						if($rowyr['year_id'] == $year){
							$selectedyr = 'selected';
						}else{
							$selectedyr = null;
						}
				?>
				<option value="<?=$rowyr['year_id'];?>" <?=$selectedyr;?>><?=$rowyr['year'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_make">Make:</label></td>
			<td class ="input"><select name="make" id="make" style="width: 150px;">
				<option value=""></option>
				<? 
					foreach($resmke as $rowmke){ 
						if($rowmke['make_id'] == $make){
							$selectedmke = 'selected';
						}else{
							$selectedmke = null;
						}
				?>
				<option value="<?=$rowmke['make_id'];?>" <?=$selectedmke;?>><?=$rowmke['make'];?></option>
				<? } ?>
			</select></td>
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td class ="input"><select name="model" id="model" style="width: 235px;" onChange="getModelVariant(this.value);">
				<option value=""></option>
				<? 
					foreach($resmdl as $rowmdl){ 
						if($rowmdl['model_id'] == $model){
							$selectedmdl = 'selected';
						}else{
							$selectedmdl = null;
						}
				?>
				<option value="<?=$rowmdl['model_id'];?>" <?=$selectedmdl;?>><?=$rowmdl['model'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td class ="input"><select name="color" id="color" style="width: 100px;">
				<option value=""></option>
				<? 
					foreach($resclr as $rowclr){ 
						if($rowclr['color_id'] == $color){
							$selectedclr = 'selected';
						}else{
							$selectedclr = null;
						}
				?>
				<option value="<?=$rowclr['color_id'];?>" <?=$selectedclr;?>><?=$rowclr['color'];?></option>
				<? } ?>
			</select></td>
			<td></td>
				<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"><span id="divModelVariant"><input type="text" name="variant" id="variant" readonly value="<?=$variant;?>" style="width:200px" id=""></span></td> 
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Engine Number:</label></td>
			<td class ="input"><input type="text" name="engine" id="engine" value="<?=$engineno;?>" style="width:235px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis Number</label></td>
			<td class ="input"><input type="text" name="chassis" id="chassis" value="<?=$chassisno;?>" style="width:200px"></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
			<td class ="input"><input type="text" name="serial" id="serial" value="<?=$serialno;?>" style="width:118px" id=""></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Odometer:</label></td>
			<td class ="input"><input type="text" name="odometer" id="odometer" value="" style="width:235px" id=""></td>
			<td></td>
			<td></td>
			<td class ="label" style="text-align: left;"><span id="newVehicleLink"><label name="lbl_customer"><a href="vehicle_add1.php<?=$newVehicleLink;?>">Add new Vehicle</a></label></span></td>
		</tr>
	</table>
	<input type="hidden" name="vehicleType" id="vehicleType" value="1" />