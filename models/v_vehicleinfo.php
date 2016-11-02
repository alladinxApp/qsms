<?
	class v_vehicleinfo{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_vehicleinfo.vehicle_id AS vehicle_id,
				tbl_vehicleinfo.customer_id        AS customer_id,
				(SELECT
					CONCAT(tbl_customer.firstname,' ',tbl_customer.middlename,' ',tbl_customer.lastname) AS custname
					FROM tbl_customer
					WHERE tbl_customer.cust_id = tbl_vehicleinfo.customer_id) AS customername,
				tbl_vehicleinfo.address            AS address,
				tbl_vehicleinfo.plate_no           AS plate_no,
				tbl_vehicleinfo.conduction_sticker AS conduction_sticker,
				tbl_vehicleinfo.make               AS make,
				tbl_make.make 					   AS make_desc,
				tbl_vehicleinfo.year               AS YEAR,
				tbl_year.year 					   AS year_desc,
				tbl_vehicleinfo.model              AS model,
				(SELECT tbl_model.model FROM tbl_model WHERE tbl_model.model_id = tbl_vehicleinfo.model) AS model_desc,
				tbl_vehicleinfo.color              AS color,
				(SELECT tbl_color.color FROM tbl_color WHERE tbl_color.color_id = tbl_vehicleinfo.color) AS color_desc,
				tbl_vehicleinfo.variant            AS variant,
				tbl_vehicleinfo.description        AS description,
				tbl_vehicleinfo.engine_no          AS engine_no,
				tbl_vehicleinfo.chassis_no         AS chassis_no,
				tbl_vehicleinfo.serial_no          AS serial_no
				FROM tbl_vehicleinfo
					JOIN tbl_make ON tbl_make.make_id = tbl_vehicleinfo.make
					JOIN tbl_year ON tbl_year.year_id = tbl_vehicleinfo.year
				$param";
			return $sql;
		}
	}
?>