<?php
	include('sqlconnstorage.php');
	header("Content-Type:text/html; charset=utf-8");
	
		if(isset($_SESSION['cs'])){
			
			$cid = $_SESSION['cs'];
			$sql = "select statue as st from customeridpass where Statue = 'new' and CID = '$cid'";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$stat = $row['st'];
			
			if($stat=='new'){

				$tempcode = $_REQUEST['tempcode'];
				$lastactivetime = date("Y-m-d H:i:s");
				
				// echo $sessiontempcode;
				// $_SESSION['lastactive'];
				// $_SESSION['tcode'];
				
				
				$sql = "select TempCode as tempc from customeridpass where CID ='$cid' and Statue ='new'";
				$result = mysqli_query($conn,$sql);
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
				$temp2 = $row['tempc'];
				if($tempcode == $temp2){
					$sql1 = "UPDATE storagev1.customeridpass 
										SET     Statue = 'login' , Tempcode = '$tempcode' , LastActiveTime = '$lastactivetime'
										WHERE  CID = '$cid'";
					if ($conn->query($sql1) === TRUE) {
						$_SESSION['tcode']=$tempcode;
						// echo $_SESSION['tcode'];
						if(checkCustomerLoginValid()==TRUE){
						// echo 'success';
						echo "輸入成功，請稍後";
						header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
						}else{
							echo mysqli_error($conn);
							echo 'checkCustomerLoginValid fail';
						}
						
					}else{
						// echo 'failed';
						// echo mysqli_error($conn);
					}
				}else{
					echo '驗證碼錯誤，請重新輸入。';
					header("Refresh: 1;URL= /projectv2/customer/html/cverifyemail.html");
				}
			}else{
				echo "已經確認過，轉回主頁面。";
				header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
			}
		}else{
			// echo 'what';
		}
?>