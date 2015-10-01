<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
				
		$make = strtoupper($_POST['make']);
		$make_rate = str_replace(",","",$_POST['make_rate']);
		$newnum = getNewNum('MAKE');
		
		$make_insert = "INSERT INTO tbl_make (make_id, make, make_rate, make_created) VALUES
		('".$newnum."',
		'".$make."',
		'".$make_rate."',
		'".$today."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'MAKE' ";
		
		$res = mysql_query($make_insert) or die("INSERT MAKE ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your make! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Make successfully saved.");</script>';
		}
		echo '<script>window.location="make_list.php";</script>';
	}
?>
<html>
<head>
<title></title>

<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</script>

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
	<fieldset form="form_make" name="form_make">
	<legend><p id="title">Vehicle Make Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_makeid">Vehicle Make Code:</label>
			<td class ="input"><input type="text" name="make_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_make">Vehicle Make:</label>
			<td class ="input"><input type="text" name="make" id="make" value="" style="width:272px"></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_makerate">Standard Rate:</label></td>
			<td class ="input"><input type="text" name="make_rate" id="make_rate" value="" onBlur="return CurrencyFormatted('make_rate'); return IsNumeric(this.value);" style="width:272px"> </td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="make_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
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