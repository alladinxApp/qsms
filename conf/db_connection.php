<?php
date_default_timezone_set('Asia/Manila');
$DBHost = "localhost";
$DBUser = "root";
$DBPass = "";
$DBName = "sqms";

$dbhandle = mysql_connect($DBHost, $DBUser, $DBPass)
  or die("Couldn't connect to SQL Server on $DBHost");

$selected = mysql_select_db($DBName, $dbhandle)
  or die("Couldn't open database $DBName");

if(!isset($_SESSION)) 
{ 
session_start(); 
}  

//////// Do not Edit below /////////
try {
$dbo = new PDO('mysql:host=localhost;dbname='.$DBName, $DBUser, $DBPass);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}

$today = date("Y-m-d H:i:s");
$ses_UserID = 'admin';
?>