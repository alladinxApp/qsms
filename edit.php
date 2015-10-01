<?php
require_once("conf/db_connection.php");
if(!isset($_SESSION))
{
session_start();
}

?>
<html>
<head>
<title</title>
<!--<link href="style/form.css" rel="stylesheet" type="text/css" />-->
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</script>

<!--END OF DATE PICKER-->

</head>
<body>
<!--<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" 
<input type="hidden" name="personal_id" value="<?php echo $personal_id; ?>"-->
<form method="post" name="accessory_form" class="form">


<fieldset form="form_accessory" name="form_accessory">
	<legend><p id="title">Accessory Item Masterfile</p></legend>
	<table>
	<tr>
	<td class ="label"><label name="lbl_accessory_id">Accessory Item Code:</label>
	<td class ="input"><input type="text" name="accessory_id" value="" style="width:272px"></td>
	</tr>
	<tr>
	<td class ="label"><label name="lbl_accessory">Accessory Description:</label>
	<td class ="input"><input type="text" name="accessory" value="" style="width:272px"></td>
	</tr>
    <tr>
	<td class ="label"><label name="lbl_access_disc">Discounted Price:</label>
	<td class ="input"><input type="text" name="access_disc" value="" style="width:272px"></td>
	</tr>    
    <tr>
	<td class ="label"><label name="lbl_access_srp">Standard Retail Price:</label>
	<td class ="input"><input type="text" name="access_srp" value="" style="width:272px"></td>
	</tr>    
    <tr>
	<td class ="label"><label name="lbl_access_onhand">Stock On Hand:</label>
	<td class ="input"><input type="text" name="access_onhand" value="" style="width:272px"></td>
	</tr>    
    <tr>
	<td class ="label"><label name="lbl_access_low">Low Stock Quantity:</label>
	<td class ="input"><input type="text" name="access_low" value="" style="width:272px"></td>
	</tr>    
	<tr>
	<td class ="label"><label name="lbl_access_status">Accessory Status:</label>
    <td class="input"><input type="radio" name="access_status" value="Active">Active <input type="radio" name="access_status" value="Inactive">Inactive</td>
	</tr>

	</table>
	</fieldset>
	
	<br />

	<!--<button name="save">SAVE</button>-->
	<p class="button">
	<input type="submit" value="" name="save" />
	<!--<input type="reset" value="RESET" />-->
   	<input type="reset" value="" name="reset" />
   
	<br /><br />
	</p>
	
</form>

   <?php

            if (isset($_POST['save'])){
				
				//$personal_id=$_POST['personal_id'];
                $accessory_id=$_POST['accessory_id'];
				$accessory=$_POST['accessory'];
				$access_disc=$_POST['access_disc'];
                $access_srp=$_POST['access_srp'];
				$access_onhand=$_POST['access_onhand'];
				$access_low=$_POST['access_low'];
				$access_status=$_POST['access_status'];
							   
                $accessory_insert = "INSERT INTO tbl_accessory (accessory_id, accessory, access_disc, access_srp, access_onhand, access_low, access_status) VALUES
				('".$accessory_id."',
				'".$accessory."',
				'".$access_disc."',
				'".$access_srp."',
				'".$access_onhand."',
				'".$access_low."',
				'".$access_status."')";
                
				mysql_query($accessory_insert) or die("INSERT ACCESSORY ".mysql_error());


                //header('location:emp_profiles.php');

            }

        ?>
</body>

</html>
