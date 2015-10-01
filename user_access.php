<?php
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(!isset($_SESSION)){
		session_start();
	}

	$username = $_GET['username'];
	$qry = "SELECT * FROM v_users WHERE username = '$username'";
	$result = $dbo->query($qry);
	$num = mysql_num_rows(mysql_query($qry));
	
	$sql_menu = "SELECT id,menu FROM tbl_menus WHERE tbl_menus.status = '1' AND tbl_menus.id NOT IN(SELECT menu_id FROM tbl_user_access WHERE user_id = '$username') ORDER BY tbl_menus.menu";
	$qry_menu = mysql_query($sql_menu);
	
	$sql_useraccess = "SELECT * FROM v_user_access WHERE user_id = '$username' AND status = '1' ORDER BY menu_id";
	$qry_useraccess = mysql_query($sql_useraccess);
	
	if($num == 0){
		echo '<script>alert("User profile not found!");</script>';
		echo '<script>window.location="users_list.php";</script>';
		exit();
	}
	
	foreach($result as $row){
		$name = $row['name'];
		$status = $row['user_status'];
		$user_type = $row['user_type'];
		$user_image = $row['image'];
	}
	
	if(isset($_GET['delete']) && !empty($_GET['delete'])){
		
		$menu_id = $_GET['delete'];
		
		$sql_delete = "DELETE FROM tbl_user_access WHERE menu_id = '$menu_id' AND user_id = '$username'";
		$qry_delete = mysql_query($sql_delete);
		
		echo '<script>alert("Menu successfully removed.");</script>';
		echo '<script>window.location="user_access.php?username=' . $username . '";</script>';
		exit();
	}
	if(isset($_POST['updateuseraccess']) && !empty($_POST['updateuseraccess']) && $_POST['updateuseraccess'] == 1){
		if(!$_POST['txtchkAll']){
			$menu_id = $_POST['txtmenu'];
			
			$sql_insert = "INSERT INTO tbl_user_access(menu_id,user_id)
							VALUES('$menu_id','$username')";
			$qry_insert = mysql_query($sql_insert);
		}else{
			while($row_menu = mysql_fetch_array($qry_menu)){
				$sql_insert = "INSERT INTO tbl_user_access(menu_id,user_id)
							VALUES('$row_menu[id]','$username')";
				$qry_insert = mysql_query($sql_insert);
			}
			
		}
		
		echo '<script>alert("Menu successfully added.");</script>';
		echo '<script>window.location="user_access.php?username=' . $username . '";</script>';
		exit();
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
</script>

</head>
<body>
	<form method="post" name="employee_form" class="form" onSubmit="return ValidateMe();" enctype="multipart/form-data">
	<fieldset form="form_employee" name="form_employee">
	<legend><p id="title">User Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Username:</label>
			<td class ="input"><input type="text" name="txtusername" id="txtusername" value="<?=$username;?>" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Menu:</label>
			<td class ="input">
				<select name="txtmenu" id="txtmenu">
					<? while($row_menu = mysql_fetch_array($qry_menu)){ ?>
					<option value="<?=$row_menu['id'];?>"><?=$row_menu['menu'];?></option>
					<? } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Select All Menu:</label>
			<td class ="input"><input type="checkbox" name="txtchkAll" id="txtchkAll"></td>
		</tr>
	</table>
	</fieldset>
	<p class="button">
		<input type="submit" value="" name="update" />
		<input type="hidden" value="1" name="updateuseraccess" />
		<a href="user_edit.php?username=<?=$username;?>"><input type="button" value="" name="reset" style="cursor: pointer;" /></a>
	</p>
	</form>
	<fieldset form="form_employee" name="form_employee">
	<legend><p id="title">User Menu Access</p></legend>
	<table>
	<tr>
		<td><table cellpadding="0" cellspacing="0" border="1">
			<? while($row_useraccess = mysql_fetch_array($qry_useraccess)){ ?>
			<tr>
				<td><?=$row_useraccess['menu'];?></td>
				<td><a href='user_access.php?username=<?=$username;?>&delete=<?=$row_useraccess['menu_id'];?>'><img src='images/delete.png' /></a></td>
			</tr>
			<? } ?>
		</table></td>
	</tr>
	</table>
	</fieldset>
</body>
</html>
