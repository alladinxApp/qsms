<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$datefrom = Date("m-d-Y");
	$dateto = Date("m-d-Y");

	if(isset($_POST['search']) && !empty($_POST['search']) && $_POST['search'] == 1){
		
		$status = $_POST['txtstatus'];
		$porefno = $_POST['txtporefno'];
		$cnt = 0;

		$where = "WHERE 1 ";

		if($status != ""){
			$where .= " AND status = '$status' ";
			$cnt++;
		}

		if(!empty($porefno)){
			$where .= " AND po_reference_no = '$porefno'";
			$cnt++;
		}

		if(!empty($_POST['txtdatefrom']) || !empty($_POST['txtdateto']) || $cnt == 0){
			$datefrom = Date("Y-m-d 00:00");
			$dateto = Date("Y-m-d 23:59");
		
			if(!empty($_POST['txtdatefrom'])){
				$datefrom = dateFormat($_POST['txtdatefrom'],"Y-m-d") . " 00:00";
			}

			if(!empty($_POST['txtdateto'])){
				$dateto = dateFormat($_POST['txtdateto'],"Y-m-d") . " 23:59";
			}

			$where .= " AND (po_date between '$datefrom' AND '$dateto') ";
		}		
		
		$qrypomst = "SELECT * FROM v_po_mst " . $where;
		$respomst = $dbo->query($qrypomst);
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
		require_once('po_menu.php');
		if(isset($_POST['search']) && !empty($_POST['search']) && $_POST['search'] == 1){
	?>
	<fieldset form="form_estimate_list" name="form_estimate_list">
	<p id="title">PURCHASE ORDER LIST</p>
	<div class="divEstimateList">
	<table width="1350">
		<tr>
			<th width="30">&nbsp;</th>
			<th width="20">#</th>
			<th width="100">PO Ref No</th>
			<th width="100">PO Date</th>
			<th width="100">Total Amount</th>
			<th width="100">Status</th>
			<th width="200">Supplier</th>
			<th width="200">Deliver To</th>
			<th width="200">Delivery Address</th>
		</tr>
		<?
			$cnt = 1; 
			foreach($respomst as $rowpomst){
				switch($rowpomst['status']){
					case 1:
						$color = "color: #00ff00;"; break;
					case 2:
						$color = "color: #ff0000;"; break;
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
			<td align="center" style="<?=$bg . $color;?>"><a href="po_edit.php?id=<?=$rowpomst['po_reference_no'];?>"><img src="images/edit.png" width="15" /></a></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$cnt;?></td>
			<td style="<?=$bg . $color;?>"><?=$rowpomst['po_reference_no'];?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=dateFormat($rowpomst['po_date'],"F d, Y");?></td>
			<td align="right" style="<?=$bg . $color;?>"><?=number_format($rowpomst['total_amount'],2);?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowpomst['status_desc'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowpomst['supplier_name'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowpomst['deliver_to'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowpomst['delivery_address'];?></td>
		</tr>
		<? $cnt++; } ?>
	</table>
	</div>
	</fieldset>
	<? }else{ ?>
	<p id="title">Search Pending PO LIST</p>
	<form method="Post">
	<div class="estimate_approval">
	<table>
		<tr>
			<td width="150">Date</td>
			<td width="10" align="center">:</td>
			<td width="125"><input type="text" id="txtdatefrom" name="txtdatefrom" value="" readonly class="date-pick" style="width: 100"></td>
			<td align="center" width="20">to</td>
			<td width="125"><input type="text" id="txtdateto" name="txtdateto" readonly class="date-pick" style="width: 100"></td>
		</tr>
		<tr>
			<td >PO Ref No</td>
			<td align="center">:</td>
			<td colspan="3"><input type="text" id="txtporefno" name="txtporefno" style="width: 230"></td>
		</tr>
		<tr>
			<td >Status</td>
			<td align="center">:</td>
			<td colspan="3"><select name="txtstatus" id="txtstatus" style="width: 230;">
				<option value="">Default</option>
				<option value="0">Pending</option>
				<option value="1">Approved</option>
				<option value="2">Disapproved</option>
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
