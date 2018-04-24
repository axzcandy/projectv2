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
		
		$md5pass = md5($mypassword);
      
		$sql = "SELECT * FROM employeeidpass WHERE (EUser = '$myusername' or Email = '$myusername') and EPass = '$md5pass'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$ip = $_SERVER["REMOTE_ADDR"];
		//$ip =$_SERVER["HTTP_X_REAL_IP"];
		//$tried==$row['LoginTried'];
		
		//@$active = $row['active'];
		$statue = $row['Statue'];
		$eid = $row['EID'];
		
		$count = mysqli_num_rows($result);
		if($count == 1) 
		{
			if($statue!="logoff"){
				if($statue=="new"){
					//**go to verify account
					$_SESSION['us'] = $eid;
					// echo $_SESSION['us'];
					header("Refresh: 1;URL= /projectv2/employee/html/emverifyemail.html");
				}
				else if($statue=='locked'){
					echo "Account Locked !! Contact Admin";
					header("Refresh: 1;URL= /projectv2/employee/html/acclock.html");
				}else if($statue=='login'){
					// echo "login";
					$currenttime=date("Y-m-d H:i:s");
					$tempcode=createRandomcode();
					$sqlsettime = "UPDATE storagev1.employeeidpass 
									SET     LastActiveTime = '$currenttime' , TempCode = '$tempcode'
									WHERE  EUser = '$myusername'";
					if ($conn->query($sqlsettime) == TRUE){
						$_SESSION['lastactive'] = $currenttime;
						$_SESSION['tcode'] = $tempcode;
						$_SESSION['us'] = $eid;
						
						//echo $_SESSION['us'];
						header("Refresh: 1;URL= /projectv2/employee/html/accountmanage.html");
						//include("../login.php");

					}else{
						// echo 'failed22';
					}
				}else{
					//Statue has some problem
					// echo 'Statue has some problem';
				}
			}else{
					//statue  = logoff
					$currenttime=date("Y-m-d H:i:s");
					$tempcode=createRandomcode();
					$sqlsettime = "UPDATE storagev1.employeeidpass 
									SET     LastActiveTime = '$currenttime' , TempCode = '$tempcode'
									WHERE  EUser = '$myusername'";
					if ($conn->query($sqlsettime) == TRUE){
						$_SESSION['lastactive'] = $currenttime;
						$_SESSION['tcode'] = $tempcode;
						$_SESSION['us'] = $eid;
						
						//echo $_SESSION['us'];
						header("Refresh: 1;URL= /projectv2/employee/html/accountmanage.html");
						//include("../login.php");

					}else{
						//echo 'failed22';
						header("Refresh: 1;URL= /projectv2/employee/html/accountmanage.html");
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
			  header("Refresh: 3;URL= /projectv2/employee/html/acclock.html");
			}else{
				//echo '44';
				echo "You have tried ".(String)$count[0]." time";
			}
			header("Refresh: 1;URL= /projectv2/employee/html/elogin.html");
		}
		
   }else{
	//not post
	header("Refresh: 1;URL= /projectv2/employee/html/elogin.html");
	}
   
   
   /*--------------------------------
   input
   21		$_SERVER["REQUEST_METHOD"]
   24		$myusername
   25		$mypassword
   
   output
   50		$_SESSION['tcode']
   51		$_SESSION['us']
   
   62.85.90		/projectv2/employee/html/emmainpage.html
   */
?>