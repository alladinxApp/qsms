<?php
require_once("conf/db_connection.php");

$data = $_GET['data'];

if(!isset($_SESSION)){
	session_start();
}

$page_name = "year_list.php";

$query2 = " SELECT * FROM v_year WHERE year LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=year_id'>Year Code</a></th>";
echo "<th><a href='$page_name?column_name=year'>Year </a></th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_year WHERE year LIKE '%$data%'";

if($num> 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[year_id]</td>"; 
echo "<td><center>$row[year]</center></td>"; 
echo "<td width='110px'><center>
			<a href='year_edit.php?yearid=$row[year_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='year_delete.php?yearid=$row[year_id]'><img src='images/delete.png' /></a>
		</center></td>"; 
		
echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='3' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";