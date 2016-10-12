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

					$sql_lbs_master = "SELECT *
						FROM v_sales
						WHERE 1 AND v_sales.transaction_date between '$dtfrom' AND '$dtto' $where
			 			ORDER BY v_sales.transaction_date";
					$qry_lbs_master = mysql_query($sql_lbs_master);
					$qry_mst = mysql_query($sql_lbs_master);

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
						$discounted = $grandtotal - $row['discount'];
						if($row['senior_citizen'] == 0){
							$vat = ($discounted * 0.12);
						}else{
							$vat = $discounted * 0.00;
						}
						// $discounted = ($grandtotal - $row['discount']);
						$totalamnt = ($discounted + $vat);
						
						$job = null;
						$sql_lbs_detail = "SELECT job_name FROM v_service_detail_job WHERE estimate_refno = '$row[estimate_refno]'";
						$qry_lbs_detail = mysql_query($sql_lbs_detail);
						while($row_lbs_detail = mysql_fetch_array($qry_lbs_detail)){
							$job[] = $row_lbs_detail['job_name'];
						}

						$ln .= $row['customername'] . "," . $job[0] . "," . $row['payment_mode'] . "," . $row['labor'] . "," . $row['lubricants'] . "," . $row['sublet'] . "," . $row['parts'] . "," . number_format($vat,2,".","") . "," . $row['discount'] . "," . number_format($totalamnt,2,".","") . "\r\n";

						for($i=1;$i<count($job);$i++){
							$ln .= "," . $job[$i] . "\r\n";
						}

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

					$ln .= ",,Total>>>>>," . $totallabor . "," . $totallubricants . "," . $totalsublet . "," . $totalparts . "," . number_format($totalvat,2,".","") . "," . number_format($totaldiscount,2,".","") . "," . number_format($totalsales,2,".","") . "\r\n";
					$ln .= "TOTAL UNITS RECEIVED," . ": " . $total_units . "\r\n";
					$ln .= "AVERAGE INVOICE PRICE," . ": " . number_format($ave_invoice_price,2,".","") . "\r\n";

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
								. "," . $row['yeardesc']
								. "," . $row['variant']
								. "\r\n";
					}

					$data = trim($ln);
					$filename = "invoice_report" . $dt . ".csv";
				break;
			case "poinventoryreport":
					$date = dateFormat($_POST['txtdate'],"Y-m-d");
		
					$item = "ALL ITEMS";
					if(!empty($_POST['txtitem'])){
						$item = $_POST['txtitem'];
					}

					$dtfrom = $date . " 00:00";
					$dtto = $date . " 23:59";
					$dt = date("Ymdhis");

					$itemdesc = "ALL ITEMS";
					$itemcode = null;
					if(!empty($_POST['txtitem'])){
						$itemcode = "SAP_item_code = '$item' AND ";

						$sql_item = "SELECT * FROM tbl_items WHERE SAP_item_code = '$item'";
						$qry_item = mysql_query($sql_item);
						while($row = mysql_fetch_array($qry_item)){
							$itemdesc = $row['item_description'];
						}
					}

					$sql_inv = "SELECT * FROM v_po_inventory
							WHERE $itemcode v_po_inventory.created_date between '$dtfrom' AND '$dtto'
							ORDER BY v_po_inventory.created_date";
					$qry_inv = mysql_query($sql_inv);

					$ln .= "INVENTORY REPORT\r\n\r\n";
					$ln .= "Date: ," . dateFormat($date,"m/d/Y") . "\r\n";
					$ln .= "SAP Code: ," . $item . "\r\n";
					$ln .= "SAP Code: ," . $itemdesc . "\r\n\r\n";
					$ln .= "#,Item Code,SAP Code,Description,Beginning Balance,Received,Received Date,Issued,Issued Date,Ending Balance,Remarks\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry_inv)){
						$ln .= $cnt
								. "," . $row['item_code']
								. "," . $row['SAP_item_code'] 
								. "," . $row['item_description'] 
								. "," . $row['beginning_balance']
								. "," . $row['received']
								. "," . dateFormat($row['received_date'],"F d Y") 
								. "," . $row['issued']
								. "," . dateFormat($row['issued_date'],"F d Y") 
								. "," . $row['ending_balance']
								. "," . $row['remarks']
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "po_inventory_report" . $dt . ".csv";
				break;
			case "stockbalancereport":
					$date = dateFormat($_POST['txtdate'],"Y-m-d");
		
					$item = "ALL ITEMS";
					if(!empty($_POST['txtitem'])){
						$item = $_POST['txtitem'];
					}

					$dtfrom = $date . " 00:00";
					$dtto = $date . " 23:59";
					$dt = date("Ymdhis");

					$itemdesc = "ALL ITEMS";
					$itemcode = null;
					if(!empty($_POST['txtitem'])){
						$itemcode = "SAP_item_code = '$item' AND ";

						$sql_item = "SELECT * FROM tbl_items WHERE SAP_item_code = '$item'";
						$qry_item = mysql_query($sql_item);
						while($row = mysql_fetch_array($qry_item)){
							$itemdesc = $row['item_description'];
						}
					}

					$sql_inv = "SELECT * FROM v_po_inventory
							WHERE $itemcode v_po_inventory.created_date between '$dtfrom' AND '$dtto'
							ORDER BY v_po_inventory.created_date";
					$qry_inv = mysql_query($sql_inv);

					$ln .= "STOCK BALANCE REPORT\r\n\r\n";
					$ln .= "Date: ," . dateFormat($date,"m/d/Y") . "\r\n";
					$ln .= "SAP Code: ," . $item . "\r\n";
					$ln .= "SAP Code: ," . $itemdesc . "\r\n\r\n";
					$ln .= "#,Item Code,SAP Code,Description,Ending Balance\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry_inv)){
						$ln .= $cnt
								. "," . $row['item_code']
								. "," . $row['SAP_item_code'] 
								. "," . $row['item_description']
								. "," . $row['ending_balance']
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "stock_balance_report" . $dt . ".csv";
				break;
			case "oinvreport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT tbl_service_master.*
								,tbl_billing.billing_date
								,tbl_billing.billing_refno
								,tbl_billing.total_amount as billing_amount 
							FROM tbl_service_master
								JOIN tbl_billing ON tbl_billing.wo_refno = tbl_service_master.wo_refno
							WHERE tbl_billing.billing_date BETWEEN '$dtfrom' AND '$dtto'
			 				ORDER BY tbl_billing.billing_date";
					$qry = mysql_query($sql);

					$ln .= "OINV REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "DocNum,DocEntry,DocType,HandWritten,Printed,DocDate,DocDueDate,CardCode,CardName,Address,NumAtCard,DocTotal,AttachmentEntry,DocCurrency,DocRate,Reference1,Reference2,Comments,JournalMemo,PaymentGroupCode,DocTime,SalesPersonCode,TransportationCode,Confirmed,ImportFileNum,SummeryType,ContactPersonCode,ShowSCN,Series,TaxDate,PartialSupply,DocObjectCode,ShipToCode,Indicator,FederalTaxID,DiscountPercent,PaymentReference,DocTotalFc,Form1099,Box1099,RevisionPo,RequriedDate,CancelDate,BlockDunning,Pick,PaymentMethod,PaymentBlock,PaymentBlockEntry,CentralBankIndicator,MaximumCashDiscount,Project,ExemptionValidityDateFrom,ExemptionValidityDateTo,WareHouseUpdateType,Rounding,ExternalCorrectedDocNum,InternalCorrectedDocNum,DeferredTax,TaxExemptionLetterNum,AgentCode,NumberOfInstallments,ApplyTaxOnFirstInstallment,VatDate,DocumentsOwner,FolioPrefixString,FolioNumber,DocumentSubType,BPChannelCode,BPChannelContact,Address2,PayToCode,ManualNumber,UseShpdGoodsAct,IsPayToBank,PayToBankCountry,PayToBankCode,PayToBankAccountNo,PayToBankBranch,BPL_IDAssignedToInvoice,DownPayment,ReserveInvoice,LanguageCode,TrackingNumber,PickRemark,ClosingDate,SequenceCode,SequenceSerial,SeriesString,SubSeriesString,SequenceModel,UseCorrectionVATGroup,DownPaymentAmount,DownPaymentPercentage,DownPaymentType,DownPaymentAmountSC,DownPaymentAmountFC,VatPercent,ServiceGrossProfitPercent,OpeningRemarks,ClosingRemarks,RoundingDiffAmount,ControlAccount,InsuranceOperation347,ArchiveNonremovableSalesQuotation,GTSChecker,GTSPayee,ExtraMonth,ExtraDays,CashDiscountDateOffset,StartFrom,NTSApproved,ETaxWebSite,ETaxNumber,NTSApprovedNumber,EDocGenerationType,EDocSeries,EDocExportFormat,EDocStatus,EDocErrorCode,EDocErrorMessage,DownPaymentStatus,GroupSeries,GroupNumber,GroupHandWritten,ReopenOriginalDocument,ReopenManuallyClosedOrCanceledDocument,CreateOnlineQuotation,POSEquipmentNumber,POSManufacturerSerialNumber,POSCashierNumber,ApplyCurrentVATRatesForDownPaymentsToDraw,ClosingOption,SpecifiedClosingDate,OpenForLandedCosts,RelevantToGTS,AnnualInvoiceDeclarationReference,Supplier,Releaser,Receiver,BlanketAgreementNumber,IsAlteration,AssetValueDate,DocumentDelivery,AuthorizationCode,StartDeliveryDate,StartDeliveryTime,EndDeliveryDate,EndDeliveryTime,VehiclePlate,ATDocumentType,ElecCommStatus,ReuseDocumentNum,ReuseNotaFiscalNum,PrintSEPADirect,FiscalDocNum\r\n";
					$ln .= "DocNum,DocEntry,DocType,Handwrtten,Printed,DocDate,DocDueDate,CardCode,CardName,Address,NumAtCard,DocTotal,AtcEntry,DocCur,DocRate,Ref1,Ref2,Comments,JrnlMemo,GroupNum,DocTime,SlpCode,TrnspCode,Confirmed,ImportEnt,SummryType,CntctCode,ShowSCN,Series,TaxDate,PartSupply,ObjType,ShipToCode,Indicator,LicTradNum,DiscPrcnt,PaymentRef,DocTotalFC,Form1099,Box1099,RevisionPo,ReqDate,CancelDate,BlockDunn,Pick,PeyMethod,PayBlock,PayBlckRef,CntrlBnk,MaxDscn,Project,FromDate,ToDate,UpdInvnt,Rounding,CorrExt,CorrInv,DeferrTax,LetterNum,AgentCode,Installmnt,VATFirst,VatDate,OwnerCode,FolioPref,FolioNum,DocSubType,BPChCode,BPChCntc,Address2,PayToCode,ManualNum,UseShpdGd,IsPaytoBnk,BnkCntry,BankCode,BnkAccount,BnkBranch,BPLId,DpmPrcnt,isIns,LangCode,TrackNo,PickRmrk,ClsDate,SeqCode,Serial,SeriesStr,SubStr,Model,UseCorrVat,DpmAmnt,DpmPrcnt,Posted,DpmAmntSC,DpmAmntFC,VatPercent,SrvGpPrcnt,Header,Footer,RoundDif,CtlAccount,InsurOp347,IgnRelDoc,Checker,Payee,ExtraMonth,ExtraDays,CdcOffset,PayDuMonth,NTSApprov,NTSWebSite,NTSeTaxNo,NTSApprNo,EDocGenTyp,ESeries,EDocExpFrm,EDocStatus,EDocErrCod,EDocErrMsg,DpmStatus,PQTGrpSer,PQTGrpNum,PQTGrpHW,ReopOriDoc,ReopManCls,OnlineQuo,POSEqNum,POSManufSN,POSCashN,DpmAsDscnt,ClosingOpt,SpecDate,OpenForLaC,GTSRlvnt,AnnInvDecR,Supplier,Releaser,Receiver,AgrNo,IsAlt,AssetDate,DocDlvry,AuthCode,StDlvDate,StDlvTime,EndDlvDate,EndDlvTime,VclPlate,AtDocType,ElCoStatus,ElCoMsg,IsReuseNum,IsReuseNFN,PrintSEPA\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry)){
						$ln .= $cnt
								. "," . ",,,,,"
								. dateFormat($row['billed_date'],"m/d/Y") . ","
								. dateFormat($row['billed_date'],"m/d/Y") . ","
								. $row['customer_id'] . ",,,"
								. $row['billing_refno'] . ","
								. $row['billing_amount'] . ",,,,,,,"
								. str_replace(",", "", $row['remarks']) . ",,,"
								. $row['technician'] . ",,,,,,,,"
								. dateFormat($row['billed_date'],"m/d/Y") . ",,,,,,,,,,,,,,,,,,,,,SUC,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,"
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "oinv_report" . $dt . ".csv";
				break;
			case "opdnreport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT tbl_rr_mst.*,tbl_suppliers.supplier_code FROM tbl_rr_mst
								JOIN tbl_po_mst ON tbl_po_mst.po_reference_no = tbl_rr_mst.po_reference_no
								JOIN tbl_suppliers ON tbl_suppliers.supplier_code = tbl_po_mst.supplier_code
							WHERE tbl_rr_mst.rr_date BETWEEN '$dtfrom' AND '$dtto'
			 				ORDER BY tbl_rr_mst.rr_date";
					$qry = mysql_query($sql);

					$ln .= "OPDN REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "DocNum,DocEntry,DocType,HandWritten,Printed,DocDate,DocDueDate,CardCode,CardName,Address,NumAtCard,DocTotal,AttachmentEntry,DocCurrency,DocRate,Reference1,Reference2,Comments,JournalMemo,PaymentGroupCode,DocTime,SalesPersonCode,TransportationCode,Confirmed,ImportFileNum,SummeryType,ContactPersonCode,ShowSCN,Series,TaxDate,PartialSupply,DocObjectCode,ShipToCode,Indicator,FederalTaxID,DiscountPercent,PaymentReference,DocTotalFc,Form1099,Box1099,RevisionPo,RequriedDate,CancelDate,BlockDunning,Pick,PaymentMethod,PaymentBlock,PaymentBlockEntry,CentralBankIndicator,MaximumCashDiscount,Project,ExemptionValidityDateFrom,ExemptionValidityDateTo,WareHouseUpdateType,Rounding,ExternalCorrectedDocNum,InternalCorrectedDocNum,DeferredTax,TaxExemptionLetterNum,AgentCode,NumberOfInstallments,ApplyTaxOnFirstInstallment,VatDate,DocumentsOwner,FolioPrefixString,FolioNumber,DocumentSubType,BPChannelCode,BPChannelContact,Address2,PayToCode,ManualNumber,UseShpdGoodsAct,IsPayToBank,PayToBankCountry,PayToBankCode,PayToBankAccountNo,PayToBankBranch,BPL_IDAssignedToInvoice,DownPayment,ReserveInvoice,LanguageCode,TrackingNumber,PickRemark,ClosingDate,SequenceCode,SequenceSerial,SeriesString,SubSeriesString,SequenceModel,UseCorrectionVATGroup,DownPaymentAmount,DownPaymentPercentage,DownPaymentType,DownPaymentAmountSC,DownPaymentAmountFC,VatPercent,ServiceGrossProfitPercent,OpeningRemarks,ClosingRemarks,RoundingDiffAmount,ControlAccount,InsuranceOperation347,ArchiveNonremovableSalesQuotation,GTSChecker,GTSPayee,ExtraMonth,ExtraDays,CashDiscountDateOffset,StartFrom,NTSApproved,ETaxWebSite,ETaxNumber,NTSApprovedNumber,EDocGenerationType,EDocSeries,EDocExportFormat,EDocStatus,EDocErrorCode,EDocErrorMessage,DownPaymentStatus,GroupSeries,GroupNumber,GroupHandWritten,ReopenOriginalDocument,ReopenManuallyClosedOrCanceledDocument,CreateOnlineQuotation,POSEquipmentNumber,POSManufacturerSerialNumber,POSCashierNumber,ApplyCurrentVATRatesForDownPaymentsToDraw,ClosingOption,SpecifiedClosingDate,OpenForLandedCosts,RelevantToGTS,AnnualInvoiceDeclarationReference,Supplier,Releaser,Receiver,BlanketAgreementNumber,IsAlteration,AssetValueDate,DocumentDelivery,AuthorizationCode,StartDeliveryDate,StartDeliveryTime,EndDeliveryDate,EndDeliveryTime,VehiclePlate,ATDocumentType,ElecCommStatus,ReuseDocumentNum,ReuseNotaFiscalNum,PrintSEPADirect,FiscalDocNum\r\n";
					$ln .= "DocNum,DocEntry,DocType,Handwrtten,Printed,DocDate,DocDueDate,CardCode,CardName,Address,NumAtCard,DocTotal,AtcEntry,DocCur,DocRate,Ref1,Ref2,Comments,JrnlMemo,GroupNum,DocTime,SlpCode,TrnspCode,Confirmed,ImportEnt,SummryType,CntctCode,ShowSCN,Series,TaxDate,PartSupply,ObjType,ShipToCode,Indicator,LicTradNum,DiscPrcnt,PaymentRef,DocTotalFC,Form1099,Box1099,RevisionPo,ReqDate,CancelDate,BlockDunn,Pick,PeyMethod,PayBlock,PayBlckRef,CntrlBnk,MaxDscn,Project,FromDate,ToDate,UpdInvnt,Rounding,CorrExt,CorrInv,DeferrTax,LetterNum,AgentCode,Installmnt,VATFirst,VatDate,OwnerCode,FolioPref,FolioNum,DocSubType,BPChCode,BPChCntc,Address2,PayToCode,ManualNum,UseShpdGd,IsPaytoBnk,BnkCntry,BankCode,BnkAccount,BnkBranch,BPLId,DpmPrcnt,isIns,LangCode,TrackNo,PickRmrk,ClsDate,SeqCode,Serial,SeriesStr,SubStr,Model,UseCorrVat,DpmAmnt,DpmPrcnt,Posted,DpmAmntSC,DpmAmntFC,VatPercent,SrvGpPrcnt,Header,Footer,RoundDif,CtlAccount,InsurOp347,IgnRelDoc,Checker,Payee,ExtraMonth,ExtraDays,CdcOffset,PayDuMonth,NTSApprov,NTSWebSite,NTSeTaxNo,NTSApprNo,EDocGenTyp,ESeries,EDocExpFrm,EDocStatus,EDocErrCod,EDocErrMsg,DpmStatus,PQTGrpSer,PQTGrpNum,PQTGrpHW,ReopOriDoc,ReopManCls,OnlineQuo,POSEqNum,POSManufSN,POSCashN,DpmAsDscnt,ClosingOpt,SpecDate,OpenForLaC,GTSRlvnt,AnnInvDecR,Supplier,Releaser,Receiver,AgrNo,IsAlt,AssetDate,DocDlvry,AuthCode,StDlvDate,StDlvTime,EndDlvDate,EndDlvTime,VclPlate,AtDocType,ElCoStatus,ElCoMsg,IsReuseNum,IsReuseNFN,PrintSEPA\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry)){
						$ln .= $cnt
								. "," . ",,,,,"
								. dateFormat($row['rr_date'],"m/d/Y") . ","
								. dateFormat($row['rr_date'],"m/d/Y") . ","
								. $row['supplier_code'] . ",,,,"
								. $row['total_amount'] . ",,,,,,,,,,,,,,,,,,"
								. dateFormat($row['rr_date'],"m/d/Y") . ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,"
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "opdn_report" . $dt . ".csv";
				break;
			case "pdnreport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT tbl_rr_dtl.*,tbl_items.SAP_item_code,tbl_items.item_description FROM tbl_rr_dtl
								JOIN tbl_rr_mst ON tbl_rr_mst.rr_reference_no = tbl_rr_dtl.rr_reference_no
								JOIN tbl_items ON tbl_items.item_code = tbl_rr_dtl.item_code
				 			WHERE 1 AND tbl_rr_dtl.rr_date between '$dtfrom' AND '$dtto' $where
				 			ORDER BY tbl_rr_dtl.rr_date";
					$qry = mysql_query($sql);

					$ln .= "PDN REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "ParentKey,LineNum,ItemCode,ItemDescription,Quantity,ShipDate,Price,PriceAfterVAT,Currency,Rate,DiscountPercent,VendorNum,SerialNum,WarehouseCode,SalesPersonCode,CommisionPercent,TreeType,AccountCode,UseBaseUnits,SupplierCatNum,CostingCode,ProjectCode,BarCode,VatGroup,Height1,Hight1Unit,Height2,Height2Unit,Lengh1,Lengh1Unit,Lengh2,Lengh2Unit,Weight1,Weight1Unit,Weight2,Weight2Unit,Factor1,Factor2,Factor3,Factor4,BaseType,BaseEntry,BaseLine,Volume,VolumeUnit,Width1,Width1Unit,Width2,Width2Unit,Address,TaxCode,TaxType,TaxLiable,BackOrder,FreeText,ShippingMethod,CorrectionInvoiceItem,CorrInvAmountToStock,CorrInvAmountToDiffAcct,WTLiable,DeferredTax,MeasureUnit,UnitsOfMeasurment,LineTotal,TaxPercentagePerRow,TaxTotal,ConsumerSalesForecast,ExciseAmount,CountryOrg,SWW,TransactionType,DistributeExpense,ShipToCode,RowTotalFC,CFOPCode,CSTCode,Usage,TaxOnly,UnitPrice,LineStatus,LineType,COGSCostingCode,COGSAccountCode,ChangeAssemlyBoMWarehouse,GrossBuyPrice,GrossBase,GrossProfitTotalBasePrice,CostingCode2,CostingCode3,CostingCode4,CostingCode5,ItemDetails,LocationCode,ActualDeliveryDate,ExLineNo,RequiredDate,RequiredQuantity,COGSCostingCode2,COGSCostingCode3,COGSCostingCode4,COGSCostingCode5,CSTforIPI,CSTforPIS,CSTforCOFINS,CreditOriginCode,WithoutInventoryMovement,AgreementNo,AgreementRowNumber,ShipToDescription,ActualBaseEntry,ActualBaseLine,DocEntry,Surpluses,DefectAndBreakup,Shortages,ConsiderQuantity,PartialRetirement,RetirementQuantity,RetirementAPC,UoMEntry,InventoryQuantity,Incoterms,TransportMode,U_Supplier,U_TIN,U_PmtType,U_Maker,U_Variant\r\n";
					$ln .= "DocNum,LineNum,ItemCode,Dscription,Quantity,ShipDate,Price,PriceAfVAT,Currency,Rate,DiscPrcnt,VendorNum,SerialNum,WhsCode,SlpCode,Commission,TreeType,AcctCode,UseBaseUn,SubCatNum,OcrCode,Project,CodeBars,VatGroup,Height1,Hght1Unit,Height2,Hght2Unit,Length1,Len1Unit,length2,Len2Unit,Weight1,Wght1Unit,Weight2,Wght2Unit,Factor1,Factor2,Factor3,Factor4,BaseType,BaseEntry,BaseLine,Volume,VolUnit,Width1,Wdth1Unit,Width2,Wdth2Unit,Address,TaxCode,TaxType,TaxStatus,BackOrdr,FreeTxt,TrnsCode,CEECFlag,ToStock,ToDiff,WtLiable,DeferrTax,unitMsr,NumPerMsr,LineTotal,VatPrcnt,VatSum,ConsumeFCT,ExciseAmt,CountryOrg,SWW,TranType,DistribExp,ShipToCode,TotalFrgn,CFOPCode,CSTCode,Usage,TaxOnly,PriceBefDi,LineStatus,LineType,CogsOcrCod,CogsAcct,ChgAsmBoMW,GrossBuyPr,GrossBase,GPTtlBasPr,OcrCode2,OcrCode3,OcrCode4,OcrCode5,Text,LocCode,ActDelDate,ExLineNo,PQTReqDate,PQTReqQty,CogsOcrCo2,CogsOcrCo3,CogsOcrCo4,CogsOcrCo5,CSTfIPI,CSTfPIS,CSTfCOFINS,CredOrigin,NoInvtryMv,AgrNo,AgrLnNum,ShipToDesc,ActBaseEnt,ActBaseLn,DocEntry,Surpluses,DefBreak,Shortages,NeedQty,PartRetire,RetireQty,RetireAPC,UomEntry,InvQty,Incoterms,TransMod,U_Supplier,U_TIN,U_PmtType,U_Maker,U_Variant\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry)){
						$ln .= $cnt
								. "," . ",,"
								. $row['SAP_item_code'] . ","
								. $row['item_description'] . ","
								. $row['quantity'] . ",,"
								. $row['price'] . ",,,,,,,P2,,,,,,,,SUC,,V4,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,P01,SUC,,Z10,,,,,,,,,,,,,,,,,,,,,,,,,,,"
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "pdn_report" . $dt . ".csv";
				break;
			case "oignreport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT tbl_service_master.*
								,tbl_billing.billing_date
								,tbl_billing.billing_refno
								,tbl_billing.total_amount as billing_amount 
							FROM tbl_service_master
								JOIN tbl_billing ON tbl_billing.wo_refno = tbl_service_master.wo_refno
							WHERE tbl_billing.billing_date BETWEEN '$dtfrom' AND '$dtto'
			 				ORDER BY tbl_billing.billing_date";
					$qry = mysql_query($sql);

					$ln .= "OIGN REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "DocNum,DocEntry,DocType,HandWritten,Printed,DocDate,DocDueDate,CardCode,CardName,Address,NumAtCard,DocTotal,AttachmentEntry,DocCurrency,DocRate,Reference1,Reference2,Comments,JournalMemo,PaymentGroupCode,DocTime,SalesPersonCode,TransportationCode,Confirmed,ImportFileNum,SummeryType,ContactPersonCode,ShowSCN,Series,TaxDate,PartialSupply,DocObjectCode,ShipToCode,Indicator,FederalTaxID,DiscountPercent,PaymentReference,DocTotalFc,Form1099,Box1099,RevisionPo,RequriedDate,CancelDate,BlockDunning,Pick,PaymentMethod,PaymentBlock,PaymentBlockEntry,CentralBankIndicator,MaximumCashDiscount,Project,ExemptionValidityDateFrom,ExemptionValidityDateTo,WareHouseUpdateType,Rounding,ExternalCorrectedDocNum,InternalCorrectedDocNum,DeferredTax,TaxExemptionLetterNum,AgentCode,NumberOfInstallments,ApplyTaxOnFirstInstallment,VatDate,DocumentsOwner,FolioPrefixString,FolioNumber,DocumentSubType,BPChannelCode,BPChannelContact,Address2,PayToCode,ManualNumber,UseShpdGoodsAct,IsPayToBank,PayToBankCountry,PayToBankCode,PayToBankAccountNo,PayToBankBranch,BPL_IDAssignedToInvoice,DownPayment,ReserveInvoice,LanguageCode,TrackingNumber,PickRemark,ClosingDate,SequenceCode,SequenceSerial,SeriesString,SubSeriesString,SequenceModel,UseCorrectionVATGroup,DownPaymentAmount,DownPaymentPercentage,DownPaymentType,DownPaymentAmountSC,DownPaymentAmountFC,VatPercent,ServiceGrossProfitPercent,OpeningRemarks,ClosingRemarks,RoundingDiffAmount,ControlAccount,InsuranceOperation347,ArchiveNonremovableSalesQuotation,GTSChecker,GTSPayee,ExtraMonth,ExtraDays,CashDiscountDateOffset,StartFrom,NTSApproved,ETaxWebSite,ETaxNumber,NTSApprovedNumber,EDocGenerationType,EDocSeries,EDocExportFormat,EDocStatus,EDocErrorCode,EDocErrorMessage,DownPaymentStatus,GroupSeries,GroupNumber,GroupHandWritten,ReopenOriginalDocument,ReopenManuallyClosedOrCanceledDocument,CreateOnlineQuotation,POSEquipmentNumber,POSManufacturerSerialNumber,POSCashierNumber,ApplyCurrentVATRatesForDownPaymentsToDraw,ClosingOption,SpecifiedClosingDate,OpenForLandedCosts,RelevantToGTS,AnnualInvoiceDeclarationReference,Supplier,Releaser,Receiver,BlanketAgreementNumber,IsAlteration,AssetValueDate,DocumentDelivery,AuthorizationCode,StartDeliveryDate,StartDeliveryTime,EndDeliveryDate,EndDeliveryTime,VehiclePlate,ATDocumentType,ElecCommStatus,ReuseDocumentNum,ReuseNotaFiscalNum,PrintSEPADirect,FiscalDocNum\r\n";
					$ln .= "DocNum,DocEntry,DocType,Handwrtten,Printed,DocDate,DocDueDate,CardCode,CardName,Address,NumAtCard,DocTotal,AtcEntry,DocCur,DocRate,Ref1,Ref2,Comments,JrnlMemo,GroupNum,DocTime,SlpCode,TrnspCode,Confirmed,ImportEnt,SummryType,CntctCode,ShowSCN,Series,TaxDate,PartSupply,ObjType,ShipToCode,Indicator,LicTradNum,DiscPrcnt,PaymentRef,DocTotalFC,Form1099,Box1099,RevisionPo,ReqDate,CancelDate,BlockDunn,Pick,PeyMethod,PayBlock,PayBlckRef,CntrlBnk,MaxDscn,Project,FromDate,ToDate,UpdInvnt,Rounding,CorrExt,CorrInv,DeferrTax,LetterNum,AgentCode,Installmnt,VATFirst,VatDate,OwnerCode,FolioPref,FolioNum,DocSubType,BPChCode,BPChCntc,Address2,PayToCode,ManualNum,UseShpdGd,IsPaytoBnk,BnkCntry,BankCode,BnkAccount,BnkBranch,BPLId,DpmPrcnt,isIns,LangCode,TrackNo,PickRmrk,ClsDate,SeqCode,Serial,SeriesStr,SubStr,Model,UseCorrVat,DpmAmnt,DpmPrcnt,Posted,DpmAmntSC,DpmAmntFC,VatPercent,SrvGpPrcnt,Header,Footer,RoundDif,CtlAccount,InsurOp347,IgnRelDoc,Checker,Payee,ExtraMonth,ExtraDays,CdcOffset,PayDuMonth,NTSApprov,NTSWebSite,NTSeTaxNo,NTSApprNo,EDocGenTyp,ESeries,EDocExpFrm,EDocStatus,EDocErrCod,EDocErrMsg,DpmStatus,PQTGrpSer,PQTGrpNum,PQTGrpHW,ReopOriDoc,ReopManCls,OnlineQuo,POSEqNum,POSManufSN,POSCashN,DpmAsDscnt,ClosingOpt,SpecDate,OpenForLaC,GTSRlvnt,AnnInvDecR,Supplier,Releaser,Receiver,AgrNo,IsAlt,AssetDate,DocDlvry,AuthCode,StDlvDate,StDlvTime,EndDlvDate,EndDlvTime,VclPlate,AtDocType,ElCoStatus,ElCoMsg,IsReuseNum,IsReuseNFN,PrintSEPA\r\n";

					$cnt = 1;
					// while($row = mysql_fetch_array($qry)){
					// 	$ln .= $cnt
					// 			. "," . ",,dDocument_Items,,,20160321,20160321,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,"
					// 			. "\r\n";
					// 	$cnt++;
					// }

					$data = trim($ln);
					$filename = "oign_report" . $dt . ".csv";
				break;
			case "ignreport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT tbl_rr_dtl.*,tbl_items.SAP_item_code,tbl_items.item_description FROM tbl_rr_dtl
								JOIN tbl_rr_mst ON tbl_rr_mst.rr_reference_no = tbl_rr_dtl.rr_reference_no
								JOIN tbl_items ON tbl_items.item_code = tbl_rr_dtl.item_code
				 			WHERE 1 AND tbl_rr_dtl.rr_date between '$dtfrom' AND '$dtto' $where
				 			ORDER BY tbl_rr_dtl.rr_date";
					$qry = mysql_query($sql);

					$ln .= "IGN REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "ParentKey,LineNum,ItemCode,ItemDescription,Quantity,ShipDate,Price,PriceAfterVAT,Currency,Rate,DiscountPercent,VendorNum,SerialNum,WarehouseCode,SalesPersonCode,CommisionPercent,TreeType,AccountCode,UseBaseUnits,SupplierCatNum,CostingCode,ProjectCode,BarCode,VatGroup,Height1,Hight1Unit,Height2,Height2Unit,Lengh1,Lengh1Unit,Lengh2,Lengh2Unit,Weight1,Weight1Unit,Weight2,Weight2Unit,Factor1,Factor2,Factor3,Factor4,BaseType,BaseEntry,BaseLine,Volume,VolumeUnit,Width1,Width1Unit,Width2,Width2Unit,Address,TaxCode,TaxType,TaxLiable,BackOrder,FreeText,ShippingMethod,CorrectionInvoiceItem,CorrInvAmountToStock,CorrInvAmountToDiffAcct,WTLiable,DeferredTax,MeasureUnit,UnitsOfMeasurment,LineTotal,TaxPercentagePerRow,TaxTotal,ConsumerSalesForecast,ExciseAmount,CountryOrg,SWW,TransactionType,DistributeExpense,ShipToCode,RowTotalFC,CFOPCode,CSTCode,Usage,TaxOnly,UnitPrice,LineStatus,LineType,COGSCostingCode,COGSAccountCode,ChangeAssemlyBoMWarehouse,GrossBuyPrice,GrossBase,GrossProfitTotalBasePrice,CostingCode2,CostingCode3,CostingCode4,CostingCode5,ItemDetails,LocationCode,ActualDeliveryDate,ExLineNo,RequiredDate,RequiredQuantity,COGSCostingCode2,COGSCostingCode3,COGSCostingCode4,COGSCostingCode5,CSTforIPI,CSTforPIS,CSTforCOFINS,CreditOriginCode,WithoutInventoryMovement,AgreementNo,AgreementRowNumber,ShipToDescription,ActualBaseEntry,ActualBaseLine,DocEntry,Surpluses,DefectAndBreakup,Shortages,ConsiderQuantity,PartialRetirement,RetirementQuantity,RetirementAPC,UoMEntry,InventoryQuantity,Incoterms,TransportMode,U_Supplier,U_TIN,U_PmtType,U_Maker,U_Variant\r\n";
					$ln .= "DocNum,LineNum,ItemCode,Dscription,Quantity,ShipDate,Price,PriceAfVAT,Currency,Rate,DiscPrcnt,VendorNum,SerialNum,WhsCode,SlpCode,Commission,TreeType,AcctCode,UseBaseUn,SubCatNum,OcrCode,Project,CodeBars,VatGroup,Height1,Hght1Unit,Height2,Hght2Unit,Length1,Len1Unit,length2,Len2Unit,Weight1,Wght1Unit,Weight2,Wght2Unit,Factor1,Factor2,Factor3,Factor4,BaseType,BaseEntry,BaseLine,Volume,VolUnit,Width1,Wdth1Unit,Width2,Wdth2Unit,Address,TaxCode,TaxType,TaxStatus,BackOrdr,FreeTxt,TrnsCode,CEECFlag,ToStock,ToDiff,WtLiable,DeferrTax,unitMsr,NumPerMsr,LineTotal,VatPrcnt,VatSum,ConsumeFCT,ExciseAmt,CountryOrg,SWW,TranType,DistribExp,ShipToCode,TotalFrgn,CFOPCode,CSTCode,Usage,TaxOnly,PriceBefDi,LineStatus,LineType,CogsOcrCod,CogsAcct,ChgAsmBoMW,GrossBuyPr,GrossBase,GPTtlBasPr,OcrCode2,OcrCode3,OcrCode4,OcrCode5,Text,LocCode,ActDelDate,ExLineNo,PQTReqDate,PQTReqQty,CogsOcrCo2,CogsOcrCo3,CogsOcrCo4,CogsOcrCo5,CSTfIPI,CSTfPIS,CSTfCOFINS,CredOrigin,NoInvtryMv,AgrNo,AgrLnNum,ShipToDesc,ActBaseEnt,ActBaseLn,DocEntry,Surpluses,DefBreak,Shortages,NeedQty,PartRetire,RetireQty,RetireAPC,UomEntry,InvQty,Incoterms,TransMod,U_Supplier,U_TIN,U_PmtType,U_Maker,U_Variant\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry)){
						$ln .= $cnt
								. "," . ",,"
								. $row['SAP_item_code'] . ",Oil Filter BOSCH-AF1-035,2,,242.85,,,,,,,P2,,,,_SYS00000000095,,,,SUC,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,485.7,,,,,,,,,,,,,,,,,,,,,,,,P01,SUC,,Z10,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,"
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "ign_report" . $dt . ".csv";
				break;
			case "orctreport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT * FROM v_customer
				 			WHERE 1 AND v_customer.cust_created between '$dtfrom' AND '$dtto' $where
				 			ORDER BY v_customer.cust_created";
					$qry = mysql_query($sql);

					$ln .= "ORCT REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "DocNum,DocType,HandWritten,Printed,DocDate,CardCode,CardName,Address,CashAccount,DocCurrency,CashSum,CheckAccount,TransferAccount,TransferSum,TransferDate,TransferReference,LocalCurrency,DocRate,Reference1,Reference2,CounterReference,Remarks,JournalRemarks,SplitTransaction,ContactPersonCode,ApplyVAT,TaxDate,Series,BankCode,BankAccount,DiscountPercent,ProjectCode,CurrencyIsLocal,DeductionPercent,DeductionSum,BoeAccount,BillOfExchangeAmount,BillofExchangeStatus,BillOfExchangeAgent,WTCode,WTAmount,Proforma,PayToBankCode,PayToBankBranch,PayToBankAccountNo,PayToCode,PayToBankCountry,IsPayToBank,PaymentPriority,TaxGroup,BankChargeAmount,BankChargeAmountInSC,WtBaseSum,VatDate,TransactionCode,PaymentType,TransferRealAmount,DocObjectCode,DocTypte,DueDate,LocationCode,ControlAccount,BPLID\r\n";
					$ln .= "DocNum,DocType,Handwrtten,Printed,DocDate,CardCode,CardName,Address,CashAcct,DocCurr,CashSum,CheckAcct,TrsfrAcct,TrsfrSum,TrsfrDate,TrsfrRef,DiffCurr,DocRate,Ref1,Ref2,CounterRef,Comments,JrnlMemo,SpiltTrans,CntctCode,ApplyVAT,TaxDate,Series,BankCode,BankAcct,Dcount,PrjCode,DiffCurr,DdctPrcnt,DdctSum,BoeAcc,BoeSum,BoeStatus,BoeAgent,WtCode,WtSum,Proforma,PBnkCode,PBnkBranch,PBnkAccnt,PayToCode,PBnkCnt,IsPaytoBnk,PaPriority,VatGroup,BcgSum,BcgSumSy,WtBaseSum,VatDate,TransCode,PaymType,TfrRealAmt,ObjType,DocType,DocDueDate,LocCode,BpAct,WddStatus\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry)){
						$ln .= $cnt
								. "," . ",,,,"
								. dateFormat($row['cust_created'],"m/d/Y") . ","
								. $row['customer_id'] . ","
								. $row['custname'] . ",,,,,,,,"
								. dateFormat($row['cust_created'],"m/d/Y") . ",,,,,,,,,,,,"
								. dateFormat($row['cust_created'],"m/d/Y") . ",,,,,SUC,,,,,,,,,,,,,,,,,,,,,,"
								. dateFormat($row['cust_created'],"m/d/Y") . ",,,,,,"
								. dateFormat($row['cust_created'],"m/d/Y") . ",,,"
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "orct_report" . $dt . ".csv";
				break;
			case "checksreport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT tbl_billing.* FROM tbl_billing
							JOIN tbl_service_master ON tbl_service_master.wo_refno = tbl_billing.wo_refno
								AND tbl_service_master.payment_id = 'PAY00000003'
								AND (tbl_service_master.trans_status = '7' 
									OR tbl_service_master.trans_status = '8'
									OR tbl_service_master.trans_status = '9'
									OR tbl_service_master.trans_status = '10')
							WHERE tbl_billing.billing_date BETWEEN '$dtfrom' AND '$dtto'
			 				ORDER BY tbl_billing.billing_date";
					$qry = mysql_query($sql);

					$ln .= "CHECKS REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "ParentKey,LineNum,DueDate,CheckNumber,BankCode,Branch,AccounttNum,Details,Trnsfrable,CheckSum,Currency,CountryCode,CheckAccount,ManualCheck\r\n";
					$ln .= "DocNum,LineNum,DueDate,CheckNum,BankCode,Branch,AcctNum,Details,Trnsfrable,CheckSum,Currency,CountryCod,CheckAct,ManualChk\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry)){
						$ln .= $cnt
								. "," . ",,"
								. dateFormat($row['billing_date'],2) . ",,,,,,,"
								. $row['total_amount'] . ",,,,"
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "checks_report" . $dt . ".csv";
				break;
			case "creditcardreport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT tbl_billing.* FROM tbl_billing
							JOIN tbl_service_master ON tbl_service_master.wo_refno = tbl_billing.wo_refno
								AND tbl_service_master.payment_id = 'PAY00000003'
								AND (tbl_service_master.trans_status = '7' 
									OR tbl_service_master.trans_status = '8'
									OR tbl_service_master.trans_status = '9'
									OR tbl_service_master.trans_status = '10')
							WHERE tbl_billing.billing_date BETWEEN '$dtfrom' AND '$dtto'
			 				ORDER BY tbl_billing.billing_date";
					$qry = mysql_query($sql);

					$ln .= "CREDIT CARD REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "ParentKey,LineNum,CreditCard,CreditAcct,CreditCardNumber,CardValidUntil,VoucherNum,OwnerIdNum,OwnerPhone,PaymentMethodCode,NumOfPayments,FirstPaymentDue,FirstPaymentSum,AdditionalPaymentSum,CreditSum,CreditCur,CreditRate,ConfirmationNum,NumOfCreditPayments,CreditType,SplitPayments\r\n";
					$ln .= "DocNum,LineNum,CreditCard,CreditAcct,CrCardNum,CardValid,VoucherNum,OwnerIdNum,OwnerPhone,CrTypeCode,NumOfPmnts,FirstDue,FirstSum,AddPmntSum,CreditSum,CreditCur,CreditRate,ConfNum,CredPmnts,CreditType,SpiltCred\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry)){
						$ln .= $cnt
								. "," . ",,,,,,,,,,1,,,,"
								. $row['total_amount'] . ",,,,,,"
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "credit_card_report" . $dt . ".csv";
				break;
			case "pmtinvoicereport":
					$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
					$to = dateFormat($_POST['txtdateto'],"Y-m-d");
					$dtfrom = $from . " 00:00:00";
					$dtto = $to . " 23:59:59";
					$dt = date("Ymdhis");

					if(empty($dtfrom) && empty($dtto)){
						$dtfrom = date("Y-m-d 00:00");
						$dtto = date("Y-m-d 23:59");
					}

					$sql = "SELECT tbl_service_master.*
								,tbl_billing.billing_date
								,tbl_billing.billing_refno
								,tbl_billing.total_amount as billing_amount 
							FROM tbl_service_master
								JOIN tbl_billing ON tbl_billing.wo_refno = tbl_service_master.wo_refno
							WHERE tbl_billing.billing_date BETWEEN '$dtfrom' AND '$dtto'
			 				ORDER BY tbl_billing.billing_date";
					$qry = mysql_query($sql);

					$ln .= "PMT INVOICE REPORT\r\n\r\n";
					
					$ln .= "From: ," . $dtfrom . "\r\n";
					$ln .= "To: ," . $dtto . "\r\n";
					$ln .= "ParentKey,LineNum,DocEntry,SumApplied,AppliedFC,AppliedSys,DocRate,DocLine,InvoiceType,DiscountPercent,PaidSum,InstallmentId,DistributionRule,DistributionRule2,DistributionRule3,DistributionRule4,DistributionRule5,TotalDiscount,TotalDiscountFC\r\n";
					$ln .= "DocNum,LineNum,DocEntry,SumApplied,AppliedFC,AppliedSys,DocRate,DocLine,InvType,Dcount,PaidSum,InstId,OcrCode,OcrCode2,OcrCode3,OcrCode4,OcrCode5,DcntSum,DcntSumFC\r\n";

					$cnt = 1;
					while($row = mysql_fetch_array($qry)){
						$ln .= $cnt
								. "," . ",,,,,,,,,,"
								. $row['total_amount'] . ",,,,,,,,"
								. "\r\n";
						$cnt++;
					}

					$data = trim($ln);
					$filename = "pmt_invoice_report" . $dt . ".csv";
				break;
			default: echo 'INVALID URL!'; break;
		}

		if(!empty($data) && !empty($filename)){
			exportRowData($data,"excel",$filename);
		}
	}
	exit();
?>