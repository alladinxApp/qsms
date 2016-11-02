<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("PrintWorkOrder_model.php");
	require_once("functions.php");
	
	$image = 'images/logo.png';
	$estimaterefno = $_SESSION['estimaterefno'];
	
	$sqlwo_mst = new v_service_master;
	$qrywo_mst1 = $dbo->query($sqlwo_mst->Query("WHERE estimate_refno = '$estimaterefno'"));
	$qrywo_mst = mysql_query($sqlwo_mst->Query("WHERE estimate_refno = '$estimaterefno'"));
	$numwo_mst = count($qrywo_mst);
	$rowwo_mst = mysql_fetch_array($qrywo_mst);

	$servicemst = $rowwo_mst;
	foreach($qrywo_mst1 as $rowwo_mst){
		$customerid = $rowwo_mst['customer_id'];
		$worefno = $rowwo_mst['wo_refno'];
		$creaby = $rowwo_mst['created_by'];
	}
	
	$sql_cust = "SELECT * FROM tbl_customer WHERE cust_id = '$customerid'";
	$qry_cust = mysql_query($sql_cust);
		
	$sql_jc = "SELECT * FROM v_jobclock_master WHERE wo_refno = '$worefno'";
	$qry_jc = mysql_query($sql_jc);
	$row_jc = mysql_fetch_array($qry_jc);

	$sql_user = "SELECT * FROM v_users WHERE username = '$creaby'";
	$qry_user = mysql_query($sql_user);
	while($row_user = mysql_fetch_array($qry_user)){
		$user_fullname = $row_user['name'];
	}
	
	$sql_histmst = new v_service_master;
	$qry_histmst = mysql_query($sql_histmst->Query("WHERE customer_id = '$customerid' AND trans_status = '6' ORDER BY transaction_date DESC LIMIT 0,3"));
	foreach($qry_histmst as $row_histmst){
		if(!empty($row_histmst)){
			$historymst[] = array("rono" => $row_histmst['wo_refno'],
								"rodate" => dateFormat($row_histmst['transaction_date'],"Y-m-d h:i:s"),
								"odometer" => $row_histmst['odometer']);
		}
	}
	
	$sql_histdtl = new v_job_history;
	$qry_histdtl = $dbo->query($sql_histdtl->Query("WHERE customer_id = '$customerid'"));
	
	for($i=0;$i<count($historymst);$i++){
		foreach($qry_histdtl as $row_histdtl){
			if($row_histdtl['wo_refno'] == $historymst[$i]['rono']){
				$job[] = $row_histdtl['job'];
			}
		}
		$jobhistory[] = $job;
	}
	
	while($row_cust = mysql_fetch_assoc($qry_cust)){
		$customername = $row_cust['firstname'] . ' ' . $row_cust['middlename'] . ' ' . $row_cust['lastname'];
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
	$pdf->setCompanyInfo('FAST QUICK SERVICE','#100 President Ave. BF Home Paranaque, City','801-6291');
	$pdf->setWOMST($servicemst);
	$pdf->setJCMST($row_jc);
	$pdf->setServiceAdvisor($user_fullname);
	$pdf->setServiceDetail($servicedtl);
	$pdf->setCustomerInfo($customer);
	$pdf->setHistoryMST($historymst);
	$pdf->setJobHistory($jobhistory);
	
	$pdf->AddPage();
	$pdf->ImprovedTable();
	// I = WEB VIEW, D = DOWNLOAD PDF FILE
	$pdf->Output($worefno . '.pdf','I');
	$_SESSION['estimaterefno'] = null;
?>