<?php
	require_once("conf/db_connection.php");
	
	if(!isset($_SESSION)){
		session_start();
	}

	$make_id = $_GET['makeid'];

	if (isset($_POST['update'])){
		$make_id = $_POST['make_id'];
		$make = mysql_real_escape_string(strtoupper($_POST['make']));
		$make_rate = mysql_real_escape_string(str_replace(",","",$_POST['make_rate']));
					  
		$make_update = "UPDATE tbl_make SET 
			make='$make', 
			make_rate='$make_rate' WHERE make_id='$make_id'  ";
									 
		$res = mysql_query($make_update) or die("UPDATE MAKE ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on updating your make! Please double check all the data and save.");</script>';
		}else{
			echo '<script>alert("Make successfully updated.");</script>';
		}
		echo '<script>window.location="make_list.php";</script>';
	}
	
	$qrymke = "SELECT * FROM v_make WHERE make_id = '$make_id'";
	$resmke = $dbo->query($qrymke);
	foreach($resmke as $row){
		$make = $row['make'] ;
		$make_rate = $row['make_rate'] ;	
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
</script>
<body>
	<form method="post" name="make_form" class="form" onSubmit="return ValidateMe();">
	<fieldset>
	<legend><p id="title">Vehicle Make Masterfile</p></legend>
	<br />
	<table>
	<tr>
	<td class="label"><label name="lbl_make_id">Vehicle Make Code:</label></td>
	<td class="input" colspan="3"><input type="text" name="make_id" value="<?=$make_id;?>" readonly style="width:272px"></td>
	<td>
	</tr>
	
	<tr>
	<td class="label"><label name="lbl_make">Vehicle Make:</label></td>
	<td class="input" colspan="3"><input type="text" name="make" id="make" value="<?=$make;?>" style="width:272px"></td>
	<tr> 
    
    <tr>
	<td class="label"><label name="lbl_make_rate">Standard Rate:</label></td>
	<td class="input" colspan="3"><input type="text" name="make_rate" id="make_rate" value="<?=$make_rate;?>" onBlur="return CurrencyFormatted('make_rate'); return IsNumeric(this.value);" style="width:272px"></td>
	<tr> 
	
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<a href ="make_list.php"><input type="button" value="" class="cancel" style="cursor: pointer;" /></a>
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var make = document.getElementById("make");
			var make_rate = document.getElementById("make_rate");
			
			if(make.value == ""){
				alert("Make is required! Please enter make.");
				make.focus();
				return false;
			}else if(make_rate.value == ""){
				alert("Make rate is required! Please enter make rate.");
				make_rate.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>	
</body>
</html>
