 <?php
	if (isset($_POST['update'])){
		
		$cust_id=$_POST['cust_id'];
		$salutation=$_POST['salutation'];
		$lastname=$_POST['lastname'];
		$firstname=$_POST['firstname'];
		$middlename=$_POST['middlename'];
		$address=$_POST['address'];
		$city=$_POST['city'];
		$province=$_POST['province'];
		$zipcode=$_POST['zipcode'];
		$birthday=$_POST['birthday'];
		$gender=$_POST['gender'];
		$tin=$_POST['tin'];
		$company=$_POST['company'];
		$source=$_POST['source'];
		$email=$_POST['email'];
		$landline=$_POST['landline'];
		$fax=$_POST['fax'];
		$mobile=$_POST['mobile'];
					   
		$customer_update = "UPDATE tbl_customer SET 
			salutation = '$salutation', 
			lastname = '$lastname', 
			firstname = '$firstname', 
			middlename = '$middlename', 
			address = '$address', 
			city = '$city', 
			province = '$province', 
			zipcode = '$zipcode', 
			birthday = '$birthday', 
			gender = '$gender', 
			tin = '$tin', 
			company = '$company', 
			source = '$source', 
			email = '$email', 
			landline = '$landline', 
			fax = '$fax', 
			mobile = '$mobile'
		WHERE cust_id = '$cust_id'";
		
		$res = mysql_query($customer_update) or die("UPDATE CUSTOMER ".mysql_error());
	}
?>