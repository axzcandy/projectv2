<?php
	include('sqlconnstorage.php');

	if(isset($_SESSION['username'])){
		$myusername = $_SESSION['username'];
		
		$sql = "SELECT Statue as st FROM employeeidpass WHERE EUser = '$myusername' or Email = '$myusername'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$st = $row['st'];
		
		
		if($st == 'new'){
			// checkCustomerLoginValid()==true
			$sql = "SELECT * FROM employeeidpass WHERE EUser = '$myusername' or Email = '$myusername'";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$tc = $row['TempCode'];
			$email = $row['Email'];
			
			$subject="Email verification code";
			$message="Your verify code is = ".$tc;
			$headers="Verify Code";
						
				if(mail($email,$subject,$message,$headers)==true){
					//echo "mail send";
					//echo 'suscess all';
					//**redirect page??
					header("Refresh: 1;URL= /projectv2/employee/html/emverifyemail.html");
				}else{
					//echo "mail failed";
				}
			
		}else{
			echo "Please login!!";
			header("Refresh: 1;URL= /projectv2/employee/html/elogin.html");
		}
	}else{
		echo "Please login!!";
		header("Refresh: 1;URL= /projectv2/employee/html/elogin.html");
	}
?>