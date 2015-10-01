<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "make_list.php";

$query2 = " SELECT * FROM v_make WHERE make LIKE '%$data%' ";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=make_id'>Vehicle Make Code</a></th>";
echo "<th><a href='$page_name?column_name=make'>Make Description</a></th>";
echo "<th><a href='$page_name?column_name=make_rate'>Standard Rate</a></th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_make WHERE make LIKE '%$data%' ";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[make_id]</td>"; 
echo "<td><center>$row[make]</center></td>"; 
echo "<td><center>$row[make_rate]</center></td>"; 
echo "<td width='110px'><center>
			<a href='make_edit.php?makeid=$row[make_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='make_delete.php?makeid=$row[make_id]'><img src='images/delete.png' /></a>
		</center></td>"; 
echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='4' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";