<?php
	session_start();
	require_once("conf/db_connection.php");
	require_once("PrintOnlineEstimate_model.php");
	require_once("functions.php");
	
	$image = 'images/logo.png';
	$oe_id = $_SESSION['oe_id'];
	
	$sqlwo_mst = "SELECT * FROM v_online_estimate_master WHERE oe_id = '$oe_id'";
	$servicemst = $dbo->query($sqlwo_mst);
	$numwo_mst = $servicemst->rowCount();
	$row_mst = $servicemst->fetchAll();

	if($numwo_mst == 0){
		echo '<script>window.location="estimates_main.php"</script>';
	}

	$servicemst = null;
	$servicedtl = null;
	
	$sql_servicedtl = "SELECT * FROM v_online_estimate_detail WHERE oe_id = '$oe_id'";
	$servicedtl = $dbo->query($sql_servicedtl);
	$row_dtl = $servicedtl->fetchAll();
	
	

	$pdf = new PrintEstimate;
	
	// Data loading
	$pdf->setCompanyInfo('FAST QUICK SERVICE','President Ave. BF Home Paranaque, City','801-6291');
	$pdf->setMaster($row_mst);
	$pdf->setDetails($row_dtl);
	
	$pdf->AddPage();
	$pdf->ImprovedTable();

	// I = WEB VIEW, D = DOWNLOAD PDF FILE
	$pdf->Output($oe_id . '.pdf','D');
	$_SESSION['oe_id'] = null;
?>