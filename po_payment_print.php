<?
	session_start();
	
	if(isset($_GET['porefno']) || !empty($_GET['porefno'])){
		$_SESSION['porefno'] = $_GET['porefno'];
		$url = 'print_po_payment.php';
	}else{
		$url = 'po_main.php';
	}
	echo '<script>window.location="'.$url.'"</script>';
?>