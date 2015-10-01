<?
	session_start();

	session_unset();
	session_destroy();
	
	echo '<script>window.location = "main.php"</script>';
	exit();
?>