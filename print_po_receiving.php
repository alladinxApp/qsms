<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("PrintPurchaseOrder_model.php");
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
		$customernumber = $rowwo_mst['landline'];
		$customerfax = $rowwo_mst['fax'];
		$customermobile = $rowwo_mst['mobile'];
		$porefno = $rowwo_mst['po_refno'];
		$discount = $rowwo_mst['discount'];
	}
	
	$sqlpo_mst = "SELECT * FROM v_po_master WHERE po_refno = '$porefno'";
	$respo_mst = $dbo->query($sqlpo_mst);
	
	foreach($respo_mst as $rowPOMST){
		$discount = $rowPOMST['discount'];
	}
	
	$sqlwo_dtl = "SELECT * FROM v_po_detail WHERE po_refno = '$porefno'";
	$qrywo_dtl = $dbo->query($sqlwo_dtl);
	
	$sqlwo_dtl_accessory = "SELECT * FROM v_po_detail_accessory WHERE po_refno = '$porefno'";
	$qrywo_dtl_accessory = $dbo->query($sqlwo_dtl_accessory);
	
	$sqlwo_dtl_job = "SELECT * FROM v_po_detail_job WHERE po_refno = '$porefno'";
	$qrywo_dtl_job = $dbo->query($sqlwo_dtl_job);
	
	$sqlwo_dtl_make = "SELECT * FROM v_po_detail_make WHERE po_refno = '$porefno'";
	$qrywo_dtl_make = $dbo->query($sqlwo_dtl_make);
	
	$sqlwo_dtl_material = "SELECT * FROM v_po_detail_material WHERE po_refno = '$porefno'";
	$qrywo_dtl_material = $dbo->query($sqlwo_dtl_material);
	
	$sqlwo_dtl_parts = "SELECT * FROM v_po_detail_parts WHERE po_refno = '$porefno'";
	$qrywo_dtl_parts = $dbo->query($sqlwo_dtl_parts);
	
	if($numwo_mst == 0){
		echo '<script>window.location="po_receiving_menu.php"</script>';
	}
	
	$pdf = new PrintPurchaseOrder;
	
	// Data loading
	$pdf->setImageLogo($image);
	$pdf->setCompanyInfo('FAST QUICK SERVICE','President Ave. BF Home Paranaque, City','801-6291');
	$pdf->setPORefNo($porefno,$transdate);
	$pdf->setAccessoryData($qrywo_dtl_accessory);
	$pdf->setJobData($qrywo_dtl_job);
	$pdf->setMakeData($qrywo_dtl_make);
	$pdf->setMaterialData($qrywo_dtl_material);
	$pdf->setPartsData($qrywo_dtl_parts);
	$pdf->setEstimateData($remarks,$discount);
	$pdf->setVehicleInfo($plateno,$model,$variant,$make,$color,$year);
	$pdf->setCustomerInfo($customername,$customeraddress,$customernumber,$customerfax,$customermobile);
	
	$pdf->AddPage();
	$pdf->ImprovedTable();
	// I = WEB VIEW, D = DOWNLOAD PDF FILE
	$pdf->Output($porefno . '.pdf','D');
	$_SESSION['estimaterefno'] = null;
?>