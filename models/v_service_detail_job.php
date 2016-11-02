<?
	class v_service_detail_job{
		public function __construct(){}
		public function Query($param = false){
			$sql = "SELECT tbl_service_detail.estimate_refno AS estimate_refno,
					tbl_service_detail.type           AS TYPE,
					tbl_service_detail.id             AS id,
					tbl_service_detail.qty            AS qty,
					tbl_job.job                       AS job_name,
					tbl_service_detail.amount         AS amount,
					tbl_job.flagrate                  AS flagrate,
					tbl_job.stdhr                     AS stdworkinghr
				FROM tbl_service_detail
					JOIN tbl_job ON tbl_service_detail.id = tbl_job.job_id
						AND tbl_service_detail.type = 'job'
				$param";
			return $sql;
		}
	}
?>