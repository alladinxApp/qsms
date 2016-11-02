<?
	class v_salesperunit{
		public function __construct(){}
		public function Query($param = false){
			$param = $param == false ? "tbl_service_master.trans_status IN('7','10') LIMIT 0,1" : "$param AND tbl_service_master.trans_status IN('7','10')";
			$sql = "SELECT tbl_vehicleinfo.plate_no AS plate_no,
						tbl_vehicleinfo.vehicle_id          AS vehicle_id,
						tbl_service_master.customer_id      AS customer_id,
						tbl_service_master.wo_refno         AS wo_refno,
						tbl_service_master.total_amount     AS total_amount,
						tbl_service_master.transaction_date AS transaction_date
					FROM tbl_service_master
					JOIN tbl_vehicleinfo ON tbl_service_master.vehicle_id = tbl_vehicleinfo.vehicle_id
				$param";
			return $sql;
		}
	}
?>