<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "wocategory_list.php";

$query2 = " SELECT * FROM v_wocat WHERE wocat LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=wocat_id'>Job Order Category Code</a></th>";
echo "<th><a href='$page_name?column_name=wocat'>Job Order Category Description</a></th>";
echo "<th>&nbsp;</th></tr>";

$query=" SELECT * FROM v_wocat WHERE wocat LIKE '%$data%'";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[wocat_id]</td>"; 
echo "<td><center>$row[wocat]</center></td>"; 
echo "<td><center>
			<a href='wocategory_edit.php?wocatid=$row[wocat_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='wocategory_delete.php?wocatid=$row[wocat_id]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='3' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";