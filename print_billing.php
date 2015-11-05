<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("PrintBilling_model.php");
	require_once("functions.php");
	
	$billingrefno = $_SESSION['billingrefno'];
	$user = $_SESSION['username'];
	
	$sql_billing = "SELECT tbl_billing.*,tbl_service_master.estimate_refno 
						FROM tbl_billing JOIN tbl_service_master
						ON tbl_billing.wo_refno = tbl_service_master.wo_refno 
						WHERE billing_refno = '$billingrefno' ORDER BY billing_refno";
	$qry_billing = mysql_query($sql_billing);
	$wo_refno = null;
	while($row_billing = mysql_fetch_array($qry_billing)){
		$wo_refno .= $row_billing['wo_refno'] . ',';
		$est_refno .= $row_billing['estimate_refno'] . ',';
	}

	$wo_refno = str_replace(",", "','",rtrim($wo_refno,","));
	$est_refno = str_replace(",", "','",rtrim($est_refno,","));
	$num_billing = mysql_num_rows($qry_billing);
	
	if($num_billing == 0 || empty($user)){
		echo '<script>window.location="billing_main.php"</script>';
	}
	
	$sql_wo = "SELECT * FROM v_service_master WHERE wo_refno IN('$wo_refno')";
	$qry_wo = mysql_query($sql_wo);
	while($row_wo = mysql_fetch_array($qry_wo)){
		$custid = $row_wo['customer_id'];
		$vehicleid = $row_wo['vehicle_id'];
		$total_amount += $row_wo['total_amount'];
		$total_discount += $row_wo['discount'];
	}

	$sql_est = "SELECT v_service_detail.*,
			(SELECT tbl_accessory.accessory FROM tbl_accessory WHERE tbl_accessory.accessory_id = v_service_detail.id) AS accessory_name,
			(SELECT tbl_job.job FROM tbl_job WHERE tbl_job.job_id = v_service_detail.id) AS job_name,
			(SELECT tbl_material.material FROM tbl_material WHERE tbl_material.material_id= v_service_detail.id) AS material_name,
			(SELECT tbl_parts.parts FROM tbl_parts WHERE tbl_parts.parts_id = v_service_detail.id) AS parts_name
			FROM v_service_detail WHERE estimate_refno IN('$est_refno') ORDER BY v_service_detail.type";
	$qry_est = mysql_query($sql_est);

	while($est_res = mysql_fetch_array($qry_est)){
		if(!empty($est_res['estimate_refno'])){
			$row_est[] = array("estimate_refno" => $est_res['estimate_refno'],
							"type" => $est_res['type'],
							"id" => $est_res['id'],
							"amount" => $est_res['amount'],
							"qty" => $est_res['qty'],
							"accessory_name" => $est_res['accessory_name'],
							"job_name" => $est_res['job_name'],
							"material_name" => $est_res['material_name'],
							"parts_name" => $est_res['parts_name']);
		}
	}

	$sql_custinfo = "SELECT * FROM v_customer WHERE cust_id = '$custid'";
	$qry_custinfo = mysql_query($sql_custinfo);
	$row_custinfo = mysql_fetch_array($qry_custinfo);
	
	$sql_vehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$vehicleid'";
	$qry_vehicle = mysql_query($sql_vehicle);
	$row_vehicle = mysql_fetch_array($qry_vehicle);
	
	$sql_jobclock = "SELECT * FROM v_jobclock_master WHERE wo_refno = '$wo_refno'";
	$qry_jobclock = mysql_query($sql_jobclock);
	$row_jobclock = mysql_fetch_array($qry_jobclock);
	
	$sql_billingmst = "SELECT * FROM v_billing WHERE billing_refno = '$billingrefno'";
	$qry_billingmst = mysql_query($sql_billingmst);
	$row_billingmst = mysql_fetch_array($qry_billingmst);
	$num_billingmst = mysql_num_rows($qry_billingmst);
	
	$compinfo = array("companyname" => 'FAST QUICK SERVICE'
					,"companyaddress" => 'President Ave. BF Home Paranaque, City'
					,"companyno" => '(02)801-6291 / (02)587-5305'
					,"mobileno" => '(+63)917-578-8792'
					,"tin" => '108-558-118-000');
	
	$pdf = new PrintBilling;
	
	// Data loading
	$pdf->setCompanyInfo($compinfo);
	$pdf->setVehicleInfo($row_vehicle);
	$pdf->setCustomerInfo($row_custinfo);
	$pdf->setServiceMaster($row_wo);
	$pdf->setJobclock($row_jobclock);
	$pdf->setBillingMaster($row_billingmst,$num_billingmst);
	$pdf->setServiceDetail($row_est);
	$pdf->setUser($user);
	$pdf->setPayments($total_amount,$total_discount);
	
	$pdf->AddPage();
	$pdf->ImprovedTable();

	// I = WEB VIEW, D = DOWNLOAD PDF FILE
	$pdf->Output($billingrefno . '.pdf','I');
	//$_SESSION['billingrefno'] = null;
?>