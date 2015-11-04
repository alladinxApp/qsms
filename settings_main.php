<?php	
	session_start();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	if(empty($_SESSION['username'])){
		echo '<script>window.location="logout.php";</script>';
		exit();
	}
	
	chkMenuAccess('settings_main',$_SESSION['username'],'estimates_main.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fast QSMS</title>
<link rel="stylesheet" type="text/css" href="style/style.css" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<script src="js/digitalclock.js" type="text/javascript"></script>
</head>

<body onload="startTime()">

   <!--<div id="top">
      <div>
      </div>
</div>-->
<br /><br />
<div id="main">
	
        <div id="lmain">
                <a href="#" title="logo" ><img src="images/logo1.png" /></a>
                
            <div id="pic_menu">
            	<ul>
            	<li id="li_menu"><a href="estimates_main.php"><img src="images/home.jpg" /></a></li>
                <li id="li_menu"><a href=""><img src="images/settings.jpg" /></a></li>                   
                <li id="li_menu"><a href="login.php"><img src="images/logout.jpg" hspace="15px" /></a></li>    
           		</ul>
       	  </div><!--end of pic_menu -->
               
         	<div id="mmenu">
                
                <!--<div style="width:4px; height:37px; padding:0px; margin:0px; float:right;"></div>-->
                <ul>
                <li id="li_menu"><a href=""></a></li>
                <li id="li_menu"><a href=""></a> </li>
                <li id="li_menu"><a href=""></a></li>
                <li id="li_menu"><a href=""></a></li>
                <li id="li_menu"><a href="estimates_main.php">Home</a></li>
                <li id="li_menu"><a href="">Settings</a></li>
                <li id="li_menu"><a href="login.php">Logout</a></li>    
      			</ul>
          </div><!--end of menu--> 
      
  </div><!--end of lmaim -->
        
       <!--<div>
        
        
        </div>-->
		 <div id="sidemain">
		 <div>Welcome! <img src="images/users/<?=$_SESSION['userimage'];?>" width="209" /></div>
		 <div class="cal"><script charset="UTF-8" src="js/calendar.js" type="text/javascript"></script></div>
		 <div id="divDigiClock" class="digiclock"></div>
     	</div>
     
        
            <div id="main2">
        
                     <div id="contents">
            
            		<iframe src="settings.php" frameborder="0" width="850px" height="650px" style="overflow:hidden"></iframe>
    		
                	</div><!--end of contents -->   
     				</div><!--end of main2 -->
    	
</div><!--end of main-->
	<br /><br />
</body>
</html>