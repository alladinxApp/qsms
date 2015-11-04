<?php
	session_start();
	require("conf/db_connection.php");
	require("functions.php");
	
	if(!empty($_SESSION['username'])){
		echo '<script>window.location = "estimates_main.php"</script>';
		exit();
	}
	
	$msg = "";
	if(isset($_POST['btn_log'])){
		$usernametxt = $_POST['usernametxt'];
		$passwordtxt = generatePassword(strtoupper(trim($_POST['passwordtxt'])));
		
		$sql = "SELECT * FROM v_users WHERE username = '$usernametxt' AND password = '$passwordtxt'";
		$qry = mysql_query($sql);
		$cnt = mysql_num_rows($qry);
		
		if($cnt > 0){
			while($row = mysql_fetch_array($qry)){
				$pass = $row['password'];
				$user_img = $row['image'];
			}
			
			$_SESSION['username'] = $usernametxt;
			if(!empty($user_img)){
				$_SESSION['userimage'] = $user_img;
			}else{
				$_SESSION['userimage'] = 'blank-person.jpg';
			}
			echo '<script>window.location="estimates_main.php";</script>';
			exit();
		}else{
			$msg = "Invalid Username/Password!";
		}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fast Quick Service</title>
<link rel="stylesheet" type="text/css" href="style/login.css" />
</head>

<body>

<div id="main">
<form method="post">
 
            	<div id="login_back">
        			<div id="msg">
                    
        			</div>
                    
        			<div id="logo">
        			<a href="main.php"><img src="images2/logo1.png" width="255" height="41" /></a>
        		    </div>
        		    
        		    <p class="login">Login</p>
        		    
                    <div id="login_form">
                    	<label class="error"><?php echo $msg; ?></label><br />
                    	<label for="login">Username:</label>
                    	<input type="text" class="fields" name="usernametxt" />
                         <div id="space_div"></div>
                        <label for="login">Password:</label>
                        <input type="password" class="fields" name="passwordtxt"  autocomplete="off"/>
                        <div class="clear"></div>
                        
                        <div id="space_div"></div>
                        <input type="submit" class="button" name="btn_log" value="Log in" />	
                    
                    </div>
        		</div>

    </form>
	
	</h2>
</div>
</body>
</html>