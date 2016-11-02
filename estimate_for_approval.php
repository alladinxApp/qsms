<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	chkMenuAccess('estimate_for_approval',$_SESSION['username'],'estimate_list.php');
	
	if(isset($_POST['search']) && !empty($_POST['search']) && $_POST['search'] == 1){
		$datefrom = $_POST['txtdatefrom'];
		$dateto = $_POST['txtdateto'];
		$plateno = $_POST['txtplateno'];
		$custname = $_POST['txtcustomer'];
		$estimateno = $_POST['txtestimaterefno'];
		
		$where = "WHERE (trans_status = '1' OR trans_status = '2' OR trans_status = '3' OR trans_status = '4' OR trans_status = '9') AND po_refno = '0'";
		if(!empty($datefrom) && !empty($dateto)){
			$datefrom = dateFormat($datefrom,"Y-m-d");
			$dateto = dateFormat($dateto,"Y-m-d");
			$where .= " AND (transaction_date between '$datefrom 00:00:00' AND '$dateto 23:59:59')";
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
			$where .= " AND customername LIKE '$custname%'";
		}
		if(!empty($estimateno)){
			$where .= " AND estimate_refno LIKE '$estimateno%'";
		}
		
		$qryservices = new v_service_master;
		$resservices = $dbo->query($qryservices->Query($where));
	}
	if(isset($_POST['option']) && !empty($_POST['option']) && $_POST['option'] == 1){
		$cntERNo = count($_POST['chkEstimateRefNo']);
		if($cntERNo > 0){
			switch($_POST['opt']){
				case 1: 
						$cnt = 1;
						$qry = null;
						foreach($_REQUEST['chkEstimateRefNo'] as $val){							
							$new_refno = getNewNum('WORKORDER',$cnt);
							$qry .= "UPDATE tbl_service_master SET wo_refno = '$new_refno', trans_status = '1' WHERE estimate_refno = '$val'; ";
							$cnt++;
						}
						$qry .= "UPDATE tbl_controlno SET lastseqno = (lastseqno + $cnt) WHERE control_type = 'WORKORDER'; ";
						
						$res = $dbo->query($qry);
						$msg = "approved";
					break;
				case 2: 
						$qry = null;
						foreach($_REQUEST['chkEstimateRefNo'] as $val){
							$qry .= "UPDATE tbl_service_master SET trans_status = '2' WHERE estimate_refno = '$val'; ";
						}
						$res = $dbo->query($qry);
						$msg = "disapproved";
					break;
				case 3: 
						$qry = null;
						foreach($_REQUEST['chkEstimateRefNo'] as $val){
							$qry .= "UPDATE tbl_service_master SET trans_status = '3' WHERE estimate_refno = '$val'; ";
						}
						$res = $dbo->query($qry);
						$msg = "cancelled";
					break;
				case 4: 
						$qry = null;
						foreach($_REQUEST['chkEstimateRefNo'] as $val){
							$qry .= "UPDATE tbl_service_master SET trans_status = '4' WHERE estimate_refno = '$val'; ";
						}
						$res = $dbo->query($qry);
						$msg = "scheduled for appointment";
					break;
				default: break;
			}
			
			if(!$res){
				echo '<script>alert("There has been an error in updating your data! Please re-process hour transaction."); window.location="estimate_approval.php";</script>';
				exit();
			}else{
				$msg1 .= "Service(s) has been successfully " . $msg;
				echo '<script>alert("' . $msg1 . '"); window.location="estimate_approval.php";</script>';
				exit();
			}
		}else{
			echo '<script>alert("please select atleast One(1) record to update!");window.location="estimate_approval.php";</script>';
			exit();
		}
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<? require_once('inc/datepicker.php');?>
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
	<p id="title">Estimate LIST for Approval</p>
	<!--<form method="Post" onSubmit="return ValidateMe();">-->
	<div class="divEstimateList">
	<table width="2200">
		<tr>
			<!--<th width="10">&nbsp;</th>-->
			<th width="10">#</th>
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
			$cnt = 1; foreach($resservices as $rowservices){
				switch($rowservices['trans_status']){
					case 1:
						$color = "color: #009900;"; break;
					case 2:
						$color = "color: #ff0000;"; break;
					case 3:
						$color = "color: #0000ff;"; break;
					case 4:
						$color = "color: #009900;"; break;
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
			<!--<td align="center" style="<?=$bg . $color;?>"><input type="checkbox" name="chkEstimateRefNo[]" id="chkEstimateRefNo[]" value="<?=$rowservices['estimate_refno'];?>"></td>-->
			<td align="center" style="<?=$bg . $color;?>"><?=$cnt;?></td>
			<td style="<?=$bg . $color;?>"><a style="<?=$color;?>" href="estimate_approval.php?estimaterefno=<?=$rowservices['estimate_refno'];?>"><?=$rowservices['estimate_refno'];?></td>
			<td style="<?=$bg . $color;?>">&nbsp;<a style="<?=$color;?>" href="estimate_print.php?estimaterefno=<?=$rowservices['estimate_refno'];?>" target="_blank">
				<? if(!$rowservices['wo_refno'] == 0){ echo $rowservices['wo_refno']; } ?>
			</a></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowservices['transaction_date'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowservices['customername'];?></td>
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
	<!--<div>
		<table>
			<tr>
				<td>Select Option: </td>
				<td><select name="opt" id="opt" style="width: 200px;">
					<option value=""></option>
					<option value="1">Approve</option>
					<option value="2">Disapprove</option>
					<option value="3">Cancel</option>
					<option value="4">Appointment</option>
				</select></td>
				<td><input type="submit" name="tbnsubmit" id="btnsubmit" value="" /></td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="option" id="option" value="1" />
	</form>-->
	<? }else{ ?>
	<form method="Post" >
	<p id="title">Search Approved Estimate LIST</p>
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
	<script type="text/javascript">
		function ValidateMe(){
			var opt = document.getElementById('opt');	
			if(opt.value == ""){
				alert("Please select an option to process your update!");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
