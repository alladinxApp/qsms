<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("PrintPayment_model.php");
	require_once("functions.php");
	
	$image = 'images/logo1.png';
	$id = $_SESSION['porefno'];
	
	$sqlpo_mst = "SELECT * FROM v_po_mst WHERE po_reference_no = '$id'";
	$qrypo_mst = mysql_query($sqlpo_mst);
	$numpo_mst = mysql_num_rows($qrypo_mst);

	$sqlpo_dtl = "SELECT * FROM v_po_dtl WHERE po_reference_no = '$id' ORDER BY seqno";
	$qrypo_dtl = mysql_query($sqlpo_dtl);
	
	if($numpo_mst == 0){
		echo '<script>window.location="po_menu.php"</script>';
	}

	while($row = mysql_fetch_array($qrypo_mst)){
		$pomst = array("cv_reference_no" => $row['cv_reference_no']
					,"payment_date" => dateFormat($row['payment_date'],"M d, Y")
					,"supplier_name" => $row['supplier_name']
					,"supplier_addr" => $row['supplier_address']
					,"supplier_phone" => $row['supplier_phone']
					,"deliver_to" => $row['deliver_to']
					,"delivery_addr" => $row['delivery_address']
					,"special" => $row['special_instruction']
					,"sub_total" => $row['sub_total']
					,"vat" => $row['vat']
					,"discount" => $row['discount']
					,"total_amount" => $row['total_amount']);
	}

	while($row = mysql_fetch_array($qrypo_dtl)){
		$podtl[] = array("item_code" => $row['item_code']
					,"item_desc" => $row['item_description']
					,"qty" => $row['quantity']
					,"price" => $row['price']
					,"total" => $row['total']);
	}
	
	$company = array("companyname" => "FAST QUICK SERVICE"
					,"companyaddr" => "#100 President Ave. BF Home Paranaque, City"
					,"companytelno" => "801-6291");
	$pdf = new PrintPayment;
	
	// Data loading
	$pdf->setImageLogo($image);
	$pdf->setCompanyInfo($company);
	$pdf->setMaster($pomst);
	$pdf->setDetails($podtl);
	
	$pdf->AddPage();
	$pdf->ImprovedTable();
	// I = WEB VIEW, D = DOWNLOAD PDF FILE
	$pdf->Output($estimaterefno . '.pdf','I');
	// $_SESSION['porefno'] = null;
?>