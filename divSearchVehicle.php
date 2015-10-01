<?php
require_once("conf/db_connection.php");

$data = strtoupper($_GET['data']);

if(!isset($_SESSION))
{
session_start();
}

$page_name = "vehicle_list.php";

$query2 = " SELECT * FROM v_vehicleinfo WHERE (plate_no LIKE '%$data%' OR conduction_sticker LIKE '%$data%') ";
$count = $dbo->prepare($query2);
$count->execute();
$num = $count->rowCount();

echo "<TABLE><tr>";
echo "<th ><a href='$page_name?column_name=vehicle_id'>Vehicle Code</a></th>";
echo "<th ><a href='$page_name?column_name=customer_name'>Customer Name</a></th>";
echo "<th ><a href='$page_name?column_name=plate_no'>Plate No</a></th>";
echo "<th ><a href='$page_name?column_name=make'>Make</a></th>";
echo "<th ><a href='$page_name?column_name=year'>Year</a></th>";
echo "<th ><a href='$page_name?column_name=model'>Model</a></th>";
echo "<th ><a href='$page_name?column_name=color'>Color</a></th>";
echo "<th ><a href='$page_name?column_name=variant'>Variant</a></th>";
echo "<th ><a href='$page_name?column_name=description'>Description</a></th>";
echo "<th ><a href='$page_name?column_name=engine_no'>Engine No</a></th>";
echo "<th ><a href='$page_name?column_name=chassis_no'>Chassis No</a></th>";
echo "<th ><a href='$page_name?column_name=serial_no'>Serial No</a></th>";
echo "<th>&nbsp;</th></tr>";

////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM v_vehicleinfo WHERE (plate_no LIKE '%$data%' OR conduction_sticker LIKE '%$data%') ";

if($num > 0){
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[vehicle_id]</td>"; 
echo "<td><center>$row[customername]</td>"; 
if(!empty($row['plate_no']) && !empty($row['conduction_sticker'])){
	echo "<td><center>$row[plate_no] / $row[conduction_sticker]</td>"; 
}elseif(!empty($row['plate_no']) && empty($row['conduction_sticker'])){
	echo "<td><center>$row[plate_no]</td>"; 
}elseif(empty($row['plate_no']) && !empty($row['conduction_sticker'])){
	echo "<td><center>$row[conduction_sticker]</td>"; 
}else{}
echo "<td><center>$row[make_desc]</td>"; 
echo "<td><center>$row[year_desc]</td>"; 
echo "<td><center>$row[model_desc]</td>"; 
echo "<td><center>$row[color_desc]</td>"; 
echo "<td><center>$row[variant]</td>"; 
echo "<td><center>$row[description]</td>"; 
echo "<td><center>$row[engine_no]</td>"; 
echo "<td><center>$row[chassis_no]</td>"; 
echo "<td><center>$row[serial_no]</td>"; 

echo "<td width='110px'><center>
			<a href='vehicle_edit.php?vehicleid=$row[vehicle_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
			<a href='vehicle_delete.php?vehicleid=$row[vehicle_id]'><img src='images/delete.png' /></a>
		</center></td>"; 

echo "</tr>";
}}else{
echo "<tr >";
echo "<td colspan='13' align='center'><span style='font-size: 14px; font-weight: bold;'>0 Results found!</span></td>"; 
echo "</tr>";
}
echo "</table>";