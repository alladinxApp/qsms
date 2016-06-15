<?
	session_start();
	
	if(isset($_GET['rrrefno']) || !empty($_GET['rrrefno'])){
		$_SESSION['rrrefno'] = $_GET['rrrefno'];
		$url = 'print_rr.php';
	}else{
		$url = 'po_main.php';
	}
	echo '<script>window.location="'.$url.'"</script>';
?>