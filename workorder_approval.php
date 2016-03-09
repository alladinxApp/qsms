<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	chkMenuAccess('workorder_approval',$_SESSION['username'],'workorder_list.php');
	
	$estimaterefno = $_GET['estimaterefno'];
	$payment = $_POST['paymentmode'];
	
	$qryestimate = "SELECT * FROM v_service_master WHERE estimate_refno = '$estimaterefno'";
	$resestimate = $dbo->query($qryestimate);
	
	foreach($resestimate as $rowestimate){
		$custid = $rowestimate['customer_id'];
		$custname = $rowestimate['customername'];
		$vehicleid = $rowestimate['vehicle_id'];
		$remarks = $rowestimate['remarks'];
		$paymentmode = $rowestimate['payment_mode'];
		$payment = $rowestimate['payment_id'];
		$discount = $rowestimate['discount'];
		$porefno = $rowestimate['po_refno'];
		$worefno = $rowestimate['wo_refno'];
		$transstatus = $rowestimate['trans_status'];
		$empid = $rowestimate['emp_id'];
		$empname = $rowestimate['tech_name'];
		$promisetime = $rowestimate['promise_time'];
		$promisedate = $rowestimate['promise_date'];
	}

	$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$custid'";
	$rescustomer = $dbo->query($qrycustomer);
	
	foreach($rescustomer as $rowcustomer){
		$address = $rowcustomer['address'] . ', ' . $rowcustomer['city'] . ', ' . $rowcustomer['province'];
	}
	
	$qryvehicle = "SELECT * FROM v_vehicleinfo WHERE vehicle_id = '$vehicleid'";
	$resvehicle = $dbo->query($qryvehicle);
	
	foreach($resvehicle as $rowvehicle){
		$plateno = $rowvehicle['plate_no'];
		$makedesc = $rowvehicle['make_desc'];
		$yeardesc = $rowvehicle['year_desc'];
		$modeldesc = $rowvehicle['model_desc'];
		$colordesc = $rowvehicle['color_desc'];
		$variant = $rowvehicle['variant'];
		$engineno = $rowvehicle['engine_no'];
		$chassisno = $rowvehicle['chassis_no'];
		$serialno = $rowvehicle['serial_no'];
	}
	
	$qrycost_accessory = "SELECT * FROM v_service_detail_accessory WHERE estimate_refno = '$estimaterefno'";
	$rescost_accessory = $dbo->query($qrycost_accessory);
	
	$qrycost_job = "SELECT * FROM v_service_detail_job WHERE estimate_refno = '$estimaterefno'";
	$rescost_job = $dbo->query($qrycost_job);
	
	$qrycost_make = "SELECT * FROM v_service_detail_make WHERE estimate_refno = '$estimaterefno'";
	$rescost_make = $dbo->query($qrycost_make);
	
	$qrycost_material = "SELECT * FROM v_service_detail_material WHERE estimate_refno = '$estimaterefno'";
	$rescost_material = $dbo->query($qrycost_material);
	
	$qrycost_parts = "SELECT * FROM v_service_detail_parts WHERE estimate_refno = '$estimaterefno'";
	$rescost_parts = $dbo->query($qrycost_parts);
	
	$subtotal = 0;
	
	if(isset($_POST['savewo']) && !empty($_POST['savewo']) && $_POST['savewo'] == 1){
		$empid = $_POST['empid'];
		$promisetime = $_POST['promisetime'];
		$promisedate = dateFormat($_POST['promisedate'],"Y-m-d");
		
		$qry = null;
		$qry .= "UPDATE tbl_service_master SET payment_id = '$payment', technician = '$empid',promise_date = '$promisedate',promise_time = '$promisetime' WHERE estimate_refno = '$estimaterefno'; ";
		$qry .= "INSERT INTO tbl_jobclock_master(wo_refno) VALUE('$worefno'); ";
		$res = $dbo->query($qry);
		
		if(!$res){
			echo '<script>alert("There has been an error in updating your data! Please re-process hour transaction."); window.location="workorder_approval.php?estimaterefno='.$estimaterefno.'";</script>';
			exit();
		}else{
			echo '<script>alert("Work order successfully selected a technician."); window.location="workorder_approval.php?estimaterefno='.$estimaterefno.'";</script>';
			exit();
		}
	}
	
	$qrytech = "SELECT * FROM v_employee WHERE emp_status = '1' ORDER BY employee";
	$restech = $dbo->query($qrytech);
	
	$qrytech1 = "SELECT * FROM v_employee WHERE emp_status = '1' ORDER BY employee";
	$restech1 = $dbo->query($qrytech1);

	$qrypayment = "SELECT * FROM v_payment";
	$respayment = $dbo->query($qrypayment);
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<? require_once('inc/datepicker.php'); ?>
<script type="text/javascript">
	function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp = false;	
		try{
			xmlhttp = new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp = false;
				}
			}
		}
		return xmlhttp;
	}
	function getEmpInfo(empid){
		var strURL = "divGetEmployeeInfo.php?empid="+empid;
		var req = getXMLHTTP();
		if (req){
			req.onreadystatechange = function(){
				if (req.readyState == 4){
						document.getElementById('divEmpInfo').innerHTML = req.responseText;
						getPlateNos(custid);
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
</script>
<style type="text/css">
	div.divTableEstimateCost{ font-size: 11px; }
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
	div.divTableEstimateCost div.divCost{ border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: 12px; }
</style>
<body>
	<? if(!empty($empid)){ ?>
	<table><tr>
		<td><a href="workorder_print.php?estimaterefno=<?=$estimaterefno;?>" target="_blank">
	  <div style="width:100px; height:50px; text-align: center;"><img src="images/print_wo.png" width="59" height="48" style="pointer: cursor; width: 59px;" border="0" /></div></a></td>
		<td><a href="workorder_cancel.php?worefno=<?=$worefno;?>"><img src="images/cancel.jpg" width="55" height="58" style="pointer: cursor; width: 59px;" border="0" /></a></td></tr>
	</table>
	<? } ?>
	
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">REFERENCE NUMBER</p></legend>
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer_id">Estimate: </label>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$estimaterefno;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Work Order: </label>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$worefno;?></td>
		</tr>
		<? if($porefno != '0'){ ?>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Purchase Order: </label>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$porefno;?></td>
		</tr>
		<? } ?>
	</table>
	</fieldset>
	<fieldset form="form_estimate" name="form_estimate">
	<legend><p id="title">CUSTOMER INFORMATION</p></legend>
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_customer_id">Customer Code:</label>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$custid;?></td>
			<td></td>
			<td class ="label" width="100"><label name="lbl_customer">Customer Name:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$custname;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_custadd">Address:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$address;?></td>
		</tr>
	</table>
	</fieldset>
	<fieldset form="form_highschool" name="form_highschool">
	<legend>
	<p id="title">VEHICLE INFORMATION:</p></legend>
	<table>
		<tr>
			<td class ="label" width="100"><label name="lbl_plateno">Plate Number:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$plateno;?></td>
			<td></td>
			<td class ="label" width="100"><label name="lbl_year">Year:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$yeardesc;?></td>
			<td></td>
			<td class ="label" width="100"><label name="lbl_make">Make:</label></td>
			<td align="left" width="200" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$makedesc;?></td>
		</tr>		
		<tr>
			<td class ="label"><label name="lbl_model">Model:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$modeldesc;?></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Color:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$colordesc;?></td>
			<td></td>
			<td class ="label"><label name="lbl_variant">Variant:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$variant;?></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_vehicledescription">Engine No:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$engineno;?></td>
			<td></td>
			<td class ="label"><label name="lbl_color">Chassis No</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$chassisno;?></td>
			<td></td>
			<td class ="label"><label name="lbl_serialno">Serial No:</label></td>
			<td align="left" style="font-weight: normal; font-size: 12px; border:1px solid #333;"><?=$serialno;?></td>
		</tr>
	</table>
	</fieldset>
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">REMARKS</p></legend>	
	<table>
		<tr>
			<td align="left" style="font-weight: normal; font-size: 12px;"><?=$remarks;?></td>
		</tr>
	</table>
	</fieldset>
	<form method="Post" onSubmit="return ValidateMe();">
	<fieldset form="form_remarks" name="form_remarks">
	<legend><p id="title">TECHNICIAN</p></legend>
	<span id="divEmpInfo">
	<table>
		<tr>
			<td class ="label"><label name="lbl_customer_id">Technician Code:</label>
			<td class ="input">
			<? if(!empty($empid)){ ?>
				<span class="input"><input type="text" name="empid" id="empid" value="<?=$empid;?>" readonly style="width:235px"></span>
			<? }else{ ?>
			<select name="empid" id="empid" onChange="getEmpInfo(this.value);">
				<option value="">Select a Technician</option>
				<? foreach($restech as $rowTECHNICIAN){ ?>
				<option value="<?=$rowTECHNICIAN['emp_id'];?>"><?=$rowTECHNICIAN['emp_id'];?></option>
				<? } ?>
			</select>
			<? } ?>
			</td>
			<td class ="label"><label name="lbl_custadd">Technician Name:</label></td>
			<td class ="input">
			<? if(!empty($empid)){ ?>
				<span class="input"><input type="text" name="empname" id="empname" value="<?=$empname;?>" readonly style="width:235px"></span>
			<? }else{ ?>
			<select name="empname" id="empname" onChange="getEmpInfo(this.value);">
				<option value="">Select a Technician</option>
				<? foreach($restech1 as $rowTECHNICIAN1){ ?>
				<option value="<?=$rowTECHNICIAN1['emp_id'];?>"><?=$rowTECHNICIAN1['employee'];?></option>
				<? } ?>
			</select>
			<? } ?>
			</td>
		</tr>
		
	</table>
	</span>
	<table>
		<tr>
			<td class ="label"><label name="lbl_custadd">Promise Date:</label></td>
			<td class ="input"><input type="text" name="promisedate" id="promisedate" readonly class="date-pick" value="<?=$promisedate;?>" /></td>
			<td class ="label"><label name="lbl_custadd">Promise Time:</label></td>
			<td class ="input"><input type="text" name="promisetime" id="promisetime" value="<?=$promisetime;?>" /> format 00:00:00</td>
		</tr>
		<tr>
			<td class="label">Payment Mode:</td>
			<td class="input"><select name="paymentmode" id="paymentmode" style="width: 200px;">
				<option value=""></option>
				<? foreach($respayment as $rowpayment){ ?>
				<option value="<?=$rowpayment['payment_id'];?>" <? if($payment == $rowpayment['payment_id']){ echo 'selected'; } ?>><?=$rowpayment['payment'];?></option>
				<? } ?>
			</select></td>
			<td></td>
		</tr>
	</table>
	</fieldset>
	
	<? if(empty($empid)){ ?>
	<input type="submit" name="btnsubmit" id="btnsubmit" value="" />
	<input type="hidden" name="savewo" id="savewo" value="1" />
	<? } ?>
	
	</form>
	<script type="text/javascript">
		function ValidateMe(){
			var empid = document.getElementById("empid").value;
			var promisetime = document.getElementById("promisetime").value;
			var promisedate = document.getElementById("promisedate").value;
			var paymentmode = document.getElementById("paymentmode").value;
			
			if(empid == ""){
				alert("Please select a technician of this work order!");
				return false;
			}else if(promisedate == ""){
				alert("Please enter promise date!");
				return false;
			}else if(promisetime == ""){
				alert("Please enter promise time!");
				return false;
			}else if(paymentmode == ""){
				alert("Please enter payment mode!");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>