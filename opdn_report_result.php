<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
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
	<p id="title">OPDN Report Result</p>
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
	<table width="15550">
		<tr>
			<th width="100">DocNum</th>
			<th width="100">DocEntry</th>
			<th width="100">DocType</th>
			<th width="100">HandWritten</th>
			<th width="100">Printed</th>
			<th width="100">DocDate</th>
			<th width="100">DocDueDate</th>
			<th width="100">CardCode</th>
			<th width="100">CardName</th>
			<th width="100">Address</th>
			<th width="100">NumAtCard</th>
			<th width="100">DocTotal</th>
			<th width="100">AttachmentEntry</th>
			<th width="100">DocCurrency</th>
			<th width="100">DocRate</th>
			<th width="100">Reference1</th>
			<th width="100">Reference2</th>
			<th width="100">Comments</th>
			<th width="100">JournalMemo</th>
			<th width="100">PaymentGroupCode</th>
			<th width="100">DocTime</th>
			<th width="100">SalesPersonCode</th>
			<th width="100">TransportationCode</th>
			<th width="100">Confirmed</th>
			<th width="100">ImportFileNum</th>
			<th width="100">SummeryType</th>
			<th width="100">ContactPersonCode</th>
			<th width="100">ShowSCN</th>
			<th width="100">Series</th>
			<th width="100">TaxDate</th>
			<th width="100">PartialSupply</th>
			<th width="100">DocObjectCode</th>
			<th width="100">ShipToCode</th>
			<th width="100">Indicator</th>
			<th width="100">FederalTaxID</th>
			<th width="100">DiscountPercent</th>
			<th width="100">PaymentReference</th>
			<th width="100">DocTotalFc</th>
			<th width="100">Form1099</th>
			<th width="100">Box1099</th>
			<th width="100">RevisionPo</th>
			<th width="100">RequriedDate</th>
			<th width="100">CancelDate</th>
			<th width="100">BlockDunning</th>
			<th width="100">Pick</th>
			<th width="100">PaymentMethod</th>
			<th width="100">PaymentBlock</th>
			<th width="100">PaymentBlockEntry</th>
			<th width="100">CentralBankIndicator</th>
			<th width="100">MaximumCashDiscount</th>
			<th width="100">Project</th>
			<th width="100">ExemptionValidityDateFrom</th>
			<th width="100">ExemptionValidityDateTo</th>
			<th width="100">WareHouseUpdateType</th>
			<th width="100">Rounding</th>
			<th width="100">ExternalCorrectedDocNum</th>
			<th width="100">InternalCorrectedDocNum</th>
			<th width="100">DeferredTax</th>
			<th width="100">TaxExemptionLetterNum</th>
			<th width="100">AgentCode</th>
			<th width="100">NumberOfInstallments</th>
			<th width="100">ApplyTaxOnFirstInstallment</th>
			<th width="100">VatDate</th>
			<th width="100">DocumentsOwner</th>
			<th width="100">FolioPrefixString</th>
			<th width="100">FolioNumber</th>
			<th width="100">DocumentSubType</th>
			<th width="100">BPChannelCode</th>
			<th width="100">BPChannelContact</th>
			<th width="100">Address2</th>
			<th width="100">PayToCode</th>
			<th width="100">ManualNumber</th>
			<th width="100">UseShpdGoodsAct</th>
			<th width="100">IsPayToBank</th>
			<th width="100">PayToBankCountry</th>
			<th width="100">PayToBankCode</th>
			<th width="100">PayToBankAccountNo</th>
			<th width="100">PayToBankBranch</th>
			<th width="100">BPL_IDAssignedToInvoice</th>
			<th width="100">DownPayment</th>
			<th width="100">ReserveInvoice</th>
			<th width="100">LanguageCode</th>
			<th width="100">TrackingNumber</th>
			<th width="100">PickRemark</th>
			<th width="100">ClosingDate</th>
			<th width="100">SequenceCode</th>
			<th width="100">SequenceSerial</th>
			<th width="100">SeriesString</th>
			<th width="100">SubSeriesString</th>
			<th width="100">SequenceModel</th>
			<th width="100">UseCorrectionVATGroup</th>
			<th width="100">DownPaymentAmount</th>
			<th width="100">DownPaymentPercentage</th>
			<th width="100">DownPaymentType</th>
			<th width="100">DownPaymentAmountSC</th>
			<th width="100">DownPaymentAmountFC</th>
			<th width="100">VatPercent</th>
			<th width="100">ServiceGrossProfitPercent</th>
			<th width="100">OpeningRemarks</th>
			<th width="100">ClosingRemarks</th>
			<th width="100">RoundingDiffAmount</th>
			<th width="100">ControlAccount</th>
			<th width="100">InsuranceOperation347</th>
			<th width="100">ArchiveNonremovableSalesQuotation</th>
			<th width="100">GTSChecker</th>
			<th width="100">GTSPayee</th>
			<th width="100">ExtraMonth</th>
			<th width="100">ExtraDays</th>
			<th width="100">CashDiscountDateOffset</th>
			<th width="100">StartFrom</th>
			<th width="100">NTSApproved</th>
			<th width="100">ETaxWebSite</th>
			<th width="100">ETaxNumber</th>
			<th width="100">NTSApprovedNumber</th>
			<th width="100">EDocGenerationType</th>
			<th width="100">EDocSeries</th>
			<th width="100">EDocExportFormat</th>
			<th width="100">EDocStatus</th>
			<th width="100">EDocErrorCode</th>
			<th width="100">EDocErrorMessage</th>
			<th width="100">DownPaymentStatus</th>
			<th width="100">GroupSeries</th>
			<th width="100">GroupNumber</th>
			<th width="100">GroupHandWritten</th>
			<th width="100">ReopenOriginalDocument</th>
			<th width="100">ReopenManuallyClosedOrCanceledDocument</th>
			<th width="100">CreateOnlineQuotation</th>
			<th width="100">POSEquipmentNumber</th>
			<th width="100">POSManufacturerSerialNumber</th>
			<th width="100">POSCashierNumber</th>
			<th width="100">ApplyCurrentVATRatesForDownPaymentsToDraw</th>
			<th width="100">ClosingOption</th>
			<th width="100">SpecifiedClosingDate</th>
			<th width="100">OpenForLandedCosts</th>
			<th width="100">RelevantToGTS</th>
			<th width="100">AnnualInvoiceDeclarationReference</th>
			<th width="100">Supplier</th>
			<th width="100">Releaser</th>
			<th width="100">Receiver</th>
			<th width="100">BlanketAgreementNumber</th>
			<th width="100">IsAlteration</th>
			<th width="100">AssetValueDate</th>
			<th width="100">DocumentDelivery</th>
			<th width="100">AuthorizationCode</th>
			<th width="100">StartDeliveryDate</th>
			<th width="100">StartDeliveryTime</th>
			<th width="100">EndDeliveryDate</th>
			<th width="100">EndDeliveryTime</th>
			<th width="100">VehiclePlate</th>
			<th width="100">ATDocumentType</th>
			<th width="100">ElecCommStatus</th>
			<th width="100">ReuseDocumentNum</th>
			<th width="100">ReuseNotaFiscalNum</th>
			<th width="100">PrintSEPADirect</th>
			<th width="100">FiscalDocNum</th>
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
			<td style="<?=$style;?>">1</td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['billing_date'],"m/d/Y");?></td>
			<td align="center" style="<?=$style;?>"><?=dateFormat($row['billing_date'],"m/d/Y");?></td>
			<td style="<?=$style;?>"><?=$row['supplier_code'];?></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td style="<?=$style;?>"></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['billing_amount'],2);?></td>
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
		</tr>
		<? $cnt++; } ?>
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="opdnreport" />
	</form>
	<? } ?>
</body>
</html>