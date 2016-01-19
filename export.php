<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$report = $_POST['report'];
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");
		$ln = null;
		switch($report){
			// 13. SALES REPORT
			case "salesreport":
					$totalsales = 0;
					$totallabor = 0;
					$totallubricants = 0;
					$totalsublet = 0;
					$totalparts = 0;
					$totaldiscount = 0;
					$ln = null;
					$where = null;
					$ptype = $_POST['txtptype'];
					$cust = $_POST['txtcust'];

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					if(!empty($cust)){
						$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$cust'";
						$rescustomer = $dbo->query($qrycustomer);

						foreach($rescustomer as $rowcustomer){
							$custname = $rowcustomer['custname'];
						}

						$where .= "AND customer_id = '$cust'";
					}

					if(!empty($ptype)){
						$qrypayment = "SELECT * FROM v_payment WHERE payment_id = '$ptype'";
						$respayment = $dbo->query($qrypayment);

						foreach($respayment as $rowpayment){
							$paymentmode = $rowpayment['payment'];
						}

						$where .= "AND payment_id = '$ptype'";
					}

					$sql_lbs_master = "SELECT v_sales.*,v_service_detail_job.job_name 
						FROM v_sales
							JOIN v_service_detail_job ON v_service_detail_job.estimate_refno = v_sales.estimate_refno
			 			WHERE 1 AND v_sales.transaction_date between '$dtfrom' AND '$dtto' $where
			 			ORDER BY v_sales.transaction_date";
					$qry_lbs_master = mysql_query($sql_lbs_master);
					$qry_mst = mysql_query($sql_lbs_master);

					// $estrefno = null;
					// while($row_mst = mysql_fetch_array($qry_mst)){
					// 	$estrefno .= "'$row_mst[estimate_refno]',";
					// }
					// $estrefno = rtrim($estrefno,",");
					
					// $job = null;
					// $sql_lbs_detail = "SELECT * FROM v_service_detail_job WHERE estimate_refno IN($estrefno) limit 0,1";
					// $qry_lbs_detail = mysql_query($sql_lbs_detail);
					// while($row_lbs_detail = mysql_fetch_array($qry_lbs_detail)){
					// 	$job .= $row_lbs_detail['job_name'] . " ";
					// }
					// $job = rtrim($job,",");

					$ln .= "SALES SUMMARY REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";

					if(!empty($cust)){
						$ln .= "Customer: ," . $custname . "\r\n";
					}else{
						$ln .= "Customer: ,ALL\r\n";
					}
					
					if(!empty($ptype)){
						$ln .= "Payment Type: ," . $paymentmode . "\r\n";
					}else{
						$ln .= "Payment Type: ,ALL\r\n";
					}

					$ln .= "\r\nCustomer Name,Service Availed,Payment Type,Labor,Lubricants,Sublet/Others,Spare Parts,VAT,Discount,Total\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry_lbs_master)){
						$grandtotal = $row['labor'] + $row['lubricants'] + $row['sublet'] + $row['parts'];
						$discounted = ($grandtotal - $row['discount']);
						$vat = ($grandtotal * 0.12);
						$totalamnt = ($discounted + $vat);
						$ln .= $row['customername'] . "," . $row['job_name'] . "," . $row['payment_mode'] . "," . $row['labor'] . "," . $row['lubricants'] . "," . $row['sublet'] . "," . $row['parts'] . "," . $vat . "," . $row['discount'] . "," . $totalamnt . "\r\n";

						$totallabor += $row['labor'];
						$totallubricants += $row['lubricants'];
						$totalsublet += $row['sublet'];
						$totalparts += $row['parts'];
						$totaldiscount += $row['discount'];
						$totalsales += $totalamnt;
						$totalvat += $vat;
						$cnt++;
					}

					$total_units = ($cnt - 1);
					$ave_invoice_price = $totalsales / $total_units; 

					$ln .= ",,Total>>>>>," . $totallabor . "," . $totallubricants . "," . $totalsublet . "," . $totalparts . "," . $totalvat . "," . $totaldiscount . "," . $totalsales . "\r\n";
					$ln .= "TOTAL UNITS RECEIVED," . ": " . $total_units . "\r\n";
					$ln .= "AVERAGE INVOICE PRICE," . ": " . $ave_invoice_price . "\r\n";

					$data = trim($ln);
					$filename = "sales_report_" . $dt . ".csv";
				break;

			// 14. SALES PER UNIT REPORT
			case "salesperunitreport":
					$totalsales = 0;
					$cnt = 0;
					$ave_sales = 0;
					$unit = $_POST['txtplateno'];
					$cust = $_POST['txtcust'];

					if(!empty($cust)){
						$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$cust'";
						$rescustomer = $dbo->query($qrycustomer);

						foreach($rescustomer as $rowcustomer){
							$custname = $rowcustomer['custname'];
						}

						$customers = " AND customer_id = '$cust' ";
					}
					
					if(!empty($unit)){
						$qryvehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$unit'";
						$resvehicle = $dbo->query($qryvehicle);

						foreach($resvehicle as $rowvehicle){
							$plateno = $rowvehicle['plate_no'];
						}

						$units = " AND vehicle_id = '$unit' ";
					}

					$sql_spu_master = "SELECT * FROM v_salesperunit 
			 			WHERE v_salesperunit.transaction_date between '$dtfrom' AND '$dtto'
			 			ORDER BY v_salesperunit.transaction_date";
					$qry_spu_master = mysql_query($sql_spu_master);

					$ln .= "SALES PER UNIT\r\n\r\n";
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";

					if(!empty($cust)){
						$ln .= "Customer: ," . $custname . "\r\n";
					}else{
						$ln .= "Customer: ,ALL\r\n";
					}
					
					if(!empty($unit)){
						$ln .= "Plate No: ," . $plateno . "\r\n";
					}else{
						$ln .= "Plate No: ,ALL\r\n";
					}

					$ln .= "\r\nPlate No, Work Order Ref. No, Amount\r\n";

					while($row = mysql_fetch_array($qry_spu_master)){
						$ln .= $row['plate_no'] . "," . $row['wo_refno'] . "," . $row['total_amount'] . "\r\n";
						$totalsales += $row['total_amount'];
						$cnt++;
					}

					$ln .= ",Total Sales >>>>>," . $totalsales . "\r\n";
					$ave_sales = $totalsales / $cnt;
					$ln .= ",Average Sales >>>>>," . $ave_sales . "\r\n";
					$ln .= ",Total Units >>>>>," . $cnt . "\r\n";
					$data = trim($ln);
					$filename = "sales_per_unit_report_" . $dt . ".csv";
				break;

			// 15. LABOR AND PARTS SUMMARY REPORT
			case "laborandpartssummaryreport":
					$totalsales = 0;
					$totallabor = 0;
					$totallubricants = 0;
					$totalsublet = 0;
					$totalparts = 0;
					$totaldiscount = 0;
					$ln = null;

					$sql_lbs_master = "SELECT * FROM v_laborandpartssummary 
			 			WHERE v_laborandpartssummary.transaction_date between '$dtfrom' AND '$dtto'
			 			ORDER BY v_laborandpartssummary.transaction_date";
					$qry_lbs_master = mysql_query($sql_lbs_master);

					$ln .= "LABOR AND PARTS SUMMARY REPORT\r\n\r\n";
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n\r\n";
					$ln .= "Labor,Lubricants,Sublet/Others,Spare Parts,VAT,Discount,Total\r\n";

					while($row = mysql_fetch_array($qry_lbs_master)){
						$totallabor += $row['labor'];
						$totallubricants += $row['lubricants'];
						$totalsublet += $row['sublet'];
						$totalparts += $row['parts'];
						$totaldiscount += $row['discount'];
						$totalsales += $row['total_amount'];

						$discounted = $row['subtotal_amount'] - $row['discount'];
						$vat = (($discounted / 100) * $row['vat']);
						$totalvat += $vat;
						$ln .= $row['labor'] . "," . $row['lubricants'] . "," . $row['sublet'] . "," . $row['parts'] . "," . $vat . "," . $row['discount'] . "," . $row['total_amount'] . "\r\n";
					}

					$ln .= $totallabor . "," . $totallubricants . "," . $totalsublet . "," . $totalparts . "," . $totalvat . "," . $totaldiscount . "," . $totalsales . "\r\n";

					$data = trim($ln);
					$filename = "labor_and_parts_summary_report_" . $dt . ".csv";
				break;

			// 16. VEHICLE SUMMARY REPORT
			case "vehiclesummaryreport":
					$year = $_POST['txtyear'];
					$totalunits = 0;
					$totaljan = 0;
					$totalfeb = 0;
					$totalmar = 0;
					$totalapr = 0;
					$totalmay = 0;
					$totaljun = 0;
					$totaljul = 0;
					$totalaug = 0;
					$totalsep = 0;
					$totaloct = 0;
					$totalnov = 0;
					$totaldec = 0;
					$ln = null;

					$sql_vs_master = "SELECT * FROM v_vehiclesummary 
			 			WHERE v_vehiclesummary.year = '$year'";
					$qry_vs_master = mysql_query($sql_vs_master);

					$ln .= "VEHICLE SUMMARY REPORT\r\n\r\n";
					$ln .= "Year: ," . $year . "\r\n\r\n";
					$ln .= "January,February,March,April,May,June,July,August,September,October,November,December\r\n";

					while($row = mysql_fetch_array($qry_vs_master)){
						$totaljan += $row['january'];
						$totalfeb += $row['february'];
						$totalmar += $row['march'];
						$totalapr += $row['april'];
						$totalmay += $row['may'];
						$totaljun += $row['june'];
						$totaljul += $row['july'];
						$totalaug += $row['august'];
						$totalsep += $row['september'];
						$totaloct += $row['october'];
						$totalnov += $row['november'];
						$totaldec += $row['december'];
					}

					$totalunits = $totaljan + $totalfeb + $totalmar + $totalapr + $totalmay + $totaljun
								 + $totaljul + $totalaug + $totalsep + $totaloct + $totalnov + $totaldec;
					
					$ln .= $totaljan . "," . $totalfeb . "," . $totalmar . "," . $totalapr . "," . $totalmay . "," . $totaljun
								. "," . $totaljul . "," . $totalaug . "," . $totalsep . "," . $totaloct . "," . $totalnov
								. "," . $totaldec . "\r\n";

					$ln .= ",,,,,,,,,,Total Units>>>>>," . $totalunits . "\r\n";

					$data = trim($ln);
					$filename = "vehicle_summary_report_" . $dt . ".csv";
				break;

			// 17. CUSTOMER SUMMARY REPORT
			case "customersummaryreport":
					$year = $_POST['txtyear'];
					$totalunits = 0;
					$totaljan = 0;
					$totalfeb = 0;
					$totalmar = 0;
					$totalapr = 0;
					$totalmay = 0;
					$totaljun = 0;
					$totaljul = 0;
					$totalaug = 0;
					$totalsep = 0;
					$totaloct = 0;
					$totalnov = 0;
					$totaldec = 0;
					$ln = null;

					$sql_vs_master = "SELECT * FROM v_vehiclesummary 
			 			WHERE v_vehiclesummary.year = '$year'";
					$qry_vs_master = mysql_query($sql_vs_master);

					$ln .= "CUSTOMER SUMMARY REPORT\r\n\r\n";
					$ln .= "Year: ," . $year . "\r\n\r\n";
					$ln .= "January,February,March,April,May,June,July,August,September,October,November,December\r\n";

					while($row = mysql_fetch_array($qry_vs_master)){
						$totaljan += $row['january'];
						$totalfeb += $row['february'];
						$totalmar += $row['march'];
						$totalapr += $row['april'];
						$totalmay += $row['may'];
						$totaljun += $row['june'];
						$totaljul += $row['july'];
						$totalaug += $row['august'];
						$totalsep += $row['september'];
						$totaloct += $row['october'];
						$totalnov += $row['november'];
						$totaldec += $row['december'];
					}

					$totalunits = $totaljan + $totalfeb + $totalmar + $totalapr + $totalmay + $totaljun
								 + $totaljul + $totalaug + $totalsep + $totaloct + $totalnov + $totaldec;
					
					$ln .= $totaljan . "," . $totalfeb . "," . $totalmar . "," . $totalapr . "," . $totalmay . "," . $totaljun
								. "," . $totaljul . "," . $totalaug . "," . $totalsep . "," . $totaloct . "," . $totalnov
								. "," . $totaldec . "\r\n";

					$ln .= ",,,,,,,,,,Total Units>>>>>," . $totalunits . "\r\n";

					$data = trim($ln);
					$filename = "customer_summary_report_" . $dt . ".csv";
				break;
			case "saleshistory":
					$cust = $_POST['txtcust'];
					$unit = $_POST['txtunits'];
					$job = $_POST['txtjob'];
					$dtfrom = $from . " 00:00:000";
					$dtto = $to . " 23:59:000";
					$dt = date("Ymdhis");

					$totalsales = 0;
					$cnt = 0;
					$ave_sales = 0;

					if(!empty($cust)){
						$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$cust'";
						$rescustomer = $dbo->query($qrycustomer);

						foreach($rescustomer as $rowcustomer){
							$custname = $rowcustomer['custname'];
						}

						$customers = " AND customer_id = '$cust' ";
					}
					
					if(!empty($unit)){
						$qryvehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$unit'";
						$resvehicle = $dbo->query($qryvehicle);

						foreach($resvehicle as $rowvehicle){
							$plateno = $rowvehicle['plate_no'];
						}

						$units = " AND vehicle_id = '$unit' ";
					}

					if(!empty($job)){
						$qryjob = "SELECT * FROM v_job WHERE job_id = '$job'";
						$resjob = $dbo->query($qryjob);

						foreach($resvehicle as $rowvehicle){
							$jobname = $rowvehicle['job'];
						}

						$jobs = " AND id = '$job' ";
					}

					$sql_sh_master = "SELECT * FROM v_sales_history
							WHERE v_sales_history.transaction_date between '$dtfrom' AND '$dtto'
							$units $customers $jobs
							ORDER BY v_sales_history.transaction_date";
					$qry_sh_master = mysql_query($sql_sh_master);

					$ln .= "SALES HISTORY REPORT\r\n\r\n";
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";

					if(!empty($cust)){
						$ln .= "Customer: ," . $custname . "\r\n";
					}else{
						$ln .= "Customer: ,ALL\r\n";
					}
					
					if(!empty($unit)){
						$ln .= "Plate No: ," . $plateno . "\r\n";
					}else{
						$ln .= "Plate No: ,ALL\r\n";
					}

					if(!empty($job)){
						$ln .= "Service Type: ," . $jobname . "\r\n";
					}else{
						$ln .= "Service Type: ,ALL\r\n";
					}

					$ln .= "\r\nService Type, Customer, Work Order Ref. No, Amount\r\n";

					while($row = mysql_fetch_array($qry_sh_master)){
						$ln .= $row['job'] . "," . $row['custname'] . "," . $row['wo_refno'] . "," . $row['amount'] . "\r\n";
						$totalsales += $row['amount'];
						$cnt++;
					}

					$ln .= ",,Total Sales >>>>>," . $totalsales . "\r\n";
					$ave_sales = $totalsales / $cnt;
					$ln .= ",,Average Sales >>>>>," . $ave_sales . "\r\n";
					$ln .= ",,Total Units >>>>>," . $cnt . "\r\n";
					$ln .= ",,Total Customer >>>>>," . $cnt . "\r\n";
					$data = trim($ln);
					$filename = "sales_history_report_" . $dt . ".csv";
				break;
			case "servicetypesummaryreport":
					$job = $_POST['txtjob'];
					$dtfrom = $from . " 00:00:000";
					$dtto = $to . " 23:59:000";
					$dt = date("Ymdhis");

					$totalsales = 0;
					$cnt = 0;
					$ave_sales = 0;

					if(!empty($job)){
						$qryjob = "SELECT * FROM v_job WHERE job_id = '$job'";
						$resjob = $dbo->query($qryjob);

						foreach($resvehicle as $rowvehicle){
							$jobname = $rowvehicle['job'];
						}

						$jobs = " AND id = '$job' ";
					}

					$sql_sh_master = "SELECT * FROM v_sales_history
							WHERE v_sales_history.transaction_date between '$dtfrom' AND '$dtto'
							$jobs
							ORDER BY v_sales_history.transaction_date";
					$qry_sh_master = mysql_query($sql_sh_master);

					$ln .= "SERVICE TYPE SUMMARY REPORT\r\n\r\n";
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";

					if(!empty($job)){
						$ln .= "Service Type: ," . $jobname . "\r\n";
					}else{
						$ln .= "Service Type: ,ALL\r\n";
					}

					$ln .= "\r\nService Type, Work Order Ref. No, Amount\r\n";

					while($row = mysql_fetch_array($qry_sh_master)){
						$ln .= $row['job'] . "," . $row['wo_refno'] . "," . $row['amount'] . "\r\n";
						$totalsales += $row['amount'];
						$cnt++;
					}

					$ln .= ",Total Sales >>>>>," . $totalsales . "\r\n";
					$ave_sales = $totalsales / $cnt;
					$ln .= ",Average Sales >>>>>," . $ave_sales . "\r\n";
					$ln .= ",Total Units >>>>>," . $cnt . "\r\n";
					$ln .= ",Total Customer >>>>>," . $cnt . "\r\n";
					$data = trim($ln);
					$filename = "service_type_summary_report" . $dt . ".csv";
				break;
			case "repairhistory":
					$cust = $_POST['txtcust'];
					$unit = $_POST['txtunits'];
					$job = $_POST['txtjob'];
					$dtfrom = $from . " 00:00:000";
					$dtto = $to . " 23:59:000";
					$dt = date("Ymdhis");

					$totalsales = 0;
					$cnt = 0;
					$ave_sales = 0;

					if(!empty($cust)){
						$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$cust'";
						$rescustomer = $dbo->query($qrycustomer);

						foreach($rescustomer as $rowcustomer){
							$custname = $rowcustomer['custname'];
						}

						$customers = " AND customer_id = '$cust' ";
					}
					
					if(!empty($unit)){
						$qryvehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$unit'";
						$resvehicle = $dbo->query($qryvehicle);

						foreach($resvehicle as $rowvehicle){
							$plateno = $rowvehicle['plate_no'];
						}

						$units = " AND vehicle_id = '$unit' ";
					}

					if(!empty($job)){
						$qryjob = "SELECT * FROM v_job WHERE job_id = '$job'";
						$resjob = $dbo->query($qryjob);

						foreach($resvehicle as $rowvehicle){
							$jobname = $rowvehicle['job'];
						}

						$jobs = " AND id = '$job' ";
					}

					$sql_sh_master = "SELECT * FROM v_sales
							WHERE v_sales.transaction_date between '$dtfrom' AND '$dtto'
							$units $customers $jobs
							ORDER BY v_sales.transaction_date";
					$qry_sh_master = mysql_query($sql_sh_master);

					$ln .= "REPAIR HISTORY REPORT\r\n\r\n";
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";

					if(!empty($cust)){
						$ln .= "Customer: ," . $custname . "\r\n";
					}else{
						$ln .= "Customer: ,ALL\r\n";
					}
					
					if(!empty($unit)){
						$ln .= "Plate No: ," . $plateno . "\r\n";
					}else{
						$ln .= "Plate No: ,ALL\r\n";
					}

					if(!empty($job)){
						$ln .= "Service Type: ," . $jobname . "\r\n";
					}else{
						$ln .= "Service Type: ,ALL\r\n";
					}

					$ln .= "\r\nPlate No, Service Remarks, Transaction Date, Customer Name\r\n";

					while($row = mysql_fetch_array($qry_sh_master)){
						$ln .= $row['plate_no'] . "," . $row['remarks'] . "," . $row['transaction_date'] . "," . $row['customername'] . "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "repair_history_report_" . $dt . ".csv";
				break;
			case "employee_compensation":
					$empl = $_POST['txtempl'];
					$worefno = $_POST['txtworefno'];
					$jobtype = $_POST['txtjobtype'];

					$where = null;
					if(!empty($empl)){
						$where .= " AND tech_name LIKE '%$empl%'";
					}

					if(!empty($worefno)){
						$where .= " AND workorder_refno LIKE '%$emworefnopl%'";
					}

					if(!empty($jobtype)){
						$where .= " AND jobname LIKE '%$jobtype%'";
					}

					$sql_emplcompensation = "SELECT * FROM v_employeecompensation 
			 			WHERE v_employeecompensation.transaction_date between '$dtfrom' AND '$dtto' 
			 			 $where
			 			ORDER BY v_employeecompensation.transaction_date";
					$qry_emplcompensation = mysql_query($sql_emplcompensation);

					$ln .= "LABOR AND PARTS SUMMARY REPORT\r\n\r\n";
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";

					if(!empty($empl)){
						$ln .= "Employee: ," . $empl . "\r\n";
					}

					if(!empty($worefno)){
						$ln .= "Work Order Ref#: ," . $worefno . "\r\n";
					}

					if(!empty($jobtype)){
						$ln .= "Work Type: ," . $jobtype . "\r\n";
					}

					$ln .= "\r\n";
					$ln .= "Employee Name, Date, Work Order Ref#, Amount, Work Type\r\n";

					$total = 0;
					while($row = mysql_fetch_array($qry_emplcompensation)){
						$ln .= $row['tech_name'] . "," . dateFormat($row['transaction_date'],"Y-m-d") . "," . $row['workorder_refno']
								. "," . $row['amount'] . "," . $row['jobname'] . "\r\n";

						$total += $row['amount'];
					}
					$ln .= ",,Total>>>>>>>>>>," . number_format($total,2);

					$data = trim($ln);
					$filename = "employee_compensation_report" . $dt . ".csv";
				break;
			case "low_stock":
					$sql_ls = "SELECT * FROM v_lowstock
			 			WHERE is_low > 0";
					$qry_ls = mysql_query($sql_ls);

					$ln .= "LOW STOCK REPORT\r\n";
					$ln .= "Parts Code, Description, Low Stock Qty, Stock On Hand\r\n";

					while($row = mysql_fetch_array($qry_ls)){
						$ln .= $row['parts_id'] 
								. "," . $row['parts'] 
								. "," . $row['parts_lowstock']
								. "," . $row['part_onhand'] 
								. "\r\n";
					}

					$data = trim($ln);
					$filename = "low_stock_report" . $dt . ".csv";
				break;
			case "technicianperformancereport":
					$tech = $_POST['txttech'];

					$totalsales = 0;
					$cnt = 0;
					$ave_sales = 0;

					if(!empty($tech)){
						$qrytech = "SELECT * FROM v_employee where emp_id = '$tech'";
						$restech = $dbo->query($qrytech);

						foreach($restech as $rowtech){
							$empname = $rowtech['employee'];
						}

						$emp = " AND emp_id = '$tech' ";
					}

					$sql_tp_master = "SELECT * FROM v_technician_performance
							WHERE v_technician_performance.transaction_date between '$dtfrom' AND '$dtto'
							$emp
							ORDER BY v_technician_performance.transaction_date";
					$qry_tp_master = mysql_query($sql_tp_master);

					$ln .= "TECHNICIAN PERFORMANCE REPORT\r\n";
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";

					if(!empty($tech)){
						$ln .= "Technician: ," . $empname . "\r\n";
					}
					$ln .= "Technician,Work Order#,Committed Date,Completion Date,Hit/Miss,No Of Hours\r\n";
					
					while($row = mysql_fetch_array($qry_tp_master)){
						$jcmsttot_hrs = 0;
						$jcdtltot_hrs = 0;
						$noofhrsmst = 0;
						$noofhrsdtl = 0;
						$totalnoofhrs = 0;

						if($row['committed_date'] < $row['completion_date']){
							$hit_miss = 'MISSED';
						}else{
							$hit_miss = 'HIT';
						}
						$sql_jcdtl = "SELECT * FROM v_jobclock_detail WHERE wo_refno = '$row[wo_refno]'";
						$qry_jcdtl = mysql_query($sql_jcdtl);

						$jcmsttot_hrs = (strtotime($row['job_end']) - strtotime($row['job_start']));

						while($row_jcdtl = mysql_fetch_array($qry_jcdtl)){
							$jcdtltot_hrs += (strtotime($row_jcdtl['time_end']) - strtotime($row_jcdtl['time_start']));
						}

						$noofhrsmst = (($jcmsttot_hrs / 60) / 60);
						$noofhrsdtl = (($jcdtltot_hrs / 60) / 60);

						$totalnoofhrs = ($noofhrsmst - $noofhrsdtl);
						
						$ln .= $row['tech_name'] 
								. "," . $row['wo_refno'] 
								. "," . dateFormat($row['committed_date'],"m-d-Y h:i:s")
								. "," . dateFormat($row['completion_date'],"m-d-Y h:i:s")
								. "," . $hit_miss
								. "," . number_format($totalnoofhrs,2)
								. "\r\n";
					}

					$data = trim($ln);
					$filename = "technician_performance_report" . $dt . ".csv";
				break;
			case "invoicereport":
					$sql_invoice = "SELECT * FROM v_invoice 
			 			WHERE v_invoice.billing_date between '$dtfrom' AND '$dtto'
			 			ORDER BY v_invoice.billing_date";
					$qry_invoice = mysql_query($sql_invoice);

					$ln .= "INVOICE REPORT\r\n\r\n";
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n\r\n";
					$ln .= "Posting Date,Billing Ref#,Customer Code,Customer Name,Item No,Item Desc,Unit Price,Tax Code,Wtax Liable,Line Total,Gross Total,Warehouse,Location,Branch,Cost Center,Vehicle,Conduction Sticker,Maker,Color,Year,Variants\r\n";

					while($row = mysql_fetch_array($qry_invoice)){
						$ln .= dateFormat($row['billing_date'],"F d Y") 
								. "," . $row['billing_refno'] 
								. "," . $row['customer_id']
								. "," . $row['custname'] 
								. "," . $row['parts_id'] 
								. "," . $row['parts']
								. "," . number_format($row['amount'],2) . ",,,," . number_format($row['gross_total'],2) . ",,,,," . $row['plate_no']
								. "," . $row['conduction_sticker']
								. "," . $row['make']
								. "," . $row['color']
								. "," . $row['year']
								. "," . $row['variant']
								. "\r\n";
					}

					$data = trim($ln);
					$filename = "invoice_report" . $dt . ".csv";
				break;
			default: echo 'INVALID URL!'; break;
		}

		if(!empty($data) && !empty($filename)){
			exportRowData($data,"excel",$filename);
		}
	}
	exit();
?>