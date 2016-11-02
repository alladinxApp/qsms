<?
	class v_service_detail_material{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_service_detail.estimate_refno AS estimate_refno,
					tbl_service_detail.type           AS type,
					tbl_service_detail.id             AS id,
					tbl_service_detail.qty            AS qty,
					tbl_material.material 			  AS material_name,
					tbl_service_detail.amount         AS amount
				FROM tbl_service_detail
				JOIN tbl_material ON tbl_material.material_id = tbl_service_detail.id
					AND tbl_service_detail.type = 'material'
				$param";
			return $sql;
		}
	}
?>