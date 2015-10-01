<?php
	session_start();
	require("conf/db_connection.php");
	require("functions.php");
	
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
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fast QSMS</title>
	<link rel="stylesheet" type="text/css" href="style/style.css" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="css2/reset.css" type="text/css" media="screen">
    <link rel="stylesheet" href="css2/style.css" type="text/css" media="screen">  
	<link rel="stylesheet" href="css2/zerogrid.css" type="text/css" media="all">
	<link rel="stylesheet" href="css2/responsive.css" type="text/css" media="all">
    <script src="js2/jquery-1.6.3.min.js" type="text/javascript"></script>
    <script src="js2/cufon-yui.js" type="text/javascript"></script>
    <script src="js2/cufon-replace.js" type="text/javascript"></script>
    <script src="js2/Kozuka_Gothic_Pro_OpenType_300.font.js" type="text/javascript"></script>
    <script src="js2/Kozuka_Gothic_Pro_OpenType_700.font.js" type="text/javascript"></script>
    <script src="js2/Kozuka_Gothic_Pro_OpenType_900.font.js" type="text/javascript"></script> 
    <script src="js2/FF-cash.js" type="text/javascript"></script>     
    <script type="text/javascript" src="js2/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="js2/tms-0.3.js"></script>
	<script type="text/javascript" src="js2/tms_presets.js"></script>
    <script type="text/javascript" src="js2/easyTooltip.js"></script> 
    <script type="text/javascript" src="js2/script.js"></script>
	<script type="text/javascript" src="js2/css3-mediaqueries.js"></script>
	<!--[if lt IE 7]>
    <div style=' clear: both; text-align:center; position: relative;'>
        <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
        	<img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
    </div>
	<![endif]-->
    <!--[if lt IE 9]>
   		<script type="text/javascript" src="js2/html5.js"></script>
        <link rel="stylesheet" href="css2/ie.css" type="text/css" media="screen">
	<![endif]-->
</head>
<body id="page1">
 
	<!--==============================header=================================-->
    <header>
    	<div class="main zerogrid">
        	<div class="prev-indent-bot2">
                <h1><a href="main.php"><img src="images2/logo.png" /></a></h1>
                <nav>
                    <ul class="menu">
                        <li><a class="active" href="main.php">home</a></li>
                        <li><a href="main.php">about us</a></li>
                        <li><a href="main.php">services</a></li>
                        <li><a href="online_estimate.php">estimate</a></li>
                        <li><a href="main.php">contacts</a></li>
                    </ul>
                </nav>
                <div class="clear"></div>
            </div>
        </div>
        
        <? if(empty($_SESSION['username'])){ ?>
         <!--===================login=====================-->
      <div id="form_login">
   <form action="login.php" method="Post">
   <label class="error"><?php echo $msg; ?></label><br /> 
    <label name="username">Username:</label> <input type="text" name="usernametxt" id="usernametxt" /> &nbsp;&nbsp;&nbsp;
    <label name"password">Password:</label> <input type="password" name="passwordtxt" id="passwordtxt" />
    <input type="submit" name="btn_log" id="btn_log" value="Login" />
    </form>
    </div>
    <!--==en of login==-->
	<? } ?>
        <div class="slider-wrapper">
        	<div class="slider">
            	<ul class="items">
                	<li>
                    	<img src="images2/slider-img1.jpg" alt="">
                        <div class="banner">
                        	<strong>Full <strong>quality service !</strong></strong>
                            <em>It’s a place you can come to for all of your auto maintenance needs.</em>
                            <span class="button">
                            	<a href="#"><strong>Read More</strong></a>
                            </span>
                        </div>
                    </li>
                    <li>
                    	<img src="images2/slider-img2.jpg" alt="">
                        <div class="banner">
                        	<strong>best <strong>service!</strong></strong>
                            <em>Customer Service You Can Depend On!</em>
                            <span class="button">
                            	<a href="#"><strong>Read More</strong></a>
                            </span>
                        </div>
                    </li>
                    <li>
                    	<img src="images2/slider-img3.jpg" alt="">
                        <div class="banner">
                        	<strong>Best <strong>Price!</strong></strong>
                            <em>With our shop specials, you can save even more!</em>
                            <span class="button">
                            	<a href="#"><strong>Read More</strong></a>
                            </span>
                        </div>
                    </li>
                </ul>
            </div>        
        </div>
    </header>
    
  
 <!--==============================content================================-->
    <section id="content"> 
    </section>

    <!--==============================footer=================================-->
    <footer>
        <div class="main zerogrid">
        	<div class="row">
            	<article class="col-1-4">
					<div class="wrap-col">
                    	<ul class="list-services">
                        	<li class="item-1"><a class="tooltips" title="facebook" href="#"></a></li>
                            <li class="item-2"><a class="tooltips" title="twiiter" href="#"></a></li>
                            <li class="item-3"><a class="tooltips" title="delicious" href="#"></a></li>
                            <li class="item-4"><a class="tooltips" title="youtube" href="#"></a></li>
                        </ul>
					</div>
                </article>
                <article class="col-1-4">
                	<div class="wrap-col">
                        <h5>Navigation</h5>
                        <ul class="list-1">
                            <li><a href="index.html">Home</a></li>
                            <li><a href="index.html">About Us</a></li>
                            <li><a href="index.html">Services</a></li>
                            <li><a href="online_estimate.php">Etimate</a></li>
                            <li><a href="index.html">Contacts</a></li>
                        </ul>
                    </div>
                </article>
                <article class="col-1-4">
					<div class="wrap-col">
                    	<h5>Contact</h5>
                        <dl class="contact">
                            <dt>FMW Building<br>235 Alaang-Zapote Road, Muntilupa City<br>
                            </dt>
                            <dd><span>Phone:</span>784-FAST(3278)</dd>
                            <dd><span>Fax:</span>784-FAST(3278)</dd>
                         </dl>
					 </div>
                </article>
                <article class="col-1-4">
					<div class="wrap-col">
                    	<h5>Legal</h5>
                        <p class="prev-indent-bot3 color-1">Quick Service  &copy; 2014					</p>
					</div>
                </article>
            </div>
        </div>
    </footer>
	<script type="text/javascript"> Cufon.now(); </script>
    <script type="text/javascript">
		$(window).load(function(){
			$('.slider')._TMS({
				duration:800,
				easing:'easeOutQuad',
				preset:'simpleFade',
				pagination:true,//'.pagination',true,'<ul></ul>'
				pagNums:false,
				slideshow:7000,
				banners:'fade',// fromLeft, fromRight, fromTop, fromBottom
				waitBannerAnimation:false
			})
		})
	</script>
</body>
</html>