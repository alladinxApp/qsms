<?php	
	session_start();
	require_once('functions.php');
	if(empty($_SESSION['username'])){
		echo '<script>window.location="logout.php";</script>';
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fast QSMS</title>
<link rel="stylesheet" type="text/css" href="style/style.css" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

</head>

<body>
<br /><br />
<div id="main">
	
        <? require_once('menu_top.php'); ?>
        
		 <div id="sidemain">
		 <div>Welcome! <img src="images/users/<?=$_SESSION['userimage'];?>" width="209" /></div>
		 <div class="cal"><script charset="UTF-8" src="js/calendar.js" type="text/javascript"></script></div>
		 <div>
			<? require_once('digiclock.php'); echo dateFormat($today,"H:i"); ?>
		 </div>
     	</div>
        
            <div id="main2">
        
                     <div id="contents">
            
            		<iframe name="inhta_frame" src="estimate_for_approval.php" frameborder="0" width="850px" height="650px" style="overflow:hidden"></iframe>
    		
                	</div><!--end of contents -->   
     				</div><!--end of main2 -->
    	
</div><!--end of main-->
	<br /><br />
</body>
</html>