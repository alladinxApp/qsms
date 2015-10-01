<?
	session_start();
	$ses_id = session_id();
	require_once("conf/db_connection.php");
	require_once("functions.php");
	
	$type = $_GET['type'];
	$rowtype = array();
	switch($type){
		case "make": 			
			$qry = "SELECT * FROM v_make";
			$res = $dbo->query($qry);
			foreach($res as $row){
				$restype[] = array("id" => $row['make_id'],
								"desc" => $row['make']);
			}
			break;
		case "job": 
			$qry = "SELECT * FROM v_job";
			$res = $dbo->query($qry);
			foreach($res as $row){
				$restype[] = array("id" => $row['job_id'],
								"desc" => $row['job']);
			}
			break;
		case "parts": 
			$qry = "SELECT * FROM v_parts";
			$res = $dbo->query($qry);
			foreach($res as $row){
				$restype[] = array("id" => $row['parts_id'],
								"desc" => $row['parts']);
			}
			break;
		case "material":
			$qry = "SELECT * FROM v_material";
			$res = $dbo->query($qry);
			foreach($res as $row){
				$restype[] = array("id" => $row['material_id'],
								"desc" => $row['material']);
			}
			break;
		case "accessory":
			$qry = "SELECT * FROM v_accessory";
			$res = $dbo->query($qry);
			foreach($res as $row){
				$restype[] = array("id" => $row['accessory_id'],
								"desc" => $row['accessory']);
			}
			break;
		default: break;
	}
?>
<select name="typeid" id="typeid" >
	<? foreach($restype as $rowtype){ ?>
	<option value="<?=$rowtype['id'];?>"><?=$rowtype['desc'];?></option>
	<? } ?>
	<option value="others">Others</option>
</select>