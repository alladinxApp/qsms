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

		$sql_lbs_master = "SELECT * FROM v_customer
 			WHERE 1 AND v_customer.cust_created between '$dtfrom' AND '$dtto' $where
 			ORDER BY v_customer.cust_created";
		$qry = mysql_query($sql_lbs_master);

		$ln .= "ORCT REPORT\r\n\r\n";
		
		$ln .= "From: ," . $dtfrom . "\r\n";
		$ln .= "To: ," . $dtto . "\r\n";
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
	div.divEstimateList{ height: 400px; width: 800px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.divEstimateList table{ border: 1px solid #ccc; font-size: 12px; }
	div.divEstimateList table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
</style>
<body>
	<p id="title">ORCT Report Result</p>
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
	<table width="1350">
		<tr>
			<th width="10">DocNum</th>
			<th width="10">DocType</th>
			<th width="100">HandWritten</th>
			<th width="100">Printed</th>
			<th width="100">DocDate</th>
			<th width="100">CardCode</th>
			<th width="100">CardName</th>
			<th width="100">Address</th>
			<th width="100">CashAccount</th>
			<th width="100">DocCurrency</th>
			<th width="100">CashSum</th>
			<th width="100">CheckAccount</th>
			<th width="100">TransferAccount</th>
			<th width="100">TransferSum</th>
			<th width="100">TransferDate</th>
			<th width="100">TransferReference</th>
			<th width="100">LocalCurrency</th>
			<th width="100">DocRate</th>
			<th width="100">Reference1</th>
			<th width="100">Reference2</th>
			<th width="100">CounterReference</th>
			<th width="100">Remarks</th>
			<th width="100">JournalRemarks</th>
			<th width="100">SplitTransaction</th>
			<th width="100">ContactPersonCode</th>
			<th width="100">ApplyVAT</th>
			<th width="100">TaxDate</th>
			<th width="100">Series</th>
			<th width="100">BankCode</th>
			<th width="100">BankAccount</th>
			<th width="100">DiscountPercent</th>
			<th width="100">ProjectCode</th>
			<th width="100">CurrencyIsLocal</th>
			<th width="100">DeductionPercent</th>
			<th width="100">DeductionSum</th>
			<th width="100">BoeAccount</th>
			<th width="100">BillOfExchangeAmount</th>
			<th width="100">BillofExchangeStatus</th>
			<th width="100">BillOfExchangeAgent</th>
			<th width="100">WTCode</th>
			<th width="100">WTAmount</th>
			<th width="100">Proforma</th>
			<th width="100">PayToBankCode</th>
			<th width="100">PayToBankBranch</th>
			<th width="100">PayToBankAccountNo</th>
			<th width="100">PayToCode</th>
			<th width="100">PayToBankCountry</th>
			<th width="100">IsPayToBank</th>
			<th width="100">PaymentPriority</th>
			<th width="100">TaxGroup</th>
			<th width="100">BankChargeAmount</th>
			<th width="100">BankChargeAmountInSC</th>
			<th width="100">WtBaseSum</th>
			<th width="100">VatDate</th>
			<th width="100">TransactionCode</th>
			<th width="100">PaymentType</th>
			<th width="100">TransferRealAmount</th>
			<th width="100">DocObjectCode</th>
			<th width="100">DocTypte</th>
			<th width="100">DueDate</th>
			<th width="100">LocationCode</th>
			<th width="100">ControlAccount</th>
			<th width="100">BPLID</th>
		</tr>
		<? 
			$cnt = 1; 
			while($row = mysql_fetch_array($qry)){ 
				$bg = null;
				if($cnt%2){
					$bg = 'background: #eee;';
				}
				$style = $bg;
		?>
		<tr>
			<td align="center" style="<?=$style;?>"><?=$cnt;?>1</td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['cust_created'],"m/d/Y");?>dt</td>
			<td style="<?=$style;?>"><?=$row['cust_id'];?>cst</td>
			<td style="<?=$style;?>"><?=$row['custname'];?>cst</td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['cust_created'],"m/d/Y");?>dt</td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>">SUC</td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['cust_created'],"m/d/Y");?>dt</td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['cust_created'],"m/d/Y");?>dt</td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
		</tr>
		<? $cnt++; } ?>
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="orctreport" />
	</form>
	<? } ?>
</body>
</html>