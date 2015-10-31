<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("PrintEstimate_model.php");
	require_once("functions.php");
	
	$image = 'images/logo.png';
	$estimaterefno = $_SESSION['estimaterefno'];
	
	$sqlwo_mst = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
	$qrywo_mst = mysql_query($sqlwo_mst);
	$numwo_mst = mysql_num_rows($qrywo_mst);
	
	while($rowwo_mst = mysql_fetch_array($qrywo_mst)){
		$remarks = $rowwo_mst['remarks'];
		$plateno = $rowwo_mst['plate_no'];
		$model = $rowwo_mst['model_desc'];
		$variant = $rowwo_mst['variant'];
		$make = $rowwo_mst['make_desc'];
		$color = $rowwo_mst['color_desc'];
		$year = $rowwo_mst['year_desc'];
		$porefno = $rowwo_mst['po_refno'];
		$transdate = $rowwo_mst['transaction_date'];
		$customername = $rowwo_mst['customername'];
		$customeraddress = $rowwo_mst['cust_address'];
		$customerfax = $rowwo_mst['fax'];
		$customermobile = $rowwo_mst['mobile'];
		$customernumber = $rowwo_mst['landline'];
		$discount = $rowwo_mst['discount'];
		$discountedprice = $rowwo_mst['discounted_price'];
		$subtotal = $rowwo_mst['subtotal_amount'];
		$total_amount = $rowwo_mst['total_amount'];
		$odometer = $rowwo_mst['odometer'];
	}
	
	$servicemst = array("discount" => $discount,
						"discounted_price" => $discountedprice,
						"subtotal" => $subtotal_amount,
						"total_amount" => $total_amount,
						"odometer" => $odometer);
	
	$sql_servicedtl = "SELECT * FROM tbl_service_detail WHERE estimate_refno = '$estimaterefno'";
	$qry_servicedtl = mysql_query($sql_servicedtl);
	
	$servicedtl = array();
	while($row_servicedtl = mysql_fetch_assoc($qry_servicedtl)){
		switch($row_servicedtl['type']){
			case "job": 
					$sql_dtl = "SELECT * FROM tbl_job WHERE job_id = '$row_servicedtl[id]' ORDER BY job_id LIMIT 0,1";
					$qry_dtl = mysql_query($sql_dtl);
					$row_dtl = mysql_fetch_assoc($qry_dtl);
					$desc = $row_dtl['job'];
				break;
			case "parts": 
					$sql_dtl = "SELECT * FROM tbl_parts WHERE parts_id = '$row_servicedtl[id]' ORDER BY parts_id LIMIT 0,1";
					$qry_dtl = mysql_query($sql_dtl);
					$row_dtl = mysql_fetch_assoc($qry_dtl);
					$desc = $row_dtl['parts'];
				break;
			case "material": 
					$sql_dtl = "SELECT * FROM tbl_material WHERE material_id = '$row_servicedtl[id]' ORDER BY material_id LIMIT 0,1";
					$qry_dtl = mysql_query($sql_dtl);
					$row_dtl = mysql_fetch_assoc($qry_dtl);
					$desc = $row_dtl['material'];
				break;
			case "accessory": 
					$sql_dtl = "SELECT * FROM tbl_accessory WHERE accessory_id = '$row_servicedtl[id]' ORDER BY accessory_id LIMIT 0,1";
					$qry_dtl = mysql_query($sql_dtl);
					$row_dtl = mysql_fetch_assoc($qry_dtl);
					$desc = $row_dtl['accessory'];
				break;
			default: break;
		}
		$servicedtl[] = array("desc" => $desc,
							"type" => $row_servicedtl['type'],
							"qty" => $row_servicedtl['qty'],
							"amount" => $row_servicedtl['amount']);
	}
	
	if($numwo_mst == 0){
		echo '<script>window.location="estimate_menu.php"</script>';
	}
	
	$pdf = new PrintEstimate;
	
	// Data loading
	//$pdf->setImageLogo($image);
	$pdf->setCompanyInfo('FAST QUICK SERVICE','Alabang, Muntinlupa City','801-6291');
	$pdf->setEstimateRefNo($estimaterefno,$transdate);
	//$pdf->setAccessoryData($qrywo_dtl_accessory);
	//$pdf->setJobData($qrywo_dtl_job);
	//$pdf->setMakeData($qrywo_dtl_make);
	//$pdf->setMaterialData($qrywo_dtl_material);
	//$pdf->setPartsData($qrywo_dtl_parts);
	//$pdf->setEstimateData($remarks,$discount);
	$pdf->setMaster($servicemst);
	$pdf->setDetails($servicedtl);
	$pdf->setVehicleInfo($plateno,$model,$variant,$make,$color,$year);
	$pdf->setCustomerInfo($customername,$customeraddress,$customernumber,$customerfax,$customermobile);
	
	$pdf->AddPage();
	$pdf->ImprovedTable();
	// I = WEB VIEW, D = DOWNLOAD PDF FILE
	$pdf->Output($estimaterefno . '.pdf','D');
	$_SESSION['estimaterefno'] = null;
?>