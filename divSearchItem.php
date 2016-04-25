<?php
require_once("conf/db_connection.php");

$data = $_GET['data'];

if(!isset($_SESSION))
{
session_start();
}

$page_name = "item_list.php";

$query2 = " SELECT * FROM v_items WHERE item_description LIKE '%$data%'";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=item_code'>Item Code</a></th>";
echo "<th><a href='$page_name?column_name=SAP_item_code'>SAP Item Code</a></th>";
echo "<th><a href='$page_name?column_name=item_description'>Description</th>";
echo "<th><a href='$page_name?column_name=unit_price'>Unit Price</th>";
echo "<th><a href='$page_name?column_name=UOM_desc'>UOM</th>";
echo "<th><a href='$page_name?column_name=low_stock_value'>Low Stock Value</th>";
echo "<th><a href='$page_name?column_name=last_unit_price'>Last Unit Price</th>";
echo "<th><a href='$page_name?column_name=item_type_desc'>Item Type</th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_items WHERE item_description LIKE '%$data%'";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[item_code]</td>"; 
echo "<td><center>$row[SAP_item_code]</center></td>"; 
echo "<td><center>$row[item_description]</center></td>"; 
echo "<td><center>$row[unit_price]</td>";
echo "<td><center>$row[UOM_desc]</td>";
echo "<td><center>$row[low_stock_value]</td>";
echo "<td><center>$row[last_unit_price]</td>";
echo "<td><center>$row[item_type_desc]</td>";
echo "<td width='110px'><center>
			<a href='item_edit.php?itemcode=$row[item_code]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='item_delete.php?itemcode=$row[item_code]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='9' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";