<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$material_id = $_GET['materialid'];

	$qrymat = "SELECT * FROM v_material WHERE material_id  = '$material_id'";
	$resmat = $dbo->query($qrymat);

	$qry_items = "SELECT * FROM v_items WHERE status = '1' ORDER BY item_description";
	$result_items = $dbo->query($qry_items);
	
	foreach($resmat as $row){
		$material = $row['material'];
		$material_disc = $row['material_disc'];
		$material_srp = $row['material_srp'];
		$material_lowstock = $row['material_lowstock'];
		$material_onhand = $row['material_onhand'];
		$material_status = $row['material_status'];
		$item_code = $row['item_code'];
		$material_used = $row['material_used'];
	}
	
	if (isset($_POST['update'])){
		$material = mysql_real_escape_string(strtoupper($_POST['material']));
		$material_disc = mysql_real_escape_string(str_replace(",","",$_POST['material_disc']));
		$material_srp = mysql_real_escape_string(str_replace(",","",$_POST['material_srp']));
		$material_onhand = mysql_real_escape_string($_POST['material_onhand']);
		$material_lowstock = $_POST['material_lowstock'];
		$material_status = $_POST['material_status'];
		$item_code = $_POST['item_code'];
		$material_used = $_POST['material_used'];

		$material_update = "UPDATE tbl_material SET 
						material = '$material',  
						material_disc = '$material_disc', 
						material_srp = '$material_srp', 
						material_onhand = '$material_onhand',  
						material_lowstock = '$material_lowstock', 
						material_status = '$material_status',
						material_used = '$material_used',
						item_code = '$item_code',
						modified_date = '$today',
						modified_by = '$_SESSION[username]' WHERE material_id = '$material_id'  ";
						
		$res = mysql_query($material_update) or die("UPDATE MATERIAL ITEM ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your material! Please double check all the data and update.");</script>';
		}else{
			echo '<script>alert("Material successfully updated.");</script>';
		}
		echo '<script>window.location="material_list.php";</script>';
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
		var srp = document.getElementById("material_srp").value;
		var discprice = document.getElementById("material_disc").value;

		var discounted = ((parseFloat(discprice) * 0.35) + parseFloat(discprice));
		
		if(srp < discounted){
			ret = 1;
		}

		return ret;
	}
</script>
<body>
	<form method="post" name="material_form" class="form">
	<fieldset>
	<legend><p id="title">Material Item Masterfile</p></legend>
	<br />
	<table>
		<tr>
			<td class="label"><label name="lbl_material_id">Material Item Code:</label></td>
			<td class="input" colspan="3"><input type="text" name="material_id" value="<?=$material_id;?>" readonly style="width:272px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_material">Material Description:</label></td>
			<td class="input" colspan="3"><input type="text" name="material" id="material" value="<?=$material;?>" style="width:272px"></td>
		</tr> 
		
		<tr>
			<td class="label"><label name="lbl_material_disc">Discounted Price:</label></td>
			<td class="input" colspan="3"><input type="text" name="material_disc" id="material_disc" value="<?=$material_disc;?>" onBlur="return CurrencyFormatted('material_disc'); return IsNumeric(this.value);" style="width:272px"></td> 
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_material_srp">Standard Retail Price:</label></td>
			<td class="input" colspan="3"><input type="text" name="material_srp" id="material_srp" value="<?=$material_srp;?>" onBlur="return CurrencyFormatted('material_srp'); return IsNumeric(this.value);" style="width:272px"></td> 
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_material_onhand">Stock On Hand:</label></td>
			<td class="input"><input type="text" name="material_onhand" id="material_onhand" value="<?=$material_onhand;?>" onkeypress="return isNumberKey(event);" style="width:91px"></td>
		</tr>
		
		<tr>
			<td class="label"><label name="lbl_material_lowstock">Low Stock Quantity:</label></td>
			<td class="input"><input type="text" name="material_lowstock" id="material_lowstock" value="<?=$material_lowstock;?>" onkeypress="return isNumberKey(event);" style="width:91px"></td>
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
					<option value="<?=$row['item_code'];?>" <?=$selected;?>><?=$row['SAP_item_code'];?> - <?=$row['item_description'];?> ( <?=$row['UOM_desc'];?> )</option>
				<? } ?>
			</select></td>
		</tr>
		<tr>
			<td class="label"><label name="material_used">Material Used:</label></td>
			<td class="input"><input type="text" name="material_used" id="material_used" value="<?=$access_used;?>" onkeypress="return isNumberKey(event);" style="width:91px"></td>
		</tr>
		<tr>
			<td class="label"><label name="lbl_material_status">Material Status:</label></td>
			<td class="input">
				<input type="radio" name="material_status" id="material_status" value="Active" <? if($material_status == "Active"){ echo 'checked'; } ?>>Active 
				<input type="radio" name="material_status" id="material_status" value="Inactive" <? if($material_status == "Inactive"){ echo 'checked'; } ?>>Inactive
			</td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="material_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var material = document.getElementById("material");
			var material_disc = document.getElementById("material_disc");
			var material_srp = document.getElementById("material_srp");
			var material_onhand = document.getElementById("material_onhand");
			var material_lowstock = document.getElementById("material_lowstock");
			var item_code = document.getElementById("item_code");
			
			if(material.value == ""){
				alert("Material is required! Please enter material.");
				material.focus();
				return false;
			}else if(material_disc.value == ""){
				alert("Material discount price is required! Please enter material discount price.");
				material_disc.focus();
				return false;
			}else if(material_srp.value == ""){
				alert("Material SRP is required! Please enter material SRP.");
				material_srp.focus();
				return false;
			}else if(material_onhand.value == ""){
				alert("Material on hand is required! Please enter material on hand.");
				material_onhand.focus();
				return false;
			}else if(material_lowstock.value == ""){
				alert("Material low stock QTY is required! Please enter material low stock QTY.");
				material_lowstock.focus();
				return false;
			}else if(item_code.value == ""){
				alert("Item is required! Please select item.");
				return false;
			}else if(getSRP() > 0){
				alert("Please enter a valid Material Standard Retail Price! \n formula: discounted price * 35% + discounted price.");
				material_srp.value = "";
				material_srp.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>