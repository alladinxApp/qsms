<?php
require_once("conf/db_connection.php");

$data = $_GET['data'];

if(!isset($_SESSION))
{
session_start();
}

$page_name = "customer_list.php";

$query2 = " SELECT * FROM v_customer WHERE last LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=customer_id'>Customer Code</a></th>";
echo "<th><a href='$page_name?column_name=name'>Customer Name</a></th>";
echo "<th><a href='$page_name?column_name=gender'>Gender</a></th>";
echo "<th><a href='$page_name?column_name=birthday'>Birthdate</a></th>";
echo "<th><a href='$page_name?column_name=company'>Company</a></th>";
echo "<th><a href='$page_name?column_name=email'>Email Address</a></th>";
echo "<th><a href='$page_name?column_name=phone'>Phone Number</a></th>";
echo "<th><a href='$page_name?column_name=mobile'>Mobile Number</a></th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_customer WHERE custname LIKE '%$data%'";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[cust_id]</td>"; 
echo "<td>$row[salutation]&nbsp$row[lastname]&nbsp$row[firstname],&nbsp$row[middlename] </td>";
echo "<td><center>$row[gender]</center></td>";
echo "<td><center>$row[birthday]</center></td>";
echo "<td><center>$row[company]</center></td>";
echo "<td><center>$row[email]</center></td>";
echo "<td><center>$row[landline]</center></td>";
echo "<td><center>$row[mobile]</center></td>"; 
echo "<td width='110px'><center>
			<a href='customer_edit.php?custid=".$row['cust_id']."'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='customer_delete.php?custid=".$row['cust_id']."'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='9' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";