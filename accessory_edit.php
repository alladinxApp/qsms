<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$accessory_id =$_GET['accessoryid'];
	
	$qryacc = "SELECT * FROM v_accessory WHERE accessory_id = '$accessory_id'";
	$resacc = $dbo->query($qryacc);

	$qry_items = "SELECT * FROM v_items WHERE status = '1' ORDER BY item_description";
	$result_items = $dbo->query($qry_items);
	
	foreach($resacc as $row){
		$accessory = $row['accessory'];
		$access_disc = $row['access_disc'];
		$access_srp = $row['access_srp'];
		$access_low = $row['access_low'];
		$access_onhand = $row['access_onhand'];
		$access_status = $row['access_status'];
		$item_code = $row['item_code'];
		$access_used = $row['access_used'];
	}
	
	if (isset($_POST['update'])){
		$accessory = mysql_real_escape_string(strtoupper($_POST['accessory']));
		$access_disc = mysql_real_escape_string(str_replace(",","",$_POST['access_disc']));
		$access_srp = mysql_real_escape_string(str_replace(",","",$_POST['access_srp']));
		$access_onhand = mysql_real_escape_string($_POST['access_onhand']);
		$access_low = $_POST['access_low'];
		$access_status = $_POST['access_status'];
		$item_code = $_POST['item_code'];
		$access_used = $_POST['access_used'];

		// $sql_chkaccess = "SELECT accessory_id,accessory AS description,item_code FROM v_accessory WHERE item_code = '$item_code' 
		// 				UNION
		// 				SELECT material_id,material AS description,item_code FROM v_material WHERE item_code = '$item_code'
		// 				UNION
		// 				SELECT parts_id,parts AS description,item_code FROM v_parts WHERE item_code = '$item_code'";
		// $qry_chkaccess = mysql_query($sql_chkaccess);
		// $num_chkaccess = mysql_num_rows($qry_chkaccess);

		// while($row = mysql_fetch_array($qry_chkaccess)){
		// 	$access_desc = $row['accessory'];
		// }

		// if($num_chkaccess > 0){
		// 	echo '<script>alert("Item is already in used/mapped to '.$access_desc.'! please select another Item.");</script>';
		// 	echo '<script>window.location="accessory_edit.php?accessoryid='.$accessory_id.'";</script>';
		// 	exit();
		// }
	  
		$accessory_update = "UPDATE tbl_accessory SET 
				accessory = '$accessory',  
				access_disc = '$access_disc', 
				access_srp = '$access_srp', 
				access_onhand = '$access_onhand',  
				access_low = '$access_low', 
				access_status = '$access_status',
				access_used = '$access_used',
				item_code = '$item_code',
				modified_date = '$today',
				modified_by = '$_SESSION[username]' WHERE accessory_id = '$accessory_id'  ";
						
									 
		$res = mysql_query($accessory_update) or die("UPDATE ACCESSORY ITEM ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your accessory! Please double check all the data and update.");</script>';
		}else{
			echo '<script>alert("Accessory successfully updated.");</script>';
		}
		echo '<script>window.location="accessory_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms_edit.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>

</head>
<script type="text/javascript">
	function CurrencyFormatted(fld){
		amount = document.getElementById(fld).value;
		var i = parseFloat(amount);
		if(isNaN(i)) { i = 0.00; }
		var minus = '';
		if(i < 0) { minus = '-'; }
		i = Math.abs(i);
		i = parseInt((i + .005) * 100);
		i = i / 100;
		s = new String(i);
		if(s.indexOf('.') < 0) { s += '.00'; }
		if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
		s = minus + s;
		var t = CommaFormatted(s);
		document.getElementById(fld).value = t;
	}
	function CommaFormatted(amount){
		var delimiter = ","; // replace comma if desired
		amount = new String(amount);
		var a = amount.split('.',2)
		var d = a[1];
		var i = parseInt(a[0]);
		if(isNaN(i)) { return ''; }
		var minus = '';
		if(i < 0) { minus = '-'; }
		i = Math.abs(i);
		var n = new String(i);
		var a = [];
		while(n.length > 3)
		{
			var nn = n.substr(n.length-3);
			a.unshift(nn);
			n = n.substr(0,n.length-3);
		}
		if(n.length > 0) { a.unshift(n); }
		n = a.join(delimiter);
		if(d.length < 1) { amount = n; }
		else { amount = n + '.' + d; }
		amount = minus + amount;
		return amount;
	}
	function IsNumeric(sText) {
		var ValidChars = "0123456789.,";
		var IsNumber=true;
		var Char;

		for (i = 0; i < sText.length && IsNumber == true; i++) { 
			Char = sText.charAt(i); 
			if (ValidChars.indexOf(Char) == -1) {
				IsNumber = false;
			}
		}
		alert(IsNumber);
		return IsNumber;
	}
	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57)){
			return false;
		}else{
			return true;
		}
	}
	function getSRP(){
		var ret = 0;
		var srp = document.getElementById("access_srp").value;
		var discprice = document.getElementById("access_disc").value;

		var discounted = ((parseFloat(discprice) * 0.35) + parseFloat(discprice));
		
		if(srp < discounted){
			ret = 1;
		}

		return ret;
	}
</script>
<body>
	<form method="post" name="accessory_form" class="form">
	<fieldset>
	<legend><p id="title">Lubricants Masterfile</p></legend>
	<br />
	<table>
		<tr>
			<td class="label"><label name="lbl_accessory_id">Accessory Item Code:</label></td>
			<td class="input" colspan="3"><input type="text" name="accessory_id" value="<?=$accessory_id;?>" readonly style="width:272px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_accessory">Accessory Description:</label></td>
			<td class="input" colspan="3"><input type="text" name="accessory" id="accessory" value="<?=$accessory;?>" style="width:272px"></td>
		</tr> 
		
		<tr>
			<td class="label"><label name="lbl_access_disc">Discounted Price:</label></td>
			<td class="input" colspan="3"><input type="text" name="access_disc" id="access_disc" value="<?=$access_disc;?>" onBlur="return CurrencyFormatted('access_disc'); return IsNumeric(this.value);" style="width:272px"></td> 
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_access_srp">Standard Retail Price:</label></td>
			<td class="input" colspan="3"><input type="text" name="access_srp" id="access_srp" value="<?=$access_srp;?>" onBlur="return CurrencyFormatted('access_srp'); return IsNumeric(this.value);" style="width:272px"></td> 
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_access_onhand">Stock On Hand:</label></td>
			<td class="input"><input type="text" name="access_onhand" id="access_onhand" value="<?=$access_onhand;?>" onkeypress="return isNumberKey(event);" style="width:91px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_access_low">Low Stock Quantity:</label></td>
			<td class="input"><input type="text" name="access_low" id="access_low" value="<?=$access_low;?>" onkeypress="return isNumberKey(event);" style="width:91px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="item_code">Item Mapper:</label>
			<td class ="input"><select name="item_code" id="item_code">
				<option value="">-- Select Item --</option>
				<? 
					foreach($result_items as $row){ 
						$selected = null;
						if($row['item_code'] == $item_code){
							$selected = 'selected';
						}
				?>
					<option value="<?=$row['item_code'];?>" <?=$selected;?>><?=$row['item_description'];?> ( <?=$row['UOM_desc'];?> )</option>
				<? } ?>
			</select></td>
		</tr>
		<tr>
			<td class="label"><label name="acces_used">Accessory Used:</label></td>
			<td class="input"><input type="text" name="acces_used" id="acces_used" value="<?=$access_used;?>" onkeypress="return isNumberKey(event);" style="width:91px"></td>
		</tr>
		<tr>
			<td class="label"><label name="lbl_access_status">Accessory Status:</label></td>
			<td class="input">
				<input type="radio" name="access_status" id="access_status" value="Active" <? if($access_status == "Active"){ echo 'checked'; } ?>>Active 
				<input type="radio" name="access_status" id="access_status" value="Inactive" <? if($access_status == "Inactive"){ echo 'checked'; } ?>>Inactive
			</td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="accessory_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var accessory = document.getElementById("accessory");
			var access_disc = document.getElementById("access_disc");
			var access_srp = document.getElementById("access_srp");
			var access_onhand = document.getElementById("access_onhand");
			var access_low = document.getElementById("access_low");
			var item_code = document.getElementById("item_code");
			
			if(accessory.value == ""){
				alert("Accessory is required! Please enter accessory.");
				accessory.focus();
				return false;
			}else if(access_disc.value == ""){
				alert("Accessory discount price is required! Please enter accessory discount price.");
				access_disc.focus();
				return false;
			}else if(access_srp.value == ""){
				alert("Accessory SRP is required! Please enter accessory SRP.");
				access_srp.focus();
				return false;
			}else if(access_onhand.value == ""){
				alert("Accessory on hand is required! Please enter accessory on hand.");
				access_onhand.focus();
				return false;
			}else if(access_low.value == ""){
				alert("Accessory low stock is required! Please enter accessory low stock.");
				access_low.focus();
				return false;
			}else if(item_code.value == ""){
				alert("Item is required! Please select item.");
				return false;
			}else if(getSRP() > 0){
				alert("Please enter a valid Accessory Standard Retail Price! \n formula: discounted price * 35% + discounted price.");
				part_srp.value = "";
				part_srp.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>