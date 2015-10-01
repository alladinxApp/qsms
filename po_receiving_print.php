<?
	session_start();
	
	if(isset($_GET['estimaterefno']) || !empty($_GET['estimaterefno'])){
		$_SESSION['estimaterefno'] = $_GET['estimaterefno'];
		$url = 'print_po_receiving.php';
	}else{
		$url = 'po_receiving_menu.php';
	}
	echo '<script>window.location="'.$url.'"</script>';
?>