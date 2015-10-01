<?php
	require_once("conf/db_connection.php");

	if(!isset($_SESSION)){
		session_start();
	}

	$pac_id = $_GET['pacid'];
	$qry = "SELECT * FROM tbl_package_master WHERE package_id = '$pac_id'";
	$result = $dbo->query($qry);

	$qrydtljob = "SELECT v_package_detail.*,v_job.job 
					FROM v_package_detail 
					INNER JOIN v_job ON v_job.job_id = v_package_detail.id
					WHERE type = 'job' AND package_id = '$pac_id'";
	$resdtljob = $dbo->query($qrydtljob);

	$qrydtlparts = "SELECT v_package_detail.*,v_parts.parts 
					FROM v_package_detail 
					INNER JOIN v_parts ON v_parts.parts_id = v_package_detail.id
					WHERE type = 'parts' AND package_id = '$pac_id'";
	$resdtlparts = $dbo->query($qrydtlparts);

	$qrydtlmaterial = "SELECT v_package_detail.*,v_material.material 
					FROM v_package_detail 
					INNER JOIN v_material ON v_material.material_id = v_package_detail.id
					WHERE type = 'material' AND package_id = '$pac_id'";
	$resdtlmaterial = $dbo->query($qrydtlmaterial);

	$qrydtlaccessory = "SELECT v_package_detail.*,v_accessory.accessory 
					FROM v_package_detail 
					INNER JOIN v_accessory ON v_accessory.accessory_id = v_package_detail.id
					WHERE type = 'accessory' AND package_id = '$pac_id'";
	$resdtlaccessory = $dbo->query($qrydtlaccessory);

	$qryjob = "SELECT * FROM v_job ";
	$resjob = $dbo->query($qryjob);
	
	$qryparts = "SELECT * FROM v_parts ";
	$resparts = $dbo->query($qryparts);
	
	$qrymaterial = "SELECT * FROM v_material ";
	$resmaterial = $dbo->query($qrymaterial);
	
	$qryaccessory = "SELECT * FROM v_accessory ";
	$resaccessory = $dbo->query($qryaccessory);
	
	foreach($result as $row){
		$pac_id = $row['package_id'];
		$package = $row['package_name'];
		$status = $row['status'];
		switch($status){
			case "0": $status_desc = "INACTIVE"; break;
			case "1": $status_desc = "ACTIVE"; break;
			default: break;
		}
	}
	
	if(isset($_GET['act']) && !empty($_GET['act'])){
		switch($_GET['act']){
			case "add": 
					$pacid = $_GET['pacid'];
					$type = $_GET['type'];
					$id = $_GET['id'];

					$qry = "INSERT INTO tbl_package_detail(package_id,type,id)
							VALUES('$pacid','$type','$id')";
					mysql_query($qry);
					echo '<script>alert("Package profile successfully updated.");</script>';
					echo '<script>window.location="package_detail.php?pacid='.$pac_id.'";</script>';
				break;
			case "del": 
					$pacid = $_GET['pacid'];
					$type = $_GET['type'];
					$id = $_GET['id'];

					$qry = "DELETE FROM tbl_package_detail
							WHERE package_id = '$pacid' AND type = '$type' AND id = '$id'";
					mysql_query($qry);
					echo '<script>alert("Package profile successfully deleted.");</script>';
					echo '<script>window.location="package_detail.php?pacid='.$pac_id.'";</script>';
				break;
			default: break;
		}
	}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style/cal.css" />
<link rel="stylesheet" type="text/css" href="style/forms.css" />  
<script>
	function AddCost(pacid,type,id){
		var url = "package_detail.php?pacid="+pacid+"&act=add&type="+type+"&id="+id;
		window.location = url;
	}
	function RemoveCost(pacid,type,id){
		var url = "package_detail.php?pacid="+pacid+"&act=del&type="+type+"&id="+id;
		window.location = url;
	}
</script>
<style type="text/css">
	div.divTableEstimateCost table{ border: 1px solid #ddd; padding: 0; margin: 0; }
	div.divTableEstimateCost table th{ background: #0000ff; color: #fff; font-size: 12px; }
</style>
</head>
<body>
	<fieldset form="form_employee" name="form_employee">
	<legend><p id="title">Package Masterfile</p></legend>
	<table>
		<tr>
			<td class ="label"><label name="lbl_emp_id">Package Code:</label>
			<td class ="input"><input type="text" name="pac_id" value="<?=$pac_id;?>" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_employee">Package Name:</label>
			<td class ="input"><input type="text" name="package" id="package" value="<?=$package;?>" readonly style="width:272px"></td>
		</tr>
		<tr>
			<td class ="label"><label name="lbl_emp_status">Status:</label>
			<td class ="input"><input type="text" name="status" id="status" value="<?=$status_desc;?>" readonly style="width:272px"></td>
		</tr>
	</table>
	</fieldset>
	<fieldset form="form_costestimate" name="form_costestimate">
	<legend><p id="title">ESTIMATE COST:</p></legend>
	<div class="divTableEstimateCost">
    <table width="750" border="0">
		<tr>
			<th><div align="center"><span class="">Job Cost</span></div></th>
			<th><div align="center"><span class="">Parts Cost</span></div></th>
			<th><div align="center"><span class="">Material Cost</span></div></th>
			<th><div align="center"><span class="">Accessory Cost</span></div></th>
		</tr>
		<tr>
			<td valign="top"><div align="center" style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
				<? foreach($resdtljob as $rowdtljob){ ?>
					<?=$rowdtljob['job'];?>&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('<?=$rowdtljob['package_id'];?>','<?=$rowdtljob['type'];?>','<?=$rowdtljob['id'];?>');"><img src="images/del_ico.png" width="15"></a>
					</div>
					<div align="center" style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
				<? } ?>
			</div></td>
			<td valign="top"><div align="center" style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
				<? 
					foreach($resdtlparts as $rowdtlparts){
						echo $rowdtlparts['parts'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $pacid .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">';
					}
				?>
			</div></td>
			<td valign="top"><div align="center" style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
				<? 
					foreach($resdtlmaterial as $rowdtlmaterial){
						echo $rowdtlmaterial['material'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $pacid .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">';
					}
				?>
			</div></td>
			<td valign="top"><div align="center" style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
				<? 
					foreach($resdtlaccessory as $rowdtlaccessory){
						echo $rowdtlaccessory['accessory'] . '&nbsp;&nbsp;&nbsp;<a href="#" onclick="RemoveCost('. $pacid .');"><img src="images/del_ico.png" width="15"></a></div><div align="center" style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">';
					}
				?>
			</div></td>
		</tr>
		<tr>
			<td><div align="center"><select name="job" id="job" style="width: 120px;" onchange="return AddCost('<?=$pac_id;?>','job',this.value);">
				<option value=""></option>
				<? foreach($resjob as $rowjob){ ?>
				<option value="<?=$rowjob['job_id'];?>"><?=$rowjob['job'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="parts" id="parts" style="width: 120px;" onchange="return AddCost('<?=$pac_id;?>','parts',this.value);">
				<option value=""></option>
				<? foreach($resparts as $rowparts){ ?>
				<option value="<?=$rowparts['parts_id'];?>"><?=$rowparts['parts'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="material" id="material" style="width: 120px;" onchange="return AddCost('<?=$pac_id;?>','material',this.value);">
				<option value=""></option>
				<? foreach($resmaterial as $rowmaterial){ ?>
				<option value="<?=$rowmaterial['material_id'];?>"><?=$rowmaterial['material'];?></option>
				<? } ?>
			</select></div></td>
			<td><div align="center"><select name="accessory" id="accessory" style="width: 120px;" onchange="return AddCost('<?=$pac_id;?>','accessory',this.value);">
				<option value=""></option>
				<? foreach($resaccessory as $rowaccessory){ ?>
				<option value="<?=$rowaccessory['accessory_id'];?>"><?=$rowaccessory['accessory'];?></option>
				<? } ?>
			</select></div></td>
		</tr>
    </table>
	</div>
	</fieldset>
</body>
</html>