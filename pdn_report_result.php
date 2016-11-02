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

		$sql = "SELECT tbl_rr_dtl.*,tbl_items.SAP_item_code,tbl_items.item_description FROM tbl_rr_dtl
					JOIN tbl_rr_mst ON tbl_rr_mst.rr_reference_no = tbl_rr_dtl.rr_reference_no
					JOIN tbl_items ON tbl_items.item_code = tbl_rr_dtl.item_code
	 			WHERE 1 AND tbl_rr_mst.rr_date between '$dtfrom' AND '$dtto' $where
	 			ORDER BY tbl_rr_mst.rr_date";
		$qry = mysql_query($sql);
		$ln .= "PDN REPORT\r\n\r\n";
		
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
	<p id="title">PDN Report Result</p>
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
			<th width="10">ParentKey</th>
			<th width="10">LineNum</th>
			<th width="100">ItemCode</th>
			<th width="100">ItemDescription</th>
			<th width="100">Quantity</th>
			<th width="100">ShipDate</th>
			<th width="100">Price</th>
			<th width="100">PriceAfterVAT</th>
			<th width="100">Currency</th>
			<th width="100">Rate</th>
			<th width="100">DiscountPercent</th>
			<th width="100">VendorNum</th>
			<th width="100">SerialNum</th>
			<th width="100">WarehouseCode</th>
			<th width="100">SalesPersonCode</th>
			<th width="100">CommisionPercent</th>
			<th width="100">TreeType</th>
			<th width="100">AccountCode</th>
			<th width="100">UseBaseUnits</th>
			<th width="100">SupplierCatNum</th>
			<th width="100">CostingCode</th>
			<th width="100">ProjectCode</th>
			<th width="100">BarCode</th>
			<th width="100">VatGroup</th>
			<th width="100">Height1</th>
			<th width="100">Hight1Unit</th>
			<th width="100">Height2</th>
			<th width="100">Height2Unit</th>
			<th width="100">Lengh1</th>
			<th width="100">Lengh1Unit</th>
			<th width="100">Lengh2</th>
			<th width="100">Lengh2Unit</th>
			<th width="100">Weight1</th>
			<th width="100">Weight1Unit</th>
			<th width="100">Weight2</th>
			<th width="100">Weight2Unit</th>
			<th width="100">Factor1</th>
			<th width="100">Factor2</th>
			<th width="100">Factor3</th>
			<th width="100">Factor4</th>
			<th width="100">BaseType</th>
			<th width="100">BaseEntry</th>
			<th width="100">BaseLine</th>
			<th width="100">Volume</th>
			<th width="100">VolumeUnit</th>
			<th width="100">Width1</th>
			<th width="100">Width1Unit</th>
			<th width="100">Width2</th>
			<th width="100">Width2Unit</th>
			<th width="100">Address</th>
			<th width="100">TaxCode</th>
			<th width="100">TaxType</th>
			<th width="100">TaxLiable</th>
			<th width="100">BackOrder</th>
			<th width="100">FreeText</th>
			<th width="100">ShippingMethod</th>
			<th width="100">CorrectionInvoiceItem</th>
			<th width="100">CorrInvAmountToStock</th>
			<th width="100">CorrInvAmountToDiffAcct</th>
			<th width="100">WTLiable</th>
			<th width="100">DeferredTax</th>
			<th width="100">MeasureUnit</th>
			<th width="100">UnitsOfMeasurment</th>
			<th width="100">LineTotal</th>
			<th width="100">TaxPercentagePerRow</th>
			<th width="100">TaxTotal</th>
			<th width="100">ConsumerSalesForecast</th>
			<th width="100">ExciseAmount</th>
			<th width="100">CountryOrg</th>
			<th width="100">SWW</th>
			<th width="100">TransactionType</th>
			<th width="100">DistributeExpense</th>
			<th width="100">ShipToCode</th>
			<th width="100">RowTotalFC</th>
			<th width="100">CFOPCode</th>
			<th width="100">CSTCode</th>
			<th width="100">Usage</th>
			<th width="100">TaxOnly</th>
			<th width="100">UnitPrice</th>
			<th width="100">LineStatus</th>
			<th width="100">LineType</th>
			<th width="100">COGSCostingCode</th>
			<th width="100">COGSAccountCode</th>
			<th width="100">ChangeAssemlyBoMWarehouse</th>
			<th width="100">GrossBuyPrice</th>
			<th width="100">GrossBase</th>
			<th width="100">GrossProfitTotalBasePrice</th>
			<th width="100">CostingCode2</th>
			<th width="100">CostingCode3</th>
			<th width="100">CostingCode4</th>
			<th width="100">CostingCode5</th>
			<th width="100">ItemDetails</th>
			<th width="100">LocationCode</th>
			<th width="100">ActualDeliveryDate</th>
			<th width="100">ExLineNo</th>
			<th width="100">RequiredDate</th>
			<th width="100">RequiredQuantity</th>
			<th width="100">COGSCostingCode2</th>
			<th width="100">COGSCostingCode3</th>
			<th width="100">COGSCostingCode4</th>
			<th width="100">COGSCostingCode5</th>
			<th width="100">CSTforIPI</th>
			<th width="100">CSTforPIS</th>
			<th width="100">CSTforCOFINS</th>
			<th width="100">CreditOriginCode</th>
			<th width="100">WithoutInventoryMovement</th>
			<th width="100">AgreementNo</th>
			<th width="100">AgreementRowNumber</th>
			<th width="100">ShipToDescription</th>
			<th width="100">ActualBaseEntry</th>
			<th width="100">ActualBaseLine</th>
			<th width="100">DocEntry</th>
			<th width="100">Surpluses</th>
			<th width="100">DefectAndBreakup</th>
			<th width="100">Shortages</th>
			<th width="100">ConsiderQuantity</th>
			<th width="100">PartialRetirement</th>
			<th width="100">RetirementQuantity</th>
			<th width="100">RetirementAPC</th>
			<th width="100">UoMEntry</th>
			<th width="100">InventoryQuantity</th>
			<th width="100">Incoterms</th>
			<th width="100">TransportMode</th>
			<th width="100">U_Supplier</th>
			<th width="100">U_TIN</th>
			<th width="100">U_PmtType</th>
			<th width="100">U_Maker</th>
			<th width="100">U_Variant</th>
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
			<td align="center" style="<?=$style;?>"><?=$cnt;?></td>
			<td align="center" style="<?=$style;?>"><?=$row['SAP_item_code'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['item_description'];?></td>
			<td align="center" style="<?=$style;?>"><?=$row['quantity'];?></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['price'],2);?></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>">P2</td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>">SUC</td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>">V4</td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>">PO1</td>
			<td align="center" style="<?=$style;?>">SUC</td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>">Z10</td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
			<td align="center" style="<?=$style;?>"></td>
		</tr>
		<? $cnt++; } ?>		
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="pdnreport" />
	</form>
	<? } ?>
</body>
</html>