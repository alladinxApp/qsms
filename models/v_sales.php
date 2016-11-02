<?
	class v_sales{
		public function __construct(){}
		public function Query($param = false){
			$param = $param == false ? "tbl_service_master.trans_status IN('7','10') LIMIT 0,1" : "$param AND tbl_service_master.trans_status IN('7','10')";
			$sql = "SELECT tbl_service_master.estimate_refno AS estimate_refno,
						tbl_service_master.transaction_date AS transaction_date,
						tbl_service_master.customer_id      AS customer_id,
						CONCAT(tbl_customer.firstname,' ',tbl_customer.middlename,' ',tbl_customer.lastname) AS customername,
						tbl_service_master.payment_id       AS payment_id,
						tbl_payment.payment                 AS payment_mode,
						tbl_service_master.total_amount AS total_amount,
						tbl_service_master.discount AS discount,
						tbl_billing.billing_refno AS billing_refno,
						tbl_billing.billing_date AS billing_date,
						(SELECT SUM(tbl_service_detail.amount)
							FROM tbl_service_detail
							WHERE tbl_service_detail.estimate_refno = tbl_service_master.estimate_refno
								AND tbl_service_detail.type = 'job') AS labor,
						(SELECT SUM(tbl_service_detail.amount)
							FROM tbl_service_detail
							WHERE tbl_service_detail.estimate_refno = tbl_service_master.estimate_refno
								AND tbl_service_detail.type = 'accessory') AS lubricants,
						(SELECT SUM(tbl_service_detail.amount)
							FROM tbl_service_detail
							WHERE tbl_service_detail.estimate_refno = tbl_service_master.estimate_refno
								AND tbl_service_detail.type = 'material') AS sublet,
						(SELECT SUM(tbl_service_detail.amount)
							FROM tbl_service_detail
							WHERE tbl_service_detail.estimate_refno = tbl_service_master.estimate_refno
								AND tbl_service_detail.type = 'parts') AS parts_id
					FROM tbl_service_master
					JOIN tbl_customer ON tbl_customer.cust_id = tbl_service_master.customer_id
					JOIN tbl_billing ON tbl_billing.wo_refno = tbl_service_master.wo_refno
					JOIN tbl_payment ON tbl_payment.payment_id = tbl_service_master.payment_id
				$param";
			return $sql;
		}
	}
?>