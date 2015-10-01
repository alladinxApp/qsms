<?
	session_start();
	
	if(isset($_GET['estimaterefno']) || !empty($_GET['estimaterefno'])){
		$_SESSION['estimaterefno'] = $_GET['estimaterefno'];
		$url = 'print_workorder.php';
	}else{
		$url = 'workorder_main.php';
	}
	echo '<script>window.location="'.$url.'"</script>';
?>