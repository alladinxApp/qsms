<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "job_list.php";

$query2 = " SELECT * FROM v_job WHERE job LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=job_id'>Job Code</a></th>";
echo "<th><a href='$page_name?column_name=job'>Job Description</a></th>";
echo "<th><a href='$page_name?column_name=category'>Job Category</a></th>";
echo "<th><a href='$page_name?column_name=stdhr'>Standard Hour</a></th>";
echo "<th><a href='$page_name?column_name=stdrate'>Standard Rate</a></th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_job WHERE job LIKE '%$data%'";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[job_id]</td>"; 
echo "<td><center>$row[job]</center></td>";
echo "<td><center>$row[wocat]</center></td>";
echo "<td><center>$row[stdhr]</center></td>"; 
echo "<td><center>$row[stdrate]</center></td>"; 
echo "<td width='110px'><center>
			<a href='job_edit.php?jobid=$row[job_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='job_delete.php?jobid=$row[job_id]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='6' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";