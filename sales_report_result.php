<?php
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");

	if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){
		$totalsales = 0;
		$totallabor = 0;
		$totallubricants = 0;
		$totalsublet = 0;
		$totalparts = 0;
		$totaldiscount = 0;
		$ln = null;
		$where = "WHERE 1";
		$ptype = $_POST['txtptype'];
		$cust = $_POST['txtcust'];
		$from = dateFormat($_POST['txtdatefrom'],"Y-m-d") . " 00:00:00";
		$to = dateFormat($_POST['txtdateto'],"Y-m-d") . " 23:59:59";
		$dtfrom = date("Y-m-d") . " 00:00:00";
		$dtto = date("Y-m-d") . " 23:59:59";
		$dt = date("Ymdhis");

		if(empty($_POST['txtdatefrom']) && empty($_POST['txtdateto'])){
			$dtfrom = date("Y-m-d 00:00:00");
			$dtto = date("Y-m-d 23:59:59");
		}else if(!empty($_POST['txtdatefrom']) && empty($_POST['txtdateto'])){
			$dtfrom = $from;
			$dtto = $from;
		}else if(empty($_POST['txtdatefrom']) && !empty($_POST['txtdateto'])){
			$dtfrom = $to;
			$dtto = $to;
		}else if(!empty($_POST['txtdatefrom']) && !empty($_POST['txtdateto'])){
			$dtfrom = $from;
			$dtto = $to;
		}else{ }
		
		$where .= " AND billing_date BETWEEN '$dtfrom' AND '$dtto'";

		if(!empty($cust)){
			$qrycustomer = "SELECT * FROM v_customer WHERE cust_id = '$cust'";
			$rescustomer = $dbo->query($qrycustomer);

			foreach($rescustomer as $rowcustomer){
				$custname = $rowcustomer['custname'];
			}

			$where .= " AND customer_id = '$cust'";
		}

		if(!empty($ptype)){
			$qrypayment = "SELECT * FROM v_payment WHERE payment_id = '$ptype'";
			$respayment = $dbo->query($qrypayment);

			foreach($respayment as $rowpayment){
				$paymentmode = $rowpayment['payment'];
			}

			$where .= " AND payment_id = '$ptype'";
		}

		$sql_lbs_master = new v_sales;
		
		$qry_lbs_master = mysql_query($sql_lbs_master->Query($where));
		$qry_mst = mysql_query($sql_lbs_master);
		
		$ln .= "SALES SUMMARY REPORT\r\n\r\n";
		
		$ln .= "From: ," . $dtfrom . "\r\n";
		$ln .= "To: ," . $dtto . "\r\n";

		if(!empty($cust)){
			$ln .= "Customer: ," . $cust . "\r\n";
		}else{
			$ln .= "Customer: ,ALL\r\n";
		}
		
		if(!empty($ptype)){
			$ln .= "Payment Type: ," . $paymentmode . "\r\n";
		}else{
			$ln .= "Payment Type: ,ALL\r\n";
		}
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
	div.divEstimateList{ overflow: scroll; height: 400px; width: 800px; border-left: 1px solid #ddd; border-top: 1px solid #ddd; }
	div.divEstimateList table{ border: 1px solid #ccc; font-size: 12px; }
	div.divEstimateList table th{ border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #fff; background: #0000ff; }
</style>
<body>
	<p id="title">Sales Report Result</p>
	<? if(isset($_POST['export']) && !empty($_POST['export']) && $_POST['export'] == 1){ ?>
	<form method="Post" action="export.php" target="_blank">
	<input type="hidden" id="txtdatefrom" name="txtdatefrom" value="<?=$from;?>">
	<input type="hidden" id="txtdateto" name="txtdateto" value="<?=$to;?>">
	<input type="hidden" id="txtptype" name="txtptype" value="<?=$ptype;?>">
	<input type="hidden" id="txtcust" name="txtcust" value="<?=$cust;?>">
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
		<tr>
			<td >Customer </td>
			<td>:</td>
			<td ><? if(!empty($cust)){ echo $custname; }else{ echo 'ALL'; } ?></td>
		</tr>
		<tr>
			<td >Payment </td>
			<td>:</td>
			<td ><? if(!empty($ptype)){ echo $paymentmode; }else{ echo 'ALL'; } ?></td>
		</tr>
	</table>
	<div class="divEstimateList">
	<table width="1350">
		<tr>
			<th width="10">#</th>
			<th width="200">Customer Name</th>
			<th width="250">Service Availed</th>
			<th width="150">Payment Type</th>
			<th width="100">Labor</th>
			<th width="100">Lubricants</th>
			<th width="100">Sublet/Others</th>
			<th width="100">Spare Parts</th>
			<th width="100">VAT</th>
			<th width="100">Discount</th>
			<th width="100">Total</th>
		</tr>
		<?
			$cnt = 1; 
			while($row = mysql_fetch_array($qry_lbs_master)){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}

				$totallabor += $row['labor'];
				$totallubricants += $row['lubricants'];
				$totalsublet += $row['sublet'];
				$totalparts += $row['parts'];
				$totaldiscount += $row['discount'];
				$total = $row['total_amount'];
				
				$grandtotal = $row['labor'] + $row['lubricants'] + $row['sublet'] + $row['parts'];
				$discounted = ($grandtotal - $row['discount']);
				if($row['senior_citizen'] == 0){
					$vat = ($discounted * 0.12);
				}else{
					$vat = 0;
				}
				$totalamnt = (($grandtotal - $vat) - $row['discount']);
				
				$totalsales += $total;
				$totalvat += $vat;

				$style = $bg;

				$jobs = null;
				$sql_lbs_detail = new v_service_detail_job;
				$qry_lbs_detail = mysql_query($sql_lbs_detail->Query("WHERE estimate_refno = '$row[estimate_refno]'"));
				while($row_lbs_detail = mysql_fetch_array($qry_lbs_detail)){
					$job[] = $row_lbs_detail['job_name'];
				}

				for($i=0;$i<count($job);$i++){
					if($i > 0){
						$jobs .= ",<br />";
					}
					$jobs .= $job[$i];
				}
		?>
		<tr>
			<td><?=$cnt;?></td>
			<td style="<?=$style;?>"><?=$row['customername'];?></td>
			<td style="<?=$style;?>"><?=$jobs;?></td>
			<td style="<?=$style;?>"><?=$row['payment_mode'];?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['labor'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['lubricants'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['sublet'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['parts'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($vat,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($row['discount'],2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($total,2);?></td>
		</tr>
		<? $cnt++; } $style .= 'border-top: 3px double #000;';?>
		<tr>
			<td align="right" colspan="4" style="<?=$style;?> border: 0;">Total>>>>></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totallabor,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totallubricants,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalsublet,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalparts,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalvat,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totaldiscount,2);?></td>
			<td align="right" style="<?=$style;?>"><?=number_format($totalsales,2);?></td>
		</tr>
		<? 
			$total_units = ($cnt - 1);
			$ave_invoice_price = $totalsales / $total_units; 
		?>
		<tr>
			<td colspan="2">TOTAL UNITS RECEIVED</td>
			<td colspan="6">: <b><?=$total_units;?></b></td>
		</tr>
		
		<tr>
			<td colspan="2">AVERAGE INVOICE PRICE</td>
			<td colspan="6">: <b><?=number_format($ave_invoice_price,2);?></b></td>
		</tr>
	</table>
	</div>
	<input type="submit" name="save" value="" style="cursor: pointer;">
	<input type="hidden" id="export" name="export" value="1" />
	<input type="hidden" id="report" name="report" value="salesreport" />
	</form>
	<? } ?>
</body>
</html>