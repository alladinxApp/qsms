<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("PrintWorkOrder_model.php");
	require_once("functions.php");
	
	$image = 'images/logo.png';
	$estimaterefno = $_SESSION['estimaterefno'];
	
	$sqlwo_mst = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
	$qrywo_mst = mysql_query($sqlwo_mst);
	$numwo_mst = mysql_num_rows($qrywo_mst);
	$rowwo_mst = mysql_fetch_array($qrywo_mst);
	$servicemst = $rowwo_mst;
	$customerid = $rowwo_mst['customer_id'];
	$worefno = $rowwo_mst['wo_refno'];
	$vehicle_id = $rowwo_mst['vehicle_id'];
	
	$sql_cust = "SELECT * FROM tbl_customer WHERE cust_id = '$customerid'";
	$qry_cust = mysql_query($sql_cust);
	
	$sql_vehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$vehicle_id'";
	$qry_vehicle = mysql_query($sql_vehicle);
	$vehicle = mysql_fetch_array($qry_vehicle);
	
	$sql_jc = "SELECT * FROM v_jobclock_master WHERE wo_refno = '$worefno'";
	$qry_jc = mysql_query($sql_jc);
	$row_jc = mysql_fetch_array($qry_jc);
	
	$sql_histmst = "SELECT * FROM v_service_master WHERE customer_id = '$customerid' AND trans_status = '6' ORDER BY transaction_date DESC LIMIT 0,3";
	$qry_histmst = mysql_query($sql_histmst);
	while($row_histmst = mysql_fetch_array($qry_histmst)){
		if(!empty($row_histmst)){
			$historymst[] = array("rono" => $row_histmst['wo_refno'],
								"rodate" => dateFormat($row_histmst['transaction_date'],"Y-m-d h:i:s"),
								"odometer" => $row_histmst['odometer']);
		}
	}
	
	$sql_histdtl = "SELECT *,v_job.job FROM v_service_master,v_service_detail,v_job
				WHERE v_service_master.customer_id = '$customerid' AND 
					v_service_master.estimate_refno = v_service_detail.estimate_refno AND
					v_service_master.trans_status = '6' AND v_service_detail.type = 'job' and
					v_service_detail.id = v_job.job_id";
	$qry_histdtl = mysql_query($sql_histdtl);
	
	for($i=0;$i<count($historymst);$i++){
		$cnt = 1;
		while($row_histdtl = mysql_fetch_array($qry_histdtl)){
			if($row_histdtl['wo_refno'] == $historymst[$i]['rono']){
				$job[] = $row_histdtl['job'];
			}
		}
		$jobhistory[] = $job;
	}
	
	while($row_cust = mysql_fetch_assoc($qry_cust)){
		$customername = $row_cust['firtname'] . ' ' . $row_cust['middlename'] . ' ' . $row_cust['lastname'];
		$address = $row_cust['address'] . ' ' . $row_cust['city'];
		$customer = array("customer_id" => $row_cust['cust_id'],
						"customer_name" => $customername,
						"address" => $address,
						"fax" => $row_cust['fax'],
						"business" => $row_cust['company']
						);
	}
	
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
							"id" => $row_servicedtl['id'],
							"qty" => $row_servicedtl['qty'],
							"amount" => $row_servicedtl['amount']);
	}
	
	if($numwo_mst == 0){
		echo '<script>window.location="estimate_menu.php"</script>';
	}
	
	$pdf = new PrintWorkOrder;
	
	// Data loading
	$pdf->setCompanyInfo('FAST QUICK SERVICE','Alabang, Muntinlupa City','801-6291');
	$pdf->setWOMST($servicemst);
	$pdf->setJCMST($row_jc);
	$pdf->setServiceAdvisor($_SESSION['username']);
	$pdf->setServiceDetail($servicedtl);
	$pdf->setVehicleInfo($vehicle);
	$pdf->setCustomerInfo($customer);
	$pdf->setHistoryMST($historymst);
	$pdf->setJobHistory($jobhistory);
	
	$pdf->AddPage();
	$pdf->ImprovedTable();
	// I = WEB VIEW, D = DOWNLOAD PDF FILE
	$pdf->Output($worefno . '.pdf','I');
	$_SESSION['estimaterefno'] = null;
?>