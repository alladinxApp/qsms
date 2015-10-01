<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	if (isset($_POST['save'])){
		$job = strtoupper($_POST['job']);
		$wocatid = $_POST['wocatid'];
		$stdhr = $_POST['stdhr'];
		$flagrate = $_POST['flagrate'];
		$stdrate = str_replace(",","",$_POST['stdrate']);
		$newnum = getNewNum('JOB');
					   
		$job_insert = "INSERT INTO tbl_job (job_id, job, wocat_id, stdhr, stdrate, flagrate) VALUES
		('".$newnum."',
		'".$job."',
		'".$wocatid."',
		'".$stdhr."',
		'".$flagrate."',
		'".$stdrate."')";
		
		$update_controlno = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'JOB' ";
		
		$res = mysql_query($job_insert) or die("INSERT JOB ".mysql_error());
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your job! Please double check all the data and save.");</script>';
		}else{
			mysql_query($update_controlno);
			echo '<script>alert("Job successfully saved.");</script>';
		}
		echo '<script>window.location="job_list.php";</script>';
	}
	
	$query=" SELECT * FROM v_wocat order by wocat_id";
	$result = $dbo->query($query);
?>
<html>
<head>
<title</title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
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
</script>
</head>
<body>
	<form method="post" name="job_form" class="form" onSubmit="return ValidateMe();">
	<fieldset form="form_job" name="form_job">
	<legend><p id="title">Work Type Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_job_id">Work Type Code:</label>
			<td class ="input"><input type="text" name="job_id" value="[SYSTEM GENERATED]" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_job">Work Description:</label>
			<td class ="input"><input type="text" name="job" id="job" value="" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_jobcat">Job Category:</label>
			<td class ="input"><select name="wocatid" id="wocatid" style="width: 270px;">
				<option value=""></option>
				<? foreach ( $result as $row) { ?>
				<option value="<?=$row['wocat_id'];?>"><?=$row['wocat'];?></option>
				<? }?>
			</select></td>
		</tr>    
		<tr>
			<td class ="label"><label name="lbl_stdhr">Work Standard Hour:</label>
			<td class ="input"><input type="text" name="stdhr" id="stdhr" value="" onkeypress="return isNumberKey(event);" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_stdrate">Work Standard Rate:</label>
			<td class ="input"><input type="text" name="stdrate" id="stdrate" value="" onBlur="return CurrencyFormatted('stdrate'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_stdrate">Flag Rate:</label>
			<td class ="input"><input type="text" name="flagrate" id="flagrate" value="" onBlur="return CurrencyFormatted('flagrate'); return IsNumeric(this.value);" style="width:272px"></td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="save" />
		<a href="job_add.php"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
		<br /><br />
	</p>
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var job = document.getElementById("job");
			var wocatid = document.getElementById("wocatid");
			var stdhr = document.getElementById("stdhr");
			var stdrate = document.getElementById("stdrate");
			
			if(job.value == ""){
				alert("Job description is required! Please enter job description.");
				job.focus();
				return false;
			}else if(wocatid.value == ""){
				alert("Job category is required! Please enter job category.");
				wocatid.focus();
				return false;
			}else if(stdhr.value == ""){
				alert("Work standard hour is required! Please enter work standard hour.");
				stdhr.focus();
				return false;
			}else if(stdrate.value == ""){
				alert("Work standard rate is required! Please enter work standard rate.");
				stdrate.focus();
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>