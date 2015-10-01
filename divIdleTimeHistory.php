<?
	session_start();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$today = date("Y-m-d");
	$type = $_GET['type'];
	$start = $today . ' ' . $_GET['start'] . date(":s");
	$end = $today . ' ' . $_GET['end'] . date(":s");
	$remarks = $_GET['remarks'];
	$worefno = $_GET['worefno'];
	
	$chkqryidletime = "SELECT * FROM v_jobclock_detail WHERE wo_refno = '$worefno'";
	$chkresidletime = mysql_query($chkqryidletime);
	$numidletime = mysql_num_rows($chkresidletime);
	$seqno = $numidletime + 1;
	
	$qryinsertidletime = "INSERT INTO tbl_jobclock_detail(wo_refno,seqno,idle_id,time_start,time_end,remarks)
					VALUES('$worefno','$seqno','$type','$start','$end','$remarks');";
	$resinsertidletime = $dbo->query($qryinsertidletime);
	
	$qryidletime = "SELECT * FROM v_jobclock_detail WHERE wo_refno = '$worefno'";
	$residletime = $dbo->query($qryidletime);
?>
<table>
	<tr>
		<th width="20">#</th>
		<th width="100">IDLE TYPE</th>
		<th width="100">START TIME</th>
		<th width="100">END TIME</th>
		<th width="100">TOTAL HOURS</th>
		<th width="300">REMARKS</th>
	</tr>
	<? foreach($residletime as $rowIDLETIME){ $totalhrs = 0; ?>
	<tr>
		<td><?=$rowIDLETIME['seqno'];?></td>
		<td><?=$rowIDLETIME['idle_name'];?></td>
		<td align="center"><?=dateFormat($rowIDLETIME['time_start'],"h:i");?></td>
		<td align="center"><?=dateFormat($rowIDLETIME['time_end'],"h:i");?></td>
		<?
			$difference = TimeDiff($rowIDLETIME['time_start'],$rowIDLETIME['time_end']) / 60 . " minutes";
		?>
		<td align="right"><?=$difference;?></td>
		<td><?=$rowIDLETIME['remarks'];?></td>
	</tr>
	<? } ?>
	<tr>
		<td colspan="4" align="right">TOTAL IDLE TIME:</td>
		<td colspan="2" align="left">&nbsp;</td>
	</tr>
</table>