<?php
require_once("conf/db_connection.php");

$data = $_GET['data'];

if(!isset($_SESSION))
{
session_start();
}

$page_name = "supplier_list.php";

$query2 = " SELECT * FROM v_suppliers WHERE supplier_name LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=supplier_code'>Supplier Code</a></th>";
echo "<th><a href='$page_name?column_name=SAP_supplier_code'>SAP Supplier Code</a></th>";
echo "<th><a href='$page_name?column_name=supplier_name'>Supplier Name</th>";
echo "<th><a href='$page_name?column_name=address'>Address</th>";
echo "<th><a href='$page_name?column_name=contact_person'>Contact Person</th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_suppliers WHERE supplier_name LIKE '%$data%'";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[supplier_code]</td>"; 
echo "<td><center>$row[SAP_supplier_code]</center></td>"; 
echo "<td><center>$row[supplier_name]</center></td>"; 
echo "<td><center>$row[address]</td>";
echo "<td><center>$row[contact_person]</td>";
echo "<td width='110px'><center>
			<a href='supplier_edit.php?suppliercode=$row[supplier_code]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='supplier_delete.php?suppliercode=$row[supplier_code]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='9' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";