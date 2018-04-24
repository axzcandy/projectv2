<?php
	include('sqlconnstorage.php');
	function createRandomcode() { 

		$chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
		srand((double)microtime()*1000000); 
		$i = 0; 
		$pass = '' ; 

		while ($i <= 7) { 
			$num = rand() % 33; 
			$tmp = substr($chars, $num, 1); 
			$pass = $pass . $tmp; 
			$i++; 
		} 

		return $pass; 
   }
	if(isset($_REQUEST['usd'])){
		$usorem=$_REQUEST['usd'];
		$sql="SELECT * FROM customeridpass WHERE CUser = '$usorem' OR Email = '$usorem'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$count = mysqli_num_rows($result);
		if($count==1){
			$emailsend=$row['Email'];
			$newpass=createRandomcode();
			$newpasstosql=md5($newpass);
			
			
			
			$subject="Email verification code";
			$message="Your verify code is = ".$tempcodeforemail."\n";
			$headers="Verify Code";
					
			if(1 /*mail($email,$subject,$message,$headers)==true*/){
				echo "申請成功，請稍候";
				header("Refresh: 1;URL= /projectv2/customer/html/customerlogin.html");
			}else{
				// echo 'failed0';
			}
			
			
		}else{
			
		}
	}else{
		window.location.replace("/projectv2/customer/html/csforgetpass.html");
	}
	
?>