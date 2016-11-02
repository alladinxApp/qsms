<?
	class v_accessory{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_accessory.accessory_id AS accessory_id,
					tbl_accessory.accessory      AS accessory,
					tbl_accessory.access_disc    AS access_disc,
					tbl_accessory.access_srp     AS access_srp,
					tbl_accessory.access_low     AS access_low,
					tbl_accessory.access_onhand  AS access_onhand,
					tbl_accessory.access_status  AS access_status,
					tbl_accessory.access_created AS access_created,
					tbl_accessory.item_code      AS item_code,
					tbl_accessory.access_used    AS access_used,
					tbl_accessory.modified_by    AS modified_by,
					tbl_accessory.modified_date  AS modified_date,
					(SELECT tbl_items.SAP_item_code AS SAP_item_code
						FROM tbl_items
						WHERE tbl_items.item_code = tbl_accessory.item_code) AS SAP_item_code
				FROM tbl_accessory 
				$param";
			return $sql;
		}
	}
?>