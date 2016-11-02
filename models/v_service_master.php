<?
	class v_service_master{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_service_master.estimate_refno AS estimate_refno,
					tbl_service_master.wo_refno           AS wo_refno,
					tbl_service_master.po_refno           AS po_refno,
					tbl_service_master.transaction_date   AS transaction_date,
					tbl_service_master.customer_id        AS customer_id,
					CONCAT(tbl_customer.firstname,' ',tbl_customer.middlename,' ',tbl_customer.lastname) AS customername,
					CONCAT(tbl_customer.address,', ',tbl_customer.city,', ',tbl_customer.province) AS cust_address,
					tbl_customer.landline                 AS landline,
					tbl_customer.fax                      AS fax,
					tbl_customer.mobile                   AS mobile,
					tbl_service_master.vehicle_id         AS vehicle_id,
					tbl_service_master.odometer           AS odometer,
					tbl_service_master.wo_trans_date      AS wo_trans_date,
					(SELECT tbl_make.make FROM tbl_make WHERE tbl_make.make_id = tbl_vehicleinfo.make) AS make_desc,
					(SELECT tbl_year.year FROM tbl_year WHERE tbl_year.year_id = tbl_vehicleinfo.year) AS year_desc,
					(SELECT tbl_model.model FROM tbl_model WHERE tbl_model.model_id = tbl_vehicleinfo.model) AS model_desc,
					(SELECT tbl_color.color FROM tbl_color WHERE tbl_color.color_id = tbl_vehicleinfo.color) AS color_desc,
					tbl_vehicleinfo.plate_no              AS plate_no,
					tbl_vehicleinfo.conduction_sticker    AS conduction_sticker,
					tbl_vehicleinfo.variant               AS variant,
					tbl_vehicleinfo.engine_no             AS engine_no,
					tbl_vehicleinfo.chassis_no            AS chassis_no,
					tbl_vehicleinfo.serial_no             AS serial_no,
					tbl_service_master.payment_id         AS payment_id,
					tbl_service_master.promise_time       AS promise_time,
					tbl_service_master.promise_date       AS promise_date,
					CONCAT(tbl_service_master.promise_date,' ',tbl_service_master.promise_time) AS committed_date,
					(SELECT tbl_payment.payment AS payment
						FROM tbl_payment
						WHERE tbl_payment.payment_id = tbl_service_master.payment_id) AS payment_mode,
					tbl_service_master.subtotal_amount    AS subtotal_amount,
					tbl_service_master.discount           AS discount,
					tbl_service_master.labor_discount     AS labor_discount,
					tbl_service_master.parts_discount     AS parts_discount,
					tbl_service_master.lubricant_discount AS lubricant_discount,
					tbl_service_master.material_discount  AS material_discount,
					tbl_service_master.senior_citizen     AS senior_citizen,
					tbl_service_master.senior_citizen_no  AS senior_citizen_no,
					tbl_service_master.discounted_price   AS discounted_price,
					tbl_service_master.vat                AS vat,
					tbl_service_master.total_amount       AS total_amount,
					tbl_service_master.remarks            AS remarks,
					tbl_service_master.recommendation     AS recommendation,
					tbl_service_master.created_by         AS created_by,
					tbl_service_master.trans_status       AS trans_status,
					tbl_service_master.technician         AS emp_id,
					MONTH(tbl_service_master.transaction_date) AS trans_month,
					YEAR(tbl_service_master.transaction_date) AS trans_year,
					(SELECT tbl_employee.employee AS employee
						FROM tbl_employee
						WHERE tbl_employee.emp_id = tbl_service_master.technician) AS tech_name,
					(CASE WHEN tbl_service_master.trans_status = '0' THEN 'PENDING' 
						WHEN tbl_service_master.trans_status = '1' THEN 'APPROVED' 
						WHEN tbl_service_master.trans_status = '2' THEN 'DISAPPROVED' 
						WHEN tbl_service_master.trans_status = '3' THEN 'CANCELLED' 
						WHEN tbl_service_master.trans_status = '4' THEN 'FOR REPAIR' 
						WHEN tbl_service_master.trans_status = '5' THEN 'FINISHED' 
						WHEN tbl_service_master.trans_status = '6' THEN 'FOR BILLING' 
						WHEN tbl_service_master.trans_status = '7' THEN 'BILLED' 
						WHEN tbl_service_master.trans_status = '8' THEN 'ON-GOING' 
						WHEN tbl_service_master.trans_status = '9' THEN 'FOR APPROVAL' 
						WHEN tbl_service_master.trans_status = '10' THEN 'CLOSED' END) AS status_desc
				FROM tbl_service_master
					JOIN tbl_vehicleinfo
						ON tbl_vehicleinfo.vehicle_id = tbl_service_master.vehicle_id
					JOIN tbl_customer
						ON tbl_customer.cust_id = tbl_service_master.customer_id 
				$param";
			// echo $sql;
			$this->setSQL($sql);
			return $sql;
		}
		private function setSQL($slq){
			$this->sql = $sql;
		}
		public function getSQL(){
			return $this->sql;
		}
	}
?>