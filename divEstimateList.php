<fieldset form="form_estimate_list" name="form_estimate_list">
<p id="title">Estimate LIST</p>
<div class="divEstimateList">
<table width="2200">
	<tr>
		<th width="10"></th>
		<th >#</th>
		<th width="150">Estimate Reference No</th>
		<th width="160">Work Order Reference No</th>
		<th width="150">Transaction Date</th>
		<th width="200">Customer Name</th>
		<th width="100">Plate No</th>
		<th width="100">Make</th>
		<th width="50">Year</th>
		<th width="100">Model</th>
		<th width="100">Color</th>
		<th width="200">Variant</th>
		<th width="200">Engine No</th>
		<th width="200">Chassis No</th>
		<th width="200">Payment Mode</th>
		<th width="100">Total Amount</th>
		<th width="100">Status</th>
	</tr>
	<? $cnt = 1; foreach($resservices as $rowservices){ ?>
	<tr>
		<td align="center"><input type="checkbox" name="chkestimateno" id="chkestimateno" value="<?=$rowservices['estimate_refno'];?>"></td>
		<td align="center"><?=$cnt;?></td>
		<td><?=$rowservices['estimate_refno'];?></td>
		<td><?=$rowservices['wo_refno'] . '&nbsp;';?></td>
		<td align="center"><?=$rowservices['transaction_date'];?></td>
		<td><?=$rowservices['customername'];?></td>
		<td><?=$rowservices['plate_no'];?></td>
		<td align="center"><?=$rowservices['make_desc'];?></td>
		<td align="center"><?=$rowservices['year_desc'];?></td>
		<td align="center"><?=$rowservices['model_desc'];?></td>
		<td align="center"><?=$rowservices['color_desc'];?></td>
		<td><?=$rowservices['variant'];?></td>
		<td><?=$rowservices['engine_no'];?></td>
		<td><?=$rowservices['chassis_no'];?></td>
		<td><?=$rowservices['payment_mode'];?></td>
		<td align="right"><?=number_format($rowservices['total_amount'],2);?></td>
		<td><?=$rowservices['status_desc'];?></td>
	</tr>
	<? $cnt++; } ?>
</table>
</div>
</fieldset>