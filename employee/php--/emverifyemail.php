<?php
	include('sqlconnstorage.php');
	
		if(isset($_SESSION['us'])){
			
			$tempcode = $_REQUEST['tempcode'];
			$currenttime = date("Y-m-d H:i:s");
			$eid = $_SESSION['us'];
			
			// echo $sessiontempcode;
			// $_SESSION['lastactive'];
			// $_SESSION['tcode'];
			
			
			$sql = "select TempCode as tempc from employeeidpass where EID ='$eid' and Statue ='new'";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$temp2 = $row['tempc'];
			if($tempcode == $temp2){
				$sql1 = "UPDATE storagev1.employeeidpass 
									SET     Statue = 'login' , LastActiveTime = '$currenttime'
									WHERE  EID = '$eid'";
				if ($conn->query($sql1) === TRUE) {
					if(checkEmployeeLoginValid()==TRUE){
					//echo 'success';
					$_SESSION['tcode'] = $tempcode;
					$_SESSION['lastactivetime'] = $currenttime;
					header("Refresh: 1;URL= /projectv2/html/EmployeeSucess.html");
					}else{
						echo 'checkemployeeLoginValid fail';
						unset($_SESSION['us']);
						unset($_SESSION['lastactive']);
						unset($_SESSION['tcode']);
						header("Refresh: 1;URL= /projectv2/html/EmployeeLogin.html");
					}
					
				}else{
					echo 'failed';
				}
			}else{
				echo 'tempcode false';
				unset($_SESSION['us']);
				unset($_SESSION['lastactive']);
				unset($_SESSION['tcode']);
				header("Refresh: 1;URL= /projectv2/html/EmployeeLogin.html");
			}
		}else{
			unset($_SESSION['us']);
			unset($_SESSION['lastactive']);
			unset($_SESSION['tcode']);
			header("Refresh: 1;URL= /projectv2/html/EmployeeLogin.html");
		}
?>