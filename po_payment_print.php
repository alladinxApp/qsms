<?
	session_start();
	
	if(isset($_GET['rrrefno']) || !empty($_GET['rrrefno'])){
		$_SESSION['rrrefno'] = $_GET['rrrefno'];
		$url = 'print_po_payment.php';
	}else{
		$url = 'po_main.php';
	}
	echo '<script>window.location="'.$url.'"</script>';
?>