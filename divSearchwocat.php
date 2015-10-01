<?php
require_once("conf/db_connection.php");

$data = $_GET['data'];

if(!isset($_SESSION)){
	session_start();
}

$page_name="wocategory_list.php";

$query2=" SELECT * FROM v_wocat WHERE wocat LIKE '%$data%'";
$count=$dbo->prepare($query2);
$count->execute();
$nume=$count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=wocat_id'>Job Type Code</a></th>";
echo "<th><a href='$page_name?column_name=wocat'>Job Category Description</a></th>";
echo "<th><a href='$page_name?column_name=button'></th>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_wocat WHERE wocat LIKE '%$data%'";

foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[wocat_id]</td>"; 
echo "<td><center>$row[wocat]</center></td>"; 
echo "<td width='110px'><center>
			<a href='wocategory_edit.php?wocatid=$row[wocat_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='wocategory_delete.php?wocatid=$row[wocat_id]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}
echo "</table>";