<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$custid = $_GET['custid'];
	$type = $_GET['type'];
	switch($type){
		case 0:
			$payment = "(payment_id = 'PAY00000001' OR payment_id = 'PAY00000002')";
			break;
		case 1:
			$payment = "payment_id = 'PAY00000003'";
			break;
		case 2:
			$payment = "payment_id = 'PAY00000004'";
			break;
		default:
			break;
	}
	$qrybillingdtl = "SELECT * FROM v_for_billing_detail WHERE customer_id = '$custid' AND $payment";
	$resbillingdtl = $dbo->query($qrybillingdtl);
	
	if(isset($_POST['billed']) && !empty($_POST['billed']) && $_POST['billed'] == 1){
		$total_amnt = str_replace(",","",$_POST['txtGrandTotal']);
		$sql = null;
		$newrefno = getNewNum('BILLING');
		$sql3 = null;
		foreach($_REQUEST['chkworefno'] as $val){
			$sql1 = null;
			$sql2 = null;
			
			$val = explode("#",$val);
			$wo = $val[0];
			$amnt = $val[1];
			$sql1 = "INSERT INTO tbl_billing(wo_refno,billing_refno,billing_date,total_amount)
					VALUES('$wo','$newrefno','$today','$amnt'); ";
			
			$sql2 = "UPDATE tbl_service_master SET trans_status = '6' WHERE wo_refno = '$wo'; ";
			mysql_query($sql1);
			mysql_query($sql2);
		}
		$sql3 = "UPDATE tbl_controlno SET lastseqno = (lastseqno + 1) WHERE control_type = 'BILLING'; ";
		
		$res = mysql_query($sql3);
		
		if(!$res){
			echo '<script>alert("There has been an error on saving your billing! Please double check all the data and save.");</script>';
		}else{
			echo '<script>alert("Billing successfully saved.");</script>';
		}
		echo '<script>window.location="billing_for_approval.php";</script>';
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />
</head>
<? require_once('inc/datepicker.php'); ?>
<script type="text/javascript">
	function retTotalAmount(){
		var chk = document.getElementsByName('chkworefno[]');
		var len = chk.length;
		var total = 0;
		
		for(i=0;i<len;i++){
			if(chk[i].checked){
				var val = chk[i].value;
				var str = val.split("#");
				total = parseFloat(total) + parseFloat(str[1]);
			}
		}
		document.getElementById("txtGrandTotal").value = formatNumber(total);
		var subtotal = total / 1.12;
		document.getElementById("txtTotal").value = formatNumber(subtotal);
		var vat = total - subtotal;
		document.getElementById("txtVat").value = formatNumber(vat);
	}
	function SelectAll(chkval){
		var chk = document.getElementsByName('chkworefno[]');
		var len = chk.length;
		var total = 0;
		
		if(chkval.checked){
			document.getElementById('chkAll').value = 1;
			for(i=0;i<len;i++){
				chk[i].checked = true;
				var val = chk[i].value;
				var str = val.split("#");
				total = parseFloat(total) + parseFloat(str[1]);
			}
		}else{
			document.getElementById('chkAll').value = 0;
			for(i=0;i<len;i++){
				chk[i].checked = false;
				total = 0;
			}
		}
		document.getElementById("txtGrandTotal").value = formatNumber(total);
		var subtotal = total / 1.12;
		document.getElementById("txtTotal").value = formatNumber(subtotal);
		var vat = total - subtotal;
		document.getElementById("txtVat").value = formatNumber(vat);
	}
	function formatNumber(num) {
		var p = num.toFixed(2).split(".");
		return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
			return  num + (i && !(i % 3) ? "," : "") + acc;
		}, "") + "." + p[1];
	}
	function getChange(amnt){
		var gtotal = document.getElementById("txtGrandTotal").value;
		var change = parseFloat(amnt) - parseFloat(gtotal.replace(/,/g, ''));
		
		document.getElementById("txtchange").value = formatNumber(change);
	}
</script>
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
	<form method="Post" onSubmit="return validateme();">
	<fieldset form="form_estimate_list" name="form_estimate_list">
	<p id="title">Work Order LIST for Billing</p>
	<div class="divEstimateList">
	<table width="750">
		<tr>
			<th width="10"><input type="checkbox" name="chkAll" id="chkAll" checked onclick="SelectAll(this);"></th>
			<th width="10">#</th>
			<th width="150">Work Order Ref#</th>
			<th width="150">Purchase Order Ref#</th>
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
		?>
		<tr>
			<td align="center" style="<?=$bg . $color;?>"><input type="checkbox" name="chkworefno[]" id="chkworefno[]" onclick="return retTotalAmount();" value="<?=$rowBILLINGDTL['wo_refno'] .'#'. $rowBILLINGDTL['total_amount'];?>" checked /></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$cnt;?></td>
			<td style="<?=$bg . $color;?>"><?=$rowBILLINGDTL['wo_refno'];?></td>
			<td style="<?=$bg . $color;?>"><?=$rowBILLINGDTL['po_refno'];?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowBILLINGDTL['transaction_date'];?></td>
			<td align="center" style="<?=$bg . $color;?>"><?=$rowBILLINGDTL['plate_no'];?></td>
			<td align="right" style="<?=$bg . $color;?>"><?=number_format($rowBILLINGDTL['total_amount'],2);?></td>
		</tr>
		<? $cnt++; } $total = $grand / 1.12; $vat = $grand - $total; ?>
	</table>
	</div>
	<table>
		<tr>
			<td rowspan="5" valign="top" width="600"><input type="submit" name="btnsubmit" id="btnsubmit" value="" /></td>
			<td width="100">Total</td>
			<td width="100" align="right"><input name="txtTotal" id="txtTotal" type="text" value="<?=number_format($total,2);?>" readonly style="text-align: right;" /></td>
			<td width="20">&nbsp;</td>
		</tr>
		<tr>
			<td>Vat 12%</td>
			<td align="right"><input type="text" name="txtVat" id="txtVat" value="<?=number_format($vat,2);?>" style="text-align: right;" /></td>
		</tr>
		<tr>
			<td>Grand Total</td>
			<td align="right"><input type="text" name="txtGrandTotal" id="txtGrandTotal" value="<?=number_format($grand,2);?>" readonly style="text-align: right;" /></td>
		</tr>
		<tr>
			<td>Amount Received</td>
			<td align="right"><input type="text" name="txtamountreceived" id="txtamountreceived" value="" onKeyup="getChange(this.value);" style="text-align: right;" /></td>
		</tr>
		<tr>
			<td>Change</td>
			<td align="right"><input type="text" name="txtchange" id="txtchange" value="" readonly style="text-align: right;" /></td>
		</tr>
	</table>
	</fieldset>
	<input type="hidden" name="billed" id="billed" value="1" />
	</form>
	<script>
		function validateme(){
			var change = document.getElementById("txtchange");
			
			if(change.value.replace(/,/g, '') < 0 || change.value == ""){
				alert("Please corret the amount received! Amount received is less than the Grand Total Amount.");
				return false;
			}else{
				return true;
			}
		}
	</script>
</body>
</html>
