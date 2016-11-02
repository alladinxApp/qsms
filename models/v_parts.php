<?
	class v_parts{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_parts.parts_id AS parts_id,
					tbl_parts.parts           AS parts,
					tbl_parts.parts_discount  AS parts_discount,
					tbl_parts.part_srp        AS part_srp,
					tbl_parts.parts_lowstock  AS parts_lowstock,
					tbl_parts.part_onhand     AS part_onhand,
					tbl_parts.partstatus      AS partstatus,
					tbl_parts.part_created    AS part_created,
					tbl_parts.parts_old_price AS parts_old_price,
					tbl_parts.new_price_date  AS new_price_date,
					tbl_parts.old_price_date  AS old_price_date,
					tbl_parts.modified_by     AS modified_by,
					tbl_parts.modified_date   AS modified_date,
					tbl_parts.item_code       AS item_code,
					tbl_parts.parts_used      AS parts_used,
					(SELECT tbl_items.SAP_item_code AS SAP_item_code
						FROM tbl_items
						WHERE tbl_items.item_code = tbl_parts.item_code) AS SAP_item_code
					FROM tbl_parts
				$param";
			return $sql;
		}
	}
?>