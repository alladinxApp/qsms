<?
	require_once("conf/db_connection.php");
	
	$model_id = $_GET['modelid'];
	$qrymdl = "SELECT * FROM v_model WHERE model_id = '$model_id'";
	$resmdl = $dbo->query($qrymdl);
	foreach($resmdl as $row){
		$variant = $row['variant'];
	}
?>
<input type="text" name="variant" id="variant" readonly value="<?=$variant;?>" style="width:200px" id="">