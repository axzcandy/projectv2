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
   if($_SERVER["REQUEST_METHOD"] == "POST") 
   {
     
		$myusername = mysqli_real_escape_string($conn,strtoupper($_POST['username']));
		$mypassword = mysqli_real_escape_string($conn,$_POST['password']);
		$mypasswordmd5 = md5($mypassword);
		
		$sql = "SELECT * FROM customeridpass WHERE (CUser = '$myusername' or Email = '$myusername') and CPass = '$mypasswordmd5'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$ip = $_SERVER["REMOTE_ADDR"];
		//$ip =$_SERVER["HTTP_X_REAL_IP"];
		//$tried==$row['LoginTried'];
		
		//@$active = $row['active'];
		$statue = $row['Statue'];
		$cid = $row['CID'];
		$email = $row['Email'];

		$count = mysqli_num_rows($result);
		if($count == 1) 
		{
			if($statue!="logoff"){
				if($statue=="new"){
					//**go to verify account
					$tempcode=createRandomcode();
					$currenttime=date("Y-m-d H:i:s");
					$sqlsettime = "UPDATE storagev1.customeridpass 
									SET     LastActiveTime = '$currenttime' , TempCode = '$tempcode'
									WHERE  CUser = '$myusername' or Email = '$myusername'";
					if ($conn->query($sqlsettime) == TRUE){
						//echo "new";
						$_SESSION['cs'] = $cid;
						$_SESSION['email'] = $email;
						$_SESSION['tcode'] = $tempcode;
						header("Refresh: 1;URL= /projectv2/customer/html/cverifyemail.html");
					}
				}else if($statue=='locked'){
					echo "Account Locked !! Contact Admin";
					header("Refresh: 3;URL= /projectv2/customer/html/customerlogin.html");
				}else if($statue=='login'){
					// echo "login";
					$currenttime=date("Y-m-d H:i:s");
					$tempcode=createRandomcode();
					$sqlsettime = "UPDATE storagev1.customeridpass 
									SET     LastActiveTime = '$currenttime' , TempCode = '$tempcode'
									WHERE  CUser = '$myusername' or Email = '$myusername'";
					if ($conn->query($sqlsettime) == TRUE){
						$_SESSION['lastactive'] = $currenttime;
						$_SESSION['tcode'] = $tempcode;
						$_SESSION['cs'] = $cid;
						
						//echo $_SESSION['us'];
						header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
						//include("../login.php");

					}else{
						//echo 'failed22';
					}
				}else{
					//Statue has some problem
					//echo 'Statue has some problem';
				}
				
			}else{
					//statue  = logoff
					$currenttime=date("Y-m-d H:i:s");
					$tempcode=createRandomcode();
					$sqlsettime = "UPDATE storagev1.customeridpass 
									SET     LastActiveTime = '$currenttime' , TempCode = '$tempcode'
									WHERE  CUser = '$myusername' or Email = '$myusername'";
					if ($conn->query($sqlsettime) == TRUE){
						$_SESSION['lastactive'] = $currenttime;
						$_SESSION['tcode'] = $tempcode;
						$_SESSION['cs'] = $cid;
						
						//echo $_SESSION['us'];
						header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
						//include("../login.php");

					}else{
						// echo 'failed22';
						header("Refresh: 1;URL= /projectv2/customer/html/customerlogin.html");
					}
				}
		}else{
			//echo "Id Pass error!!";
			mysqli_query($conn, "INSERT INTO `ip` (`address` ,`timestamp`)VALUES ('$ip',CURRENT_TIMESTAMP)");
			$result = mysqli_query($conn, "SELECT COUNT(*) FROM `ip` WHERE `address` LIKE '$ip' AND `timestamp` > (now() - interval 10 minute)");
			$count = mysqli_fetch_array($result, MYSQLI_NUM);

			if($count[0] > 3){
			  echo "Your are allowed 3 attempts in 10 minutes";
			  //**redirect page after success
			  //**sql update status to locked
			  
			  header("Refresh: 3;URL= /projectv2/customer/html/customerlogin.html");
			}else{
				//echo '44';
				echo "You have tried ".(String)$count[0]." time";
			}
			header("Refresh: 1;URL= /projectv2/customer/html/customerlogin.html");
		}
		
   }else{
	//not post
   }
   
   /*--------------------------------------------------------
   input
   8		$myusername
   9		$mypassword
   15		$ip
   
   output
   33		$_SESSION['lastactive']
   34		$_SESSION['tcode']
   38		$_SESSION['cs']
   
   */
?>