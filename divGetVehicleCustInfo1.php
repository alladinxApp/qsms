<?
	require_once("conf/db_connection.php");
	
	$vehicleid = $_GET['vehicleid'];
	
	$qry = " SELECT * FROM v_customer order by lastname";
	$result = $dbo->query($qry);
	
	$qry1 = " SELECT * FROM v_customer order by lastname";
	$result1 = $dbo->query($qry1);
	
	$qry = " SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$vehicleid'";
	$customer = $dbo->query($qry);
	foreach($customer as $rowcust){
		$id = $rowcust['customer_id'];
	}
	
	$qry2 = " SELECT * FROM v_customer WHERE cust_id = '$id'";
	$result2 = $dbo->query($qry2);
	foreach($result2 as $row2){
		$custaddress = $row2['address'];
		$custid = $row2['cust_id'];
	}
?>
<table>
    <tr>
		<td class ="label"><label name="lbl_customer_id">Customer Code:</label>
		<td class ="input"><select name="customer_id" id="customer_id"	onchange="getCustInfo(this.value);" style="width: 235px;">
			<? 
				foreach($result as $row){ 
					if($custid == $row['cust_id']){
						$select = 'selected';
					}else{
						$select = null;	
					}
			?>
			<option value="<?=$row['cust_id'];?>" <?=$select;?>><?=$row['cust_id'];?></option>
			<? } ?>
		</select></td>
		<td></td>
		<td class ="label"><label name="lbl_customer">Customer Name:</label></td>
		<td class ="input"><select name="customer" onchange="getCustInfo(this.value);" style="width: 235px;">
			<? 
				foreach($result1 as $row1){ 
					if($custid == $row1['cust_id']){
						$select1 = 'selected';
					}else{
						$select1 = null;
					}
			?>
			<option value="<?=$row1['cust_id'];?>" <?=$select1;?>><?=$row1['lastname'] . ', ' . $row1['firstname'] . ' ' . $row1['middlename'];?></option>
			<? } ?>
		</select></td>
	</tr>
	
	<tr>
	<td class ="label"><label name="lbl_custadd">Address:</label></td>
	<td class ="input"><input type="text" name="customer_address" value="<?=$custaddress;?>" style="width:235px"> </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td class ="label"><label name="lbl_customer"><a href="customer_add1.php">Add new Customer</a></label></td>
	</tr>
</table>
<input type="hidden" name="custType" id="custType" value="1" />