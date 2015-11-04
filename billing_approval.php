<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$billingrefno = $_GET['billingrefno'];
	
	$qrybillingdtl = "SELECT * FROM v_billing_detail WHERE billing_refno = '$billingrefno'";
	$resbillingdtl = $dbo->query($qrybillingdtl);
	$resbillingdtl2 = $dbo->query($qrybillingdtl);
	foreach($resbillingdtl2 as $rowbillingdtl){
		$wo_refnos[] = $rowbillingdtl['wo_refno'];
	}
	
	if(isset($_POST['postbill']) && !empty($_POST['postbill']) && $_POST['postbill'] == 1){
		$sql = null;
		$sql .= "UPDATE tbl_billing SET billing_status = '1' WHERE billing_refno = '$billingrefno'; ";
		for($i = 0;$i < count($wo_refnos);$i++){
			$sql .= "UPDATE tbl_service_master SET trans_status = '7' WHERE wo_refno = '$wo_refnos[$i]'; ";
		}

		$res = $dbo->query($sql);
		if(!$res){
			echo '<script>alert("There has been an error on your billing transaction! Please double check all the data and save.");</script>';
		}else{
			echo '<script>alert("Work Order(s) successfully billed.");</script>';
		}
		echo '<script>window.location="billing_list.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<style type="text/css">
	div.divEstimateList{ overflow:scroll; height: 200px; width: 800px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.divEstimateList table{ border: 1px solid #ccc; font-size: 12px; }
	div.divEstimateList table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
	
	div.estimate_approval{ clear: both; }
	div.divEstimateList a,
	div.divEstimateList a:link,
	div.divEstimateList a:visited,
	div.divEstimateList a:active
		{ color: #000; text-decoration: none; }
	div.divEstimateList a:hover{ color: #ccc; text-decoration: none; }
</style>
<body>
	<table><tr><td><a href="billing_print.php?billingrefno=<?=$billingrefno;?>" target="_blank"><img src="images/bill_statement.png" border="0" style="pointer: cursor;" width="130" /></a></td></tr></table>
	<fieldset form="form_estimate_list" name="form_estimate_list">
	<p id="title">Billing for Posting</p>
	<div class="divEstimateList">
	<table width="750">
		<tr>
			<th width="10">#</th>
			<th width="150">Work Order Ref#</th>
			<th width="150">Billing Ref#</th>
			<th width="150">Transaction Date</th>
			<th width="140">Plate#</th>
			<th width="140">Total Amount</th>
		</tr>
		<?
			$cnt = 1;
			foreach($resbillingdtl as $rowBILLINGDTL){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}
				$grand += $rowBILLINGDTL['total_amount'];
				$discount += $rowBILLINGDTL['discount'];
		?>
		<tr>
			<td align="center" style="<?=$bg . $color;?>"><?=$cnt;?></td>
			<td style="<?=$bg . $color;?>"><?=$rowBILLINGDTL['wo_refno'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowBILLINGDTL['billing_refno'];?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=dateFormat($rowBILLINGDTL['transaction_date'],"m/d/Y h:i");?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowBILLINGDTL['plate_no'];?></td>
			<td align="right" style="<?=$bg . $color;?>"><?=number_format($rowBILLINGDTL['total_amount'],2);?></td>
		</tr>
		<? $cnt++; } $total = $grand / 1.12; $vat = $grand - ($total - $discount); ?>
	</table>
	</div>
	<table>
		<form method="Post">
		<tr>
			<td rowspan="3" width="600"><input type="submit" name="btnsubmit" id="btnsubmit" value="" /></td>
			<td width="100">Total</td>
			<td width="100" align="right"><?=number_format($total,2);?></td>
			<td width="20">&nbsp;</td>
		</tr>
		<input type="hidden" name="postbill" id="postbill" value="1">
		</form>
		<tr>
			<td>Vat 12%</td>
			<td align="right"><?=number_format($vat,2);?></td>
		</tr>
		<tr>
			<td>Grand Total</td>
			<td align="right"><?=number_format($grand,2);?></td>
		</tr>
	</table>
	</fieldset>
</body>
</html>
