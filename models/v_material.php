<?
	class v_material{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_material.material_id AS material_id,
					tbl_material.material          AS material,
					tbl_material.material_disc     AS material_disc,
					tbl_material.material_srp      AS material_srp,
					tbl_material.material_lowstock AS material_lowstock,
					tbl_material.material_onhand   AS material_onhand,
					tbl_material.material_status   AS material_status,
					tbl_material.material_created  AS material_created,
					tbl_material.material_used     AS material_used,
					tbl_material.item_code         AS item_code,
					tbl_material.modified_by       AS modified_by,
					tbl_material.modified_date     AS modified_date,
					(SELECT tbl_items.SAP_item_code AS SAP_item_code
						FROM tbl_items
						WHERE tbl_items.item_code = tbl_material.item_code) AS SAP_item_code
				FROM tbl_material
				$param";
			return $sql;
		}
	}
?>