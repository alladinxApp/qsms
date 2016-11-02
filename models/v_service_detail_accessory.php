<?
	class v_service_detail_accessory{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_service_detail.estimate_refno AS estimate_refno,
						tbl_service_detail.type           AS type,
						tbl_service_detail.id             AS id,
						tbl_service_detail.qty            AS qty,
						tbl_accessory.accessory 		  AS accessory_name,
						tbl_accessory.item_code			  AS item_code,
						tbl_accessory.access_onhand		  AS access_onhand,
						tbl_accessory.access_used		  AS access_used,
						tbl_service_detail.amount         AS amount
					FROM tbl_service_detail
					JOIN tbl_accessory ON tbl_accessory.accessory_id = tbl_service_detail.id
						AND tbl_service_detail.type = 'accessory'
				$param";
			return $sql;
		}
	}
?>