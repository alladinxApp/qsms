<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	if(isset($_POST['search']) && !empty($_POST['search']) && $_POST['search'] == 1){
		$datefrom = $_POST['txtdatefrom'];
		$dateto = $_POST['txtdateto'];
		$billingrefno = $_POST['txtbillingrefno'];
		
		$where = "WHERE billing_status = '1' ";
		if(!empty($datefrom) && !empty($dateto)){
			$datefrom = dateFormat($datefrom,"Y-m-d");
			$dateto = dateFormat($dateto,"Y-m-d");
			$where .= " AND (billing_date between '$datefrom 00:00' AND '$dateto 23:59')";
		}else if(empty($datefrom) && !empty($dateto)){
			$dateto = dateFormat($dateto,"Y-m-d");
			$where .= " AND billing_date = '$dateto'";
		}else if(empty($dateto) && !empty($datefrom)){
			$datefrom = dateFormat($datefrom,"Y-m-d");
			$where .= " AND billing_date = '$datefrom'";
		}else if(!empty($billingrefno)){
			$where .= " AND billing_refno LIKE '%$billingrefno%'";
		}else{}
		
		$qrybilling = "SELECT * FROM v_billing " . $where;
		$resbilling = $dbo->query($qrybilling);
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
		require_once('billing_menu.php');
		if(isset($_POST['search']) && !empty($_POST['search']) && $_POST['search'] == 1){ 
	?>
	<fieldset form="form_estimate_list" name="form_estimate_list">
	<p id="title">Posted Billing LIST</p>
	<div class="divEstimateList">
	<table width="610">
		<tr>
			<th >#</th>
			<th width="150">Billing Reference No</th>
			<th width="150">Transaction Date</th>
			<th width="150">Customer Name</th>
			<th width="150">Amount</th>
		</tr>
		<?
			$cnt = 1;
			foreach($resbilling as $rowbilling){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}
				switch($rowservices['trans_status']){
					case 5:
						$color = "color: #009900;"; break;
					default:
						$color = "color: #000;"; break;
				}
		?>
		<tr>
			<td align="center" style="<?=$bg . $color;?>"><?=$cnt;?></td>
			<td style="<?=$bg . $color;?>"><a target="_blank" href="billing_print.php?billingrefno=<?=$rowbilling['billing_refno'];?>"><?=$rowbilling['billing_refno'];?></a></td>
			<td style="<?=$bg . $color;?>"><?=dateFormat($rowbilling['billing_date'],"M d, Y");?></td>
			<td style="<?=$bg . $color;?>"><?=$rowbilling['customername'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowbilling['total_amount'];?></td>
		</tr>
		<? $cnt++; } ?>
	</table>
	</div>
	</fieldset>
	<? }else{ ?>
	<form method="Post">
	<legend><p id="title">Search Billing Reference</p></legend>
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
			<td >Billing Ref#</td>
			<td align="center">:</td>
			<td colspan="3"><input type="text" id="txtbillingrefno" name="txtbillingrefno" style="width: 230"></td>
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
