<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "material_list.php";

$query2 = " SELECT * FROM v_material WHERE material LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=material_id'>Material Code</a></th>";
echo "<th><a href='$page_name?column_name=material'>Material Name</a></th>";
echo "<th><a href='$page_name?column_name=material_discount'>Discounted Price</a></th>";
echo "<th><a href='$page_name?column_name=material_srp'>Retail Price</a></th>";
echo "<th><a href='$page_name?column_name=material_low'>Low Stock Qty.</a></th>";
echo "<th><a href='$page_name?column_name=material_onhand'>On Hand Qty.</a></th>";
echo "<th><a href='$page_name?column_name=material_status'>Status</a></th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_material WHERE material LIKE '%$data%'";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[material_id]</td>"; 
echo "<td><center>$row[material]</center></td>"; 
echo "<td><center>$row[material_disc]</center></td>"; 
echo "<td><center>$row[material_srp]</center></td>";
echo "<td><center>$row[material_lowstock]</center></td>";
echo "<td><center>$row[material_onhand]</center></td>";
echo "<td><center>$row[material_status]</center></td>";    
echo "<td width='110px'><center>
			<a href='material_edit.php?materialid=$row[material_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='material_delete.php?materialid=$row[material_id]'><img src='images/delete.png' /></a>
		</center></td>"; 
echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='8' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";