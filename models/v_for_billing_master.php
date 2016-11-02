<?
	class v_for_billing_master{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT v_customer.cust_id  AS cust_id,
						v_customer.custname AS custname,
						(SELECT COUNT(estimate_refno)
							FROM tbl_service_master
							WHERE (tbl_service_master.customer_id = v_customer.cust_id)
								AND (tbl_service_master.trans_status = '5')
								AND ((tbl_service_master.payment_id = _latin1'PAY00000001')
								     or (tbl_service_master.payment_id = _latin1'PAY00000002'))
							) AS transaction_counts,
						(SELECT SUM(tbl_service_master.total_amount) AS total_amount
							FROM tbl_service_master
							WHERE (tbl_service_master.customer_id = v_customer.cust_id)
								AND (tbl_service_master.trans_status = '5')
								AND ((tbl_service_master.payment_id = _latin1'PAY00000001')
								      or (tbl_service_master.payment_id = _latin1'PAY00000002'))
							) AS total_billings
					FROM v_customer
					WHERE (SELECT COUNT(estimate_refno)
								FROM tbl_service_master
								WHERE (tbl_service_master.customer_id = v_customer.cust_id)
									AND (tbl_service_master.trans_status = '5')
									AND ((tbl_service_master.payment_id = _latin1'PAY00000001')
									    or (tbl_service_master.payment_id = _latin1'PAY00000002'))
								) > 0
				$param";
			return $sql;
		}
	}
?>