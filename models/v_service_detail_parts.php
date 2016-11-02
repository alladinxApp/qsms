<?
	class v_service_detail_parts{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_service_detail.estimate_refno AS estimate_refno,
						tbl_service_detail.type           AS type,
						tbl_service_detail.id             AS id,
						tbl_service_detail.qty            AS qty,
						tbl_parts.parts 				  AS parts_name,
						tbl_parts.item_code		  		  AS item_code,
						tbl_parts.part_onhand	  		  AS parts_onhand,
						tbl_parts.parts_used		  	  AS parts_used,
						tbl_service_detail.amount         AS amount
					FROM tbl_service_detail
					JOIN tbl_parts ON tbl_parts.parts_id = tbl_service_detail.id
						AND tbl_service_detail.type = 'parts'
				$param";
			return $sql;
		}
	}
?>