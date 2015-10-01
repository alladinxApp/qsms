<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "employee_list.php";

$query2 = " SELECT * FROM v_employee WHERE employee LIKE '%$data%' ORDER BY emp_id ";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=employee_id'>Employee ID</a></th>";
echo "<th><a href='$page_name?column_name=employee'>Employee Name</a></th>";
echo "<th><a href='$page_name?column_name=employee_status'>Employee Status</th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query = " SELECT * FROM v_employee WHERE employee LIKE '%$data%' ORDER BY emp_id ";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[emp_id]</td>"; 
echo "<td><center>$row[employee]</center></td>"; 
echo "<td><center>$row[emp_status]</center></td>"; 
echo "<td width='110px'><center>
			<a href='employee_edit.php?empid=$row[emp_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='employee_delete.php?empid=$row[emp_id]'><img src='images/delete.png' /></a>
		</center></td>"; 
echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='4' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";