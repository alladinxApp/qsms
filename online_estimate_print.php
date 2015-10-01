<?
	session_start();
	
	if(isset($_GET['oe_id']) || !empty($_GET['oe_id'])){
		$_SESSION['oe_id'] = $_GET['oe_id'];
		$url = 'print_online_estimate.php';
	}else{
		$url = 'estimates_main.php';
	}
	echo '<script>window.location="' . $url . '"</script>';
?>