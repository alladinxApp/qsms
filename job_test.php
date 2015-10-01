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
<form method="post" name="job_form" class="form">


<fieldset form="form_job" name="form_job">
	<legend><p id="title">Work Type Masterfile</p></legend>
	<table>
	<tr>
	<td class ="label"><label name="lbl_job_id">Work Type Code:</label>
	<td class ="input"><input type="text" name="job_id" value="" style="width:272px"></td>
	</tr>
	<tr>
	<td class ="label"><label name="lbl_job">Work Description:</label>
	<td class ="input"><input type="text" name="job" value="" style="width:272px"></td>
	</tr>
    <tr>
	<td class ="label"><label name="lbl_jobcat">Job Category:</label>
	<td class ="input"><input type="text" name="stdhr" value="" style="width:272px"></td>
	</tr>    
    <tr>
	<td class ="label"><label name="lbl_stdhr">Work Standard Hour:</label>
	<td class ="input"><input type="text" name="stdhr" value="" style="width:272px"></td>
	</tr>
    <tr>
	<td class ="label"><label name="lbl_stdrate">Work Standard Rate:</label>
	<td class ="input"><input type="text" name="stdrate" value="" style="width:272px"></td>
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
                $job_id=$_POST['job_id'];
				$job=$_POST['job'];
				$stdhr=$_POST['stdhr'];
                $stdrate=$_POST['stdrate'];
							   
                $job_insert = "INSERT INTO tbl_job (job_id, job, stdhr, stdrate) VALUES
				('".$job_id."',
				'".$job."',
				'".$stdhr."',
				'".$stdrate."')";
                
				mysql_query($job_insert) or die("INSERT JOB ".mysql_error());


                //header('location:emp_profiles.php');

            }

        ?>
</body>

</html>
