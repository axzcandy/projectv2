<?php
	include('sqlconnstorage.php');
	header("Content-Type:text/html; charset=utf-8");
	function checkusername($cuser,$email){
		$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		
	    $sql = "SELECT COUNT(CUser) AS 'countuser' FROM creg WHERE CUser = '$cuser' or Email = '$email'";
	    $result = mysqli_query($conn,$sql);
		$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    $count = $rowinfo['countuser'];
		// echo $count;
	    if($count!=0){
	 	    mysqli_close($conn);
		    return false;
	    }else{
			$currentdate=date("Y-m-d");
			$sql1 = "SELECT COUNT(CUser) AS countid FROM creg WHERE  DateCreated = '$currentdate'";
			$result = mysqli_query($conn,$sql1);
			$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
			global $id;
			$id=$rowinfo['countid'];
		    mysqli_close($conn);
		   
		    return true;
	    }
	}
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
	
	//$cuser = mysqli_real_escape_string($conn,strtoupper($_POST['cuser']));
	$cuser = preg_replace("/[^A-Za-z0-9 ]/", "", strtoupper($_POST["cuser"]));
	$email=$_REQUEST["email"];
	$_SESSION['email'] = $email;
	$cname=$_REQUEST["cname"];
	$ps1=$_REQUEST["ps1"];
	$ps2=$_REQUEST["ps2"];
	$tel=$_REQUEST["tel"];
	$address=$_REQUEST["address"];
	$dob=$_REQUEST["dob"];
	// dob = date not datetime
	$agreement=$_REQUEST["agreement"];
	//$agreement 0/1
	$gender = $_REQUEST["gender"];
	
	//$email='';
	//$cname='1034';
	//$ps1='123456';
	//$ps2='123456';
	//$tel='111';
	//$address='test';
	//$dob='2017-12-08 19:05:54';
	// dob = date not datetime
	//$agreement=$_REQUEST["agreement"];
	
	
	$currenttime=date("Y-m-d H:i:s");
	$currentdate=date("Y-m-d");
	
	if((checkusername($cuser,$email)==true)&&($ps1==$ps2)){
		$md5ps = md5($ps1);
		
		$cid="CS".$currentdate.$id;
		$eid="SYS0000";
		$tempcodeforemail=createRandomcode();
		$statue="new";
		$_SESSION['cs'] = $cid;
		$_SESSION['tcode'] = $tempcodeforemail;
		$_SESSION['lastactive'] = $currenttime;
		
		$insertcreg="INSERT INTO creg(CID, CUser, Email, CPass, SubmitByEID, Statue, DateCreated) 
		VALUES ('$cid','$cuser','$email','$md5ps','$eid','$statue','$currentdate')";
		if ($conn->query($insertcreg) === TRUE) {
			//insert success $insertcreg
			$insertidpass="INSERT INTO customeridpass(CID, CUser, CPass, Email, Statue, TempCode, LastActiveTime) 
			VALUES ('$cid','$cuser','$md5ps','$email','$statue','$tempcodeforemail','$currenttime')";
			
			//**send email to user
			
			if ($conn->query($insertidpass) === TRUE) {
				$insertinfo="INSERT INTO customer(CID, CName, Gender, DOB, Address, Email, Tel, DateCreated) 
				VALUES ('$cid','$cname','$gender','$dob','$address','$email','$tel','$currentdate')";
				
				if ($conn->query($insertinfo) === TRUE) {
					//success all
					//**redirect page??
					$subject="Email verification code";
					$message="Your verify code is = ".$tempcodeforemail."\n";
					$headers="Verify Code";
					
					if(mail($email,$subject,$message,$headers)==true){
						echo "申請成功，請稍候";
						header("Refresh: 1;URL= /projectv2/customer/html/cverifyemail.html");
					}else{
						// echo 'failed0';
					}
					
				}else{
					//insert into customer failed insertinfo
					// 第三層，要刪去一二層資料
					
					echo mysqli_error($conn);
					echo 'failed1';
					//第一層
					$sql = "DELETE FROM `creg` WHERE CID = '$cid'";
					if ($conn->query($sql) === TRUE) {
						// 第二層
						$sql = "DELETE FROM `customeridpass` WHERE CID = '$cid'";
						if ($conn->query($sql) === TRUE) {
							header("Refresh: 1;URL= /projectv2/customer/html/customer register.html");
						}else{echo "second del fail";}
						
					}else{echo "first del fail";}
								
					//
				}
			}else{
				//insert into idpass failed $insertidpass
				// 第二層，要刪去第一層資料
				echo mysqli_error($conn);
				echo 'failed2';
				//第一層
					$sql = "DELETE FROM `creg` WHERE CID = '$cid'";
					if ($conn->query($sql) === TRUE) {
						header("Refresh: 1;URL= /projectv2/customer/html/customer register.html");
					}else{echo "first del fail";}
				
				//
			}
		}else{
			//insert into creg SQL failed $insertcreg
			echo mysqli_error($conn);
			echo 'failed3';
		}
		
	}else{
		//checkusername return false
		echo "帳號或信箱已經有人使用過了！";
		header("Refresh: 1;URL= /projectv2/customer/html/customer register.html");
		// echo mysqli_error($conn);
		// echo 'failed4';
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