<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("PrintRR_model.php");
	require_once("functions.php");
	
	$image = 'images/logo1.png';
	$id = $_SESSION['porefno'];
	
	$sqlpo_mst = "SELECT * FROM v_po_mst WHERE po_reference_no = '$id'";
	$qrypo_mst = mysql_query($sqlpo_mst);
	$numpo_mst = mysql_num_rows($qrypo_mst);

	$sqlrr_mst = "SELECT * FROM v_rr_mst WHERE po_reference_no = '$id'";
	$qryrr_mst = mysql_query($sqlrr_mst);

	$ttldiscount = 0;
	$ttlsubtotal = 0;
	$ttlvat = 0;
	$ttltotal = 0;
	$cntrr = 0;
	while($row = mysql_fetch_array($qryrr_mst)){
		$ttldiscount += $row['discount'];
		$ttlsubtotal += $row['sub_total'];
		$ttlvat += $row['vat'];
		$ttltotal += $row['total_amount'];
		
		$rrrefdate .= $row['rr_reference_no'] . "(" . dateFormat($row['rr_date'],"M d, Y") . "),";

		if($cntrr % 2){
			$rrrefdate = rtrim($rrrefdate,",");
			$rr_ref_date[] = $rrrefdate;
			$rrrefdate = null;
		}

		$cntrr++;
	}
	
	if(!empty($rrrefdate)){
		$rrrefdate = rtrim($rrrefdate,",");
		$rr_ref_date[] = $rrrefdate;
	}

	$sqlrr_dtl = "SELECT * FROM v_rr_dtl WHERE po_reference_no = '$id' ORDER BY seqno";
	$qryrr_dtl = mysql_query($sqlrr_dtl);
	
	if($numpo_mst == 0){
		echo '<script>window.location="po_menu.php"</script>';
	}

	while($row = mysql_fetch_array($qrypo_mst)){
		$rrmst = array("po_reference_no" => $row['po_reference_no']
					,"po_date" => dateFormat($row['po_date'],"M d, Y")
					,"supplier_name" => $row['supplier_name']
					,"supplier_addr" => $row['supplier_address']
					,"supplier_phone" => $row['supplier_phone']
					,"deliver_to" => $row['deliver_to']
					,"delivery_addr" => $row['delivery_address']
					,"special" => $row['special_instruction']
					,"sub_total" => $ttlsubtotal
					,"vat" => $ttlvat
					,"discount" => $ttldiscount
					,"total_amount" => $ttltotal
					,"rr_ref_date" => $rr_ref_date);
	}

	while($row = mysql_fetch_array($qryrr_dtl)){
		$rrdtl[] = array("item_code" => $row['item_code']
					,"item_desc" => $row['item_description']
					,"qty" => $row['quantity']
					,"rr_qty" => $row['quantity']
					,"price" => $row['price']
					,"total" => $row['rr_total']);
	}
	
	$company = array("companyname" => "FAST QUICK SERVICE"
					,"companyaddr" => "#100 President Ave. BF Home Paranaque, City"
					,"companytelno" => "801-6291");
	$pdf = new PrintRR;
	
	// Data loading
	$pdf->setImageLogo($image);
	$pdf->setCompanyInfo($company);
	$pdf->setMaster($rrmst);
	$pdf->setDetails($rrdtl);
	
	$pdf->AddPage();
	$pdf->ImprovedTable();
	// I = WEB VIEW, D = DOWNLOAD PDF FILE
	$pdf->Output($estimaterefno . '.pdf','I');
	$_SESSION['porefno'] = null;
?>