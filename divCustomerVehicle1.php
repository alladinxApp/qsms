<?
	require_once("conf/db_connection.php");
	
	$vehicle_id = $_GET['vehicleid'];
	
	$qryvehicleinfo = " SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$vehicle_id'";
	$resvehicleinfo = $dbo->query($qryvehicleinfo);

	foreach($resvehicleinfo as $rowvehicle){
		$year = $rowvehicle['year'];
		$make = $rowvehicle['make'];
		$model = $rowvehicle['model'];
		$color = $rowvehicle['color'];
		$variant = $rowvehicle['variant'];
		$engineno = $rowvehicle['engine_no'];
		$chassisno = $rowvehicle['chassis_no'];
		$serialno = $rowvehicle['serial_no'];
	}
	
	$sqlvehicle = " SELECT * FROM v_vehicleinfo";
	$resvehicle = $dbo->query($sqlvehicle);
	
	$sqlyear = " SELECT * FROM v_year WHERE year_id = '$year'";
	$qryyear = mysql_query($sqlyear);
	$rowyear = mysql_fetch_array($qryyear);
	
	$sqlmake = " SELECT * FROM v_make WHERE make_id = '$make'";
	$qrymake = mysql_query($sqlmake);
	$rowmake = mysql_fetch_array($qrymake);
	
	$sqlmodel = " SELECT * FROM v_model WHERE model_id = '$model'";
	$qrymodel = mysql_query($sqlmodel);
	$rowmodel = mysql_fetch_array($qrymodel);
	
	$sqlcolor = " SELECT * FROM v_color WHERE color_id = '$color'";
	$qrycolor = mysql_query($sqlcolor);
	$rowcolor = mysql_fetch_array($qrycolor);
?>
<table>
		<tr>
			<td class ="label"><label name="lbl_plateno">Plate Number:</label></td>
			<td class ="input"><select name="plateno" id="plateno" style="width: 235px;" onchange="return getCustVehicleInfo1(this.value);">
				<?
					foreach($resvehicle as $rowvehicle){
					if($rowvehicle['vehicle_id'] == $vehicle_id){
						$selected_pn = 'selected';
					}else{
						$selected_pn = null;
					}
				?>
				<option value="<?=$rowvehicle['vehicle_id'];?>" <?=$selected_pn;?>><?=$rowvehicle['plate_no'];?></option>
				<? } ?>
			</select></td>
			<td></td>
			<td class ="label"><label name="lbl_year">Year:</label></td>
			<td class ="input"><input type="text" name="year" value="<?=$rowyear['year'];?>" readonly style="width:100px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_make">Make:</label></td>
			<td class ="input"><input type="text" name="make" value="<?=$rowmake['make'];?>" readonly style="width:118px" id=""></td>
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td class ="input"><input type="text" name="model" value="<?=$rowmodel['model'];?>" readonly style="width:235px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td class ="input"><input type="text" name="color" value="<?=$rowcolor['color'];?>" readonly style="width:100px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td class ="input"><input type="text" name="variant" value="<?=$variant;?>" readonly style="width:118px" id=""></td> 
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Engine Number:</label></td>
			<td class ="input"><input type="text" name="engineno" value="<?=$engineno;?>" readonly style="width:235px" id=""></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis Number</label></td>
			<td class ="input"><input type="text" name="chassisno" value="<?=$chassisno;?>" readonly style="width:200px"></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial Number:</label></td>
			<td class ="input"><input type="text" name="serialno" value="<?=$serialno;?>" readonly style="width:118px" id=""></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Odometer:</label></td>
			<td class ="input"><input type="text" name="odometer" id="odometer" value="" style="width:235px" id=""></td>
			<td></td>
			<td></td>
			<td class ="label" style="text-align: left;"><label name="lbl_customer"><a href="vehicle_add1.php?customerid=<?=$customerid;?>">Add new Vehicle</a></label></td>
		</tr>
	</table>
	<input type="hidden" name="vehicleType" id="vehicleType" value="1" />