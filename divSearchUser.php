<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "users_list.php";

$query2=" SELECT * FROM v_users WHERE name LIKE '%$data%' ";

$count=$dbo->prepare($query2);
$count->execute();
$nume=$count->rowCount();

echo "<TABLE><tr>";
echo "<th width='75px'><a href='$page_name?column_name=username'>Username</a></th>";
echo "<th width='120px'><a href='$page_name?column_name=name'>Name</a></th>";
echo "<th width='250px'><a href='$page_name?column_name=user_status'>User Status</th>";
echo "<th><a href='$page_name?column_name=button'></th>";


////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////

//////////////// Now we will display the returned records in side the rows of the table/////////
foreach ($dbo->query($query2) as $row) {

//if($bgcolor=='#f1f1f1'){$bgcolor='#ffffff';}
//else{$bgcolor='#f1f1f1';}

echo "<tr >";
echo "<td><center>$row[username]</td>"; 
echo "<td><center>$row[name]</center></td>"; 
echo "<td><center>$row[status_desc]</center></td>"; 
echo "<td width='110px'><center>
		<a href='user_edit.php?username=$row[username]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
		<a href='user_access.php?username=$row[username]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
		<a href='user_delete.php?username=$row[username]'><img src='images/delete.png' /></a>
	</center></td>"; 

echo "</tr>";
}
echo "</table>";