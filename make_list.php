<?php
	require_once("conf/db_connection.php");
	
	session_start();
	if(empty($_SESSION['username'])){
		echo '<script>window.location="logout.php";</script>';
		exit();
	}
?>
<html>
<head>
<title</title>
<link rel="stylesheet" type="text/css" href="style/table.css" />
</head>
<script type="text/javascript">
	function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp = false;	
		try{
			xmlhttp = new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp = false;
				}
			}
		}
		return xmlhttp;
	}
	function SearchMake(){
		var data = document.getElementById("txtsearchmake").value;
		if(data == ""){
			alert("Please enter make to search!");
			return false;
		}
		var strURL = "divSearchMake.php?data="+data;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divSearchMake').innerHTML = req.responseText;
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
</script>
<body>
<div class="button"><a href="make_add.php"><img src="images/add.jpg" /></a></div>

<div class="title">VEHICLE MAKE LIST</div> 

Search by Make: <input type="text" name="txtsearchmake" id="txtsearchmake" name="search" placeholder="Enter Make" /><input type="button" value="Search" onclick="SearchMake();" />

<?Php

$page_name="make_list.php"; //  If you use this code with a different page ( or file ) name then change this 

@$column_name=$_GET['make_list']; // Read the column name from query string. 
if(strlen($column_name)>0 and !ctype_alnum($column_name)){ 
// We don't expect the value of column variable to be any thing other than alphanumeric so checking before using. 
echo "Data Error";
exit;
}

@$start=$_GET['start'];
if(strlen($start) > 0 and !is_numeric($start)){
echo "Data Error";
exit;
}

$eu = ($start - 0); 
$limit = 10;                                 // No of records to be shown per page.
$this1 = $eu + $limit; 
$back = $eu - $limit; 
$next = $eu + $limit; 

/////////////// WE have to find out the number of records in our table. We will use this to break the pages///////
$query2=" SELECT * FROM tbl_make  ";
$count=$dbo->prepare($query2);
$count->execute();
$nume=$count->rowCount();

/////// The variable nume above will store the total number of records in the table////

echo "<span id='divSearchMake'><TABLE><tr>";
echo "<th><a href='$page_name?column_name=make_id'>Vehicle Make Code</a></th>";
echo "<th><a href='$page_name?column_name=make'>Make Description</a></th>";
echo "<th><a href='$page_name?column_name=make_rate'>Standard Rate</a></th>";
echo "<th><a href='$page_name?column_name=button'></th>";


////////////// Now let us start executing the query with variables $eu and $limit  set at the top of the page///////////
$query=" SELECT * FROM tbl_make  ";

if(isset($column_name) and strlen($column_name)>0){
$query = $query . " order by $make_id";
}
$query = $query. " limit $eu, $limit ";
//////////////// Now we will display the returned records in side the rows of the table/////////
foreach ($dbo->query($query) as $row) {

echo "<tr >";
echo "<td><center>$row[make_id]</td>"; 
echo "<td><center>$row[make]</center></td>"; 
echo "<td><center>" . number_format($row['make_rate'],2) . "</center></td>"; 
echo "<td width='110px'><center>
		<a href='make_edit.php?makeid=$row[make_id]'><img src='images/edit.png' /></a> &nbsp;&nbsp; 
		<a href='make_delete.php?makeid=$row[make_id]'><img src='images/delete.png' /></a>
	</center></td>"; 

echo "</tr>";
}
echo "</table><span>";
////////////////////////////// End of displaying the table with records ////////////////////////

/////////////// Start the buttom links with Prev and next link with page numbers /////////////////
echo "<table><tr><td align='right'>";
//// if our variable $back is equal to 0 or more then only we will display the link to move back ////////
if($back >=0) { 
print "<a href='$page_name?start=$back&column_name=$column_name'>PREV <<</a>"; 
} 
//////////////// Let us display the page links at  center. We will not display the current page as a link ///////////
//echo "</td><td align=center width='30%'>";
$i=0;
$l=1;
for($i=0;$i < $nume;$i=$i+$limit){
if($i <> $eu){
echo "&nbsp;&nbsp;&nbsp; <a href='$page_name?start=$i&column_name=$column_name'>$l</a> ";
}
else { echo "<font face='Verdana' size='4' color=red>$l</font>";}        /// Current page is not displayed as link and given font color red
$l=$l+1;
}


//echo "</td><td  align='right' width='30%'>";
///////////// If we are not in the last page then Next link will be displayed. Here we check that /////
if($this1 < $nume) { 
print "<a href='$page_name?start=$next&column_name=$column_name'>>> NEXT</a>";} 
echo "</td></tr></table>";


?>
</body>
</html>