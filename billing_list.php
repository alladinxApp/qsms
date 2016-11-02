<?
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$qrybillingmst = new v_for_billing_master;
	$resbillingmst = $dbo->query($qrybillingmst->Query());
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
	
	div.estimate_approval{ clear: both; }
	div.divEstimateList a,
	div.divEstimateList a:link,
	div.divEstimateList a:visited,
	div.divEstimateList a:active
		{ color: #000; text-decoration: none; }
	div.divEstimateList a:hover{ color: #ccc; text-decoration: none; }
	
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
	<? require_once('billing_menu.php'); ?>
	<fieldset form="form_estimate_list" name="form_estimate_list">
	<p id="title">Work Order LIST for Billing</p>
	<div class="divEstimateList">
	<table width="600">
		<tr>
			<th width="10">#</th>
			<th width="200">Customer ID</th>
			<th width="200">Customer Name</th>
			<th width="190">Total Billings</th>
		</tr>
		<?
			$cnt = 1;
			foreach($resbillingmst as $rowBILLINGMST){
				if($cnt%2){
					$bg = "background: none;";
				}else{
					$bg = "background: #eee;";
				}
		?>
		<tr>
			<td align="center" style="<?=$bg . $color;?>"><?=$cnt;?></td>
			<td><a href="billing_details.php?custid=<?=$rowBILLINGMST['cust_id'];?>&type=0"><?=$rowBILLINGMST['cust_id'];?></a></td>
			<td><?=$rowBILLINGMST['custname'];?></td>
			<td><?=number_format($rowBILLINGMST['total_billings'],2);?></td>
		</tr>
		<? $cnt++; } ?>
	</table>
	</div>
	</fieldset>
</body>
</html>
