<?php
	include('sqlconnstorage.php');
	
	if(checkCustomerLoginValid()==true){
		// checkCustomerLoginValid()==true
		$tempcodeforemail = $_SESSION['tcode']; 
		$email = $_SESSION['email'];
		
		$sql = "select statue as st from customeridpass where Email = '$email'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$stat = $row['st'];

		if($stat=='new'){
			$subject="Email verification code";
			$message="Your verify code is = ".$tempcodeforemail."\n";
			$headers="Verify Code";
						
			if(mail($email,$subject,$message,$headers)==true){
				echo "再次送出成功，請稍候";
				header("Refresh: 1;URL= /projectv2/customer/html/cverifyemail.html");
			}else{
				// echo 'failed0';
			}
		}else{
			echo "您已經確認過，將轉入主頁面。";
			header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
		}
	}else{
		echo "Please login!!";
		header("Refresh: 1;URL= /projectv2/customer/html/customer register.html");
	}
?>