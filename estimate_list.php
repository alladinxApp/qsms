<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(isset($_POST['search']) && !empty($_POST['search']) && $_POST['search'] == 1){
		$datefrom = $_POST['txtdatefrom'];
		$dateto = $_POST['txtdateto'];
		$plateno = $_POST['txtplateno'];
		$custname = $_POST['txtcustomer'];
		$estimateno = $_POST['txtestimaterefno'];
		$status = $_POST['txtstatus'];
		
		if(!empty($status)){
			$where = "WHERE (trans_status = '$status')";
		}else{
			$where = "WHERE (trans_status < '4')";
		}

		if(!empty($datefrom) && !empty($dateto)){
			$datefrom = dateFormat($datefrom,"Y-m-d");
			$dateto = dateFormat($dateto,"Y-m-d");
			$where .= " AND (transaction_date between '$datefrom 00:00:00' AND '$dateto 23:59:59')";
			// $where .= " AND (transaction_date between '$datefrom 00:00' AND '$dateto 23:59')";
		}else if(empty($datefrom) && !empty($dateto)){
			$dateto = dateFormat($dateto,"Y-m-d");
			$where .= " AND transaction_date = '$dateto'";
		}else if(empty($dateto) && !empty($datefrom)){
			$datefrom = dateFormat($datefrom,"Y-m-d");
			$where .= " AND transaction_date = '$datefrom'";	
		}

		if(!empty($plateno)){
			$where .= " AND plate_no LIKE '$plateno%'";
		}

		if(!empty($custname)){
			$where .= " AND custname LIKE '$custname%'";
		}

		if(!empty($estimateno)){
			$where .= " AND estimate_refno LIKE '$estimateno%'";
		}
		
		$qryservices = "SELECT tbl_service_master.*,v_customer.custname AS custname
							,tbl_vehicleinfo.plate_no
							,tbl_vehicleinfo.variant
							,tbl_vehicleinfo.description
							,tbl_vehicleinfo.engine_no
							,tbl_vehicleinfo.chassis_no
							,tbl_vehicleinfo.serial_no
							,(SELECT tbl_make.make FROM tbl_make WHERE tbl_make.make_id = tbl_vehicleinfo.make) AS make_desc
							,(SELECT tbl_model.model FROM tbl_model WHERE tbl_model.model_id = tbl_vehicleinfo.model) AS model_desc
							,(SELECT tbl_color.color FROM tbl_color WHERE tbl_color.color_id = tbl_vehicleinfo.color) AS color_desc
							,(SELECT tbl_year.year FROM tbl_year WHERE tbl_year.year_id = tbl_vehicleinfo.year) AS year_desc
							,(SELECT tbl_payment.payment FROM tbl_payment WHERE tbl_payment.payment_id = tbl_service_master.payment_id) AS payment_mode
							,(CASE WHEN tbl_service_master.trans_status = '0' THEN 'PENDING' 
								WHEN tbl_service_master.trans_status = '1' THEN 'APPROVED' 
								WHEN tbl_service_master.trans_status = '2' THEN 'DISAPPROVED' 
								WHEN tbl_service_master.trans_status = '3' THEN 'CANCELLED' 
								WHEN tbl_service_master.trans_status = '4' THEN 'FOR REPAIR' 
								WHEN tbl_service_master.trans_status = '5' THEN 'FINISHED' 
								WHEN tbl_service_master.trans_status = '6' THEN 'FOR BILLING' 
								WHEN tbl_service_master.trans_status = '7' THEN 'BILLED' 
								WHEN tbl_service_master.trans_status = '8' THEN 'ON-GOING' 
								WHEN tbl_service_master.trans_status = '9' THEN 'FOR APPROVAL' 
								WHEN tbl_service_master.trans_status = '10' THEN 'CLOSED' END) AS `status_desc`
						FROM tbl_service_master
						JOIN v_customer ON v_customer.cust_id = tbl_service_master.customer_id
						JOIN tbl_vehicleinfo ON tbl_vehicleinfo.vehicle_id = tbl_service_master.vehicle_id
						" . $where;
		$resservices = $dbo->query($qryservices);
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<? require_once('inc/datepicker.php'); ?>
<style type="text/css">
	div.divEstimateList{ overflow:scroll; height: 400px; width: 800px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.divEstimateList table{ border: 1px solid #ccc; font-size: 12px; }
	div.divEstimateList table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
	
	div.menu_link{ clear: both; }
	div.menu_link div a,
	div.menu_link div a:active,
	div.menu_link div a:link,
	div.menu_link div a:visited
		{ color: #000; line-height: 28px; font-size: 13px; font-weight: bold; text-decoration: none; padding: 5px 2px; }
	div.menu_link div{ width: 65px; float: left; border-right: 1px solid #ddd; }
	
	div.menu_img div{ width: 65px; float: left; }
	div.estimate_approval{ clear: both; }
	
	div.divEstimateList a,
	div.divEstimateList a:link,
	div.divEstimateList a:visited,
	div.divEstimateList a:active
		{ color: #000; text-decoration: none; }
	div.divEstimateList a:hover{ color: #ccc; text-decoration: none; }
</style>
<body>
	<? 
		require_once('estimate_menu.php');
		if(isset($_POST['search']) && !empty($_POST['search']) && $_POST['search'] == 1){
	?>
	<fieldset form="form_estimate_list" name="form_estimate_list">
	<p id="title">Pending Estimate LIST</p>
	<div class="divEstimateList">
	<table width="2200">
		<tr>
			<th >#</th>
			<th width="150">Estimate Reference No</th>
			<th width="160">Work Order Reference No</th>
			<th width="150">Transaction Date</th>
			<th width="200">Customer Name</th>
			<th width="100">Plate No</th>
			<th width="100">Make</th>
			<th width="50">Year</th>
			<th width="100">Model</th>
			<th width="100">Color</th>
			<th width="200">Variant</th>
			<th width="200">Engine No</th>
			<th width="200">Chassis No</th>
			<th width="200">Payment Mode</th>
			<th width="100">Total Amount</th>
			<th width="100">Status</th>
		</tr>
		<?
			$cnt = 1; 
			foreach($resservices as $rowservices){
				switch($rowservices['trans_status']){
					case 1:
						$color = "color: #00ff00;"; break;
					case 2:
						$color = "color: #ff0000;"; break;
					case 3:
						$color = "color: #0000ff;"; break;
					case 4:
						$color = "color: #00ff00;"; break;
					default:
						$color = "color: #000;"; break;
				}
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}
		?>
		<tr>
			<td align="center" style="<?=$bg . $color;?>"><?=$cnt;?></td>
			<td style="<?=$bg . $color;?>"><a style="<?=$color;?>" href="estimate_edit.php?estimaterefno=<?=$rowservices['estimate_refno'];?>"><?=$rowservices['estimate_refno'];?></a></td>
			<td style="<?=$bg . $color;?>"><a style="<?=$color;?>" href="estimate_print.php?estimaterefno=<?=$rowservices['estimate_refno'];?>" target="_blank">
				<? if(!$rowservices['wo_refno'] == 0){ echo $rowservices['wo_refno']; } ?>
			</a></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowservices['transaction_date'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowservices['custname'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowservices['plate_no'];?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowservices['make_desc'];?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowservices['year_desc'];?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowservices['model_desc'];?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowservices['color_desc'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowservices['variant'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowservices['engine_no'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowservices['chassis_no'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowservices['payment_mode'];?></td>
			<td align="right" style="<?=$bg . $color;?>"><?=number_format($rowservices['total_amount'],2);?></td>
			<td style="<?=$bg . $color;?>"><?=$rowservices['status_desc'];?></td>
		</tr>
		<? $cnt++; } ?>
	</table>
	</div>
	</fieldset>
	<? }else{ ?>
	<p id="title">Search Pending Estimate LIST</p>
	<form method="Post">
	<div class="estimate_approval">
	<table>
		<tr>
			<td width="150">Date</td>
			<td width="10" align="center">:</td>
			<td width="125"><input type="text" id="txtdatefrom" name="txtdatefrom" readonly class="date-pick" style="width: 100"></td>
			<td align="center" width="20">to</td>
			<td width="125"><input type="text" id="txtdateto" name="txtdateto" readonly class="date-pick" style="width: 100"></td>
		</tr>
		<tr>
			<td >Plate No</td>
			<td align="center">:</td>
			<td colspan="3"><input type="text" id="txtplateno" name="txtplateno" style="width: 230"></td>
		</tr>
		<tr>
			<td >Customer name</td>
			<td align="center">:</td>
			<td colspan="3"><input type="text" id="txtcustomer" name="txtcustomer" style="width: 230"></td>
		</tr>
		<tr>
			<td >Estimate Ref#</td>
			<td align="center">:</td>
			<td colspan="3"><input type="text" id="txtestimaterefno" name="txtestimaterefno" style="width: 230"></td>
		</tr>
		<tr>
			<td >Status</td>
			<td align="center">:</td>
			<td colspan="3"><select name="txtstatus" id="txtstatus" style="width: 230;">
				<option value="">Default</option>
				<option value="0">Pending</option>
				<option value="1">Approved</option>
				<option value="2">Disapproved</option>
				<option value="3">Cancelled</option>
				<option value="4">For Repair</option>
				<option value="5">Finished</option>
				<option value="6">For Billing</option>
				<option value="7">Billed</option>
				<option value="8">On-Going</option>
				<option value="9">For Approval</option>
				<option value="10">Closed</option>
			</select></td>
		</tr>
		<tr>
			<td >&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td colspan="3"><input type="submit" name="save" value="" style="cursor: pointer;"> &nbsp; <input type="button" style="cursor: pointer;" name="reset" value=""></td>
		</tr>
	</table>
	</div>
	<input type="hidden" id="search" name="search" value="1" />
	</form>
	<hr noshade style="clear: both;"/><br />
	<? } ?>
</body>
</html>
