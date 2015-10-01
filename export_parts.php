<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$sql_parts = "SELECT * FROM v_parts";
	$qry_parts = mysql_query($sql_parts);
	
	$ln .= "STOCK REPORT\r\n";
	$ln .= "Parts Code,Description,Discount,Low Stock Qty,Stock On Hand,Old Pirce,Old Price Date,SRP,SRP Date\r\n";

	while($row = mysql_fetch_array($qry_parts)){
		$ln .= $row['parts_id'] 
				. "," . $row['parts'] 
				. "," . $row['parts_discount']
				. "," . $row['parts_lowstock'] 
				. "," . $row['part_onhand']
				. "," . $row['parts_old_price']
				. "," . $row['old_price_date']
				. "," . $row['part_srp']
				. "," . $row['new_price_date']
				. "\r\n";
	}

	$data = trim($ln);
	$filename = "parts_report" . $dt . ".csv";

	if(!empty($data) && !empty($filename)){
		exportRowData($data,"excel",$filename);
	}
	
	exit();
?>