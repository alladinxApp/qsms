<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "model_list.php";

$query2 = " SELECT * FROM v_model WHERE model LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th ><a href='$page_name?column_name=model_id'>Vehicle Model Code</a></th>";
echo "<th ><a href='$page_name?column_name=model'>Vehicle Model</a></th>";
echo "<th ><a href='$page_name?column_name=variant'>Variant</a></th>";
echo "<th ><a href='$page_name?column_name=variantdescr_srp'>Variant Description</a></th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_model WHERE model LIKE '%$data%'";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[model_id]</td>"; 
echo "<td><center>$row[model]</center></td>"; 
echo "<td><center>$row[variant]</center></td>"; 
echo "<td><center>$row[variantdesc]</center></td>";
echo "<td width='110px'><center>
			<a href='model_edit.php?modelid=$row[model_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='model_delete.php?modelid=$row[model_id]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='5' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";