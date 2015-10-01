<?
	require_once("conf/db_connection.php");
	
	$id = $_GET['empid'];
	
	$qryemp = " SELECT * FROM v_employee WHERE emp_id = '$id'";
	$resemp = $dbo->query($qryemp);
	
	foreach($resemp as $rowemp){
		$empname = $rowemp['employee'];
	}
	
	$qrytech = " SELECT * FROM v_employee WHERE emp_status = '1' ORDER BY employee";
	$restech = $dbo->query($qrytech);
	
	$qrytech1 = "SELECT * FROM v_employee WHERE emp_status = '1' ORDER BY employee";
	$restech1 = $dbo->query($qrytech1);
?>
<table>
	<tr>
		<td class ="label"><label name="lbl_customer_id">Technician Code:</label>
		<td class ="input"><select name="empid" id="empid" onchange="getEmpInfo(this.value);" style="width: 235px;">
			<option value=""></option>
			<?
				foreach($restech as $rowtech){
					if($rowtech['emp_id'] == $id){
						$select = 'selected';
					}else{
						$select = null;
					}
			?>
			<option value="<?=$rowtech['emp_id'];?>" <?=$select;?>><?=$rowtech['emp_id'];?></option>
			<? } ?>
		</select></td>
		<td></td>
		<td class ="label"><label name="lbl_custadd">Technician Name:</label></td>
		<td class ="input"><select name="empname" id="empname" onChange="getEmpInfo(this.value);">
			<option value="">Select a Technician</option>
			<? 
				foreach($restech1 as $rowtech1){ 
					if($rowtech1['emp_id'] == $id){
						$select1 = 'selected';
					}else{
						$select1 = null;
					}
			?>
			<option value="<?=$rowtech1['emp_id'];?>" <?=$select1;?>><?=$rowtech1['employee'];?></option>
			<? } ?>
		</select></td>
	</tr>
</table>