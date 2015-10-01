<?
	session_start();
	
	if(isset($_GET['billingrefno']) || !empty($_GET['billingrefno'])){
		$_SESSION['billingrefno'] = $_GET['billingrefno'];
		$url = 'print_billing.php';
	}else{
		$url = 'billing_main.php';
	}
	echo '<script>window.location="'.$url.'"</script>';
?>