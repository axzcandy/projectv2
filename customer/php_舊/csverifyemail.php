<?php
	include('sqlconnstorage.php');
	
		if(isset($_SESSION['cs'])){
			
			$cid = $_SESSION['cs'];
			echo $cid;
			$tempcode = $_REQUEST['tempcode'];
			$lastactivetime = date("Y-m-d H:i:s");
			$sessiontempcode = $_SESSION['tcode'];
			
			// echo $sessiontempcode;
			// $_SESSION['lastactive'];
			// $_SESSION['tcode'];
			
			
			$sql = "select TempCode as tempc from customeridpass where CID ='$cid' and Statue ='new'";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$temp2 = $row['tempc'];
			if($tempcode == $temp2){
				$sql1 = "UPDATE storagev1.customeridpass 
									SET     Statue = 'login' , Tempcode = '$sessiontempcode' , LastActiveTime = '$lastactivetime'
									WHERE  CID = '$cid'";
				if ($conn->query($sql1) === TRUE) {
					if(checkCustomerLoginValid()==TRUE){
					echo 'success';
					header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
					}else{
						echo 'checkCustomerLoginValid fail';
					}
					
				}else{
					echo 'failed';
				}
			}else{
				echo 'tempcode false';
				//header("Refresh: 1;URL= /projectv2/customer/html/cverifyemail.html");
			}
		}else{
			echo 'what';
		}
?>