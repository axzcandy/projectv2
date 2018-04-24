<?php
	include('sqlconnstorage.php');
	
	function checkusername($euser,$email){
		$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		
	    $sql = "SELECT COUNT(EUser) AS 'countuser' FROM ereg WHERE EUser = '$euser' or Email = '$email'";
	    $result = mysqli_query($conn,$sql);
		$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    $count = $rowinfo['countuser'];
		
	    if($count!=0){
	 	    mysqli_close($conn);
		    return false;
	    }else{
			$currentdate=date("Y-m-d");
			$sql1 = "SELECT COUNT(EUser) AS countid FROM ereg WHERE  DateCreated = '$currentdate'";
			$result = mysqli_query($conn,$sql1);
			$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
			global $id;
			$id=$rowinfo['countid'];
		    mysqli_close($conn);
			// echo $id;
		    return true;
	    }
	}
	function createRandomcode() { 

		$chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
		srand((double)microtime()*1000000); 
		$i = 0; 
		$pass = '' ; 

		while ($i <= 8) { 
			$num = rand() % 33; 
			$tmp = substr($chars, $num, 1); 
			$pass = $pass . $tmp; 
			$i++; 
		} 

		return $pass; 
   }
	if(checkUserLoginValid()==true){
	$euser = $_REQUEST["euser"];  // 前端多加euser輸入欄位
	//$euser = '12345';
	// $cuser = 'AAA';
	$email=$_REQUEST["email"];
	$ename=$_REQUEST["ename"];
	// $ps1=$_REQUEST["ps1"];	
	// $ps2=$_REQUEST["ps2"];
	$tel=$_REQUEST["tel"];
	$address=$_REQUEST["address"];
	$dob=$_REQUEST["dob"];
	// dob = date not datetime
	$agreement=$_REQUEST["agreement"];
	$gender = $_REQUEST["gender"];
	//$agreement 0/1

	$currenttime=date("Y-m-d H:i:s");
	$currentdate=date("Y-m-d");
	
	if((checkusername($euser,$email)==true)){
	
		// echo $id;
		$eid="EM".$currentdate.$id;
		$tempcodeforemail=createRandomcode();
		$statue="new";
		$epassrandom = createRandomcode();
		$submitbyeid = $_SESSION['us'];
		
		$md5epassramdom = md5($epassrandom);
		
		$insertcreg="INSERT INTO ereg(EID, EUser, Email, EPass, SubmitByEID, Statue, DateCreated) 
		VALUES ('$eid','$euser','$email','$md5epassramdom','$submitbyeid','$statue','$currentdate')";
		if ($conn->query($insertcreg) === TRUE) {
			//insert success $insertcreg
			$insertidpass="INSERT INTO employeeidpass(EID, EUser, EPass, Email, Statue, TempCode, LastActiveTime) 
			VALUES ('$eid','$euser','$md5epassramdom','$email','$statue','$tempcodeforemail','$currenttime')";
			
			//**send email to user
			
			if ($conn->query($insertidpass) === TRUE) {
				$insertinfo="INSERT INTO employee(EID, EUser, EName, Gender, DOB, Address, Email, Tel,DateCreated) 
				VALUES ('$eid','$euser','$ename','$gender','$dob','$address','$email','$tel','$currentdate')";
				
				if ($conn->query($insertinfo) === TRUE) {
					//success all
					$subject="Email verification code";
					$message="Your verify code is = ".$tempcodeforemail."\nUsername =" 
					.$euser."\nPass = ".$epassrandom;
					$headers="Verify Code";
					
					if(mail($email,$subject,$message,$headers)==true){
						//echo "mail send";
						//echo 'suscess all';
						//**redirect page??
						header("Refresh: 1;URL= /projectv2/employee/html/employeeconf.html");
					}else{
						//echo "mail failed";
					}
						
					// }else{
						 // echo 'failed0';
					// }
					
				}else{
					//insert into customer failed insertinfo
					// echo mysqli_error($conn);
					// echo 'failed1';
				}
			}else{
				//insert into idpass failed $insertidpass
				// echo mysqli_error($conn);
				// echo 'failed2';
			}
		}else{
			//insert into creg SQL failed $insertcreg
			// echo mysqli_error($conn);
			// echo 'failed3';
		 }
		
	}else{
		//checkusername return false
		// echo mysqli_error($conn);
		// echo 'failed4';
	}
	}else{
		// echo 'fault';
	}
	/*
	input
	44		$cuser
	45		$email
	46		$cname
	47		$ps1
	48		$ps2
	49		$tel
	50		$address
	51		$dob
	53		$agreement
	
	output
	
	*/
?>