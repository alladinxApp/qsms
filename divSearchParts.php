<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION)){
	session_start();
}

$page_name = "parts_list.php";

$query2 = "SELECT * FROM v_parts WHERE parts LIKE '%$data%'";
// $count = $dbo->prepare($query2);
// $count->execute();
// $num = $count->rowCount();
$qry = mysql_query($query2);
$num = mysql_num_rows($qry);

echo "<TABLE><tr>";
echo "<th><a href='$page_name?column_name=parts_id'>Parts Code</a></th>";
echo "<th><a href='$page_name?column_name=parts'>Parts Name</a></th>";
echo "<th><a href='$page_name?column_name=parts_discount'>Discounted Price</a></th>";
echo "<th><a href='$page_name?column_name=part_srp'>Retail Price</a></th>";
echo "<th><a href='$page_name?column_name=parts_low'>Low Stock Qty.</a></th>";
echo "<th><a href='$page_name?column_name=part_onhand'>On Hand Qty.</a></th>";
echo "<th><a href='$page_name?column_name=SAP_item_code'>SAP Item Code</a></th>";
echo "<th><a href='$page_name?column_name=partstatus'>Status</a></th>";
echo "<th>&nbsp;</th></tr>";

// ////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////

if($num > 0){
// foreach ($dbo->query($query) as $row) {
while($row = mysql_fetch_array($qry)){

echo "<tr >";
echo "<td><center>$row[parts_id]</td>"; 
echo "<td><center>$row[parts]</center></td>"; 
echo "<td><center>$row[parts_discount]</center></td>"; 
echo "<td><center>$row[part_srp]</center></td>";
echo "<td><center>$row[parts_lowstock]</center></td>";
echo "<td><center>$row[part_onhand]</center></td>";
echo "<td><center>$row[SAP_item_code]</center></td>";
echo "<td><center>$row[partstatus]</center></td>";
echo "<td width='110px'><center>
			<a href='parts_edit.php?partsid=$row[parts_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='parts_delete.php?partsid=$row[parts_id]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='8' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";