<?
	class v_job_history{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_job.job,tbl_service_master.*
					FROM tbl_service_master
					JOIN tbl_vehicleinfo
						ON tbl_vehicleinfo.vehicle_id = tbl_service_master.vehicle_id
					JOIN tbl_customer
						ON tbl_customer.cust_id = tbl_service_master.customer_id
					JOIN tbl_service_detail
						ON tbl_service_detail.estimate_refno = tbl_service_master.estimate_refno
					JOIN tbl_job
						ON tbl_job.job_id = tbl_service_detail.id
				WHERE tbl_service_detail.type = 'job'
				$param";
			return $sql;
		}
	}
?>