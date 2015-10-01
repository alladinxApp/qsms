<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "accessory_list.php";

$query2 = " SELECT * FROM v_accessory WHERE accessory LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=accessory_id'>Accessory Code</a></th>";
echo "<th><a href='$page_name?column_name=accessory'>Description</a></th>";
echo "<th><a href='$page_name?column_name=access_disc'>Discounted Price</th>";
echo "<th><a href='$page_name?column_name=access_srp'>Retail Price</th>";
echo "<th><a href='$page_name?column_name=access_low'>Low Stock Qty.</th>";
echo "<th><a href='$page_name?column_name=access_onhand'>Stock On Hand</th>";
echo "<th><a href='$page_name?column_name=access_status'>Status</th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_accessory WHERE accessory LIKE '%$data%'";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[accessory_id]</td>"; 
echo "<td><center>$row[accessory]</center></td>"; 
echo "<td><center>$row[access_disc]</center></td>"; 
echo "<td><center>$row[access_srp]</td>";
echo "<td><center>$row[access_low]</td>";
echo "<td><center>$row[access_onhand]</td>";
echo "<td><center>$row[access_status]</td>"; 
echo "<td width='110px'><center>
			<a href='accessory_edit.php?accessoryid=$row[accessory_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='accessory_delete.php?accessoryid=$row[accessory_id]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='8' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";