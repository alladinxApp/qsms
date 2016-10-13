<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d");
		$to = dateFormat($_POST['txtdateto'],"Y-m-d");
		$dtfrom = $from . " 00:00:000";
		$dtto = $to . " 23:59:000";
		$dt = date("Ymdhis");

		if(empty($dtfrom) && empty($dtto)){
			$dtfrom = date("Y-m-d 00:00");
			$dtto = date("Y-m-d 23:59");
		}

		$sql = "SELECT tbl_billing.* FROM tbl_billing
					JOIN tbl_service_master ON tbl_service_master.wo_refno = tbl_billing.wo_refno
						AND tbl_service_master.payment_id = 'PAY00000004'
						AND (tbl_service_master.trans_status = '7' 
							OR tbl_service_master.trans_status = '8'
							OR tbl_service_master.trans_status = '9'
							OR tbl_service_master.trans_status = '10')
	 			WHERE 1 AND tbl_billing.billing_date between '$dtfrom' AND '$dtto' $where
	 			ORDER BY tbl_billing.billing_date";
		$qry = mysql_query($sql);
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<? require_once('inc/datepicker.php'); ?>
</head>
<style>
	div.divEstimateList{ height: 400px; width: 1250px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; overflow: scroll; }
	div.divEstimateList table{ border: 1px solid #ccc; font-size: 12px; }
	div.divEstimateList table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
</style>
<body>
	<p id="title">CREDIT CARD Report Result</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtdatefrom" name="txtdatefrom" value="<?=$from;?>">
	<input type="hidden" id="txtdateto" name="txtdateto" value="<?=$to;?>">
	<table width="100%">
		<tr>
			<td width="120">Date From </td>
			<td width="10">:</td>
			<td width="300"><?=dateFormat($from,"F d, Y");?></td>
		</tr>
		<tr>
			<td >Date To </td>
			<td>:</td>
			<td ><?=dateFormat($to,"F d, Y");?></td>
		</tr>
	</table>
	<div class="divEstimateList">
	<table width="2100">
		<tr>
			<th width="10">ParentKey</th>
			<th width="10">LineNum</th>
			<th width="100">CreditCard</th>
			<th width="100">CreditAcct</th>
			<th width="100">CreditCardNumber</th>
			<th width="100">CardValidUntil</th>
			<th width="100">VoucherNum</th>
			<th width="100">OwnerIdNum</th>
			<th width="100">OwnerPhone</th>
			<th width="100">PaymentMethodCode</th>
			<th width="100">NumOfPayments</th>
			<th width="100">FirstPaymentDue</th>
			<th width="100">FirstPaymentSum</th>
			<th width="100">AdditionalPaymentSum</th>
			<th width="100">CreditSum</th>
			<th width="100">CreditCur</th>
			<th width="100">CreditRate</th>
			<th width="100">ConfirmationNum</th>
			<th width="100">NumOfCreditPayments</th>
			<th width="100">CreditType</th>
			<th width="100">SplitPayments</th>
		</tr>
		<? 
			$cnt = 0; 
			while($row = mysql_fetch_array($qry)){ 
				$bg = null;
				if($cnt%2){
					$bg = 'background: #eee;';
				}
				$style = $bg;
		?>
		<tr>
			<td align="center" style="<?=$style;?>">1</td>
			<td align="center" style="<?=$style;?>"><?=$cnt;?></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>">1</td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['total_amount'],2);?></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
		</tr>
		<? $cnt++; } ?>	
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="creditcardreport" />
	</form>
	<? } ?>
</body>
</html>