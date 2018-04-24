<?php
	include('sqlconnstorage.php');
	
	if(checkCustomerLoginValid()==true){
		// checkCustomerLoginValid()==true
		
		$email = $_SESSION['email'];
		
		$sql = "select Statue as st,TempCode as tp from customeridpass where Email = '$email'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$stat = $row['st'];
		$tempcode = $row['tp'];
		if($stat=='new'){
			$subject="Email verification code";
			
			$tempcodeforemail=$tempcode;

			$message="Your verify code is = ".$tempcodeforemail."\n";
			$headers="Verify Code";
						
			if(mail($email,$subject,$message,$headers)==true){
				echo $email.$subject.$message.$headers.$stat;
				echo "Send...Redirecting";
				header("Refresh: 3;URL= /projectv2/customer/html/cverifyemail.html");
			}else{
				// echo 'failed0';
				echo "'ERROR:mailsenderror' Please contact support!";
			}
		}else if($stat=='locked'){
			echo "Account locked! Contact support for help.";
			header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
		}
	}else{
		unset($_SESSION['cs']);
		unset($_SESSION['lastactive']);
		unset($_SESSION['tcode']);
		echo "Please login!!!";
		header("Refresh: 4;URL= /projectv2/customer/html/customerlogin.html");
	}
?>