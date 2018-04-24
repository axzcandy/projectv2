<?php
   include('sqlconnstorage.php');
	
	$myusername=$_SESSION['cs'];
   	$nops=trim($_REQUEST["ops"]);	//delete space
	$nps=trim($_REQUEST["nps"]);
	$nnps=trim($_REQUEST["nnps"]);
	
	// $myusername='test';
	// $nops='1234';	//delete space
	// $nnps='12345';
	// $annps='12345';
	
	$ops = preg_replace("/[^A-Za-z0-9 ]/", "", $nops);	//刪除除了英文和數字以外的符號
	$nps = preg_replace("/[^A-Za-z0-9 ]/", "", $nps);
   	$nnps = preg_replace("/[^A-Za-z0-9 ]/", "", $nnps);
   if(checkCustomerLoginValid()==true){

		$ckidandpass = "SELECT * FROM `customeridpass` WHERE CPass = '$ops' and CID = '$myusername'";
		$result = mysqli_query($conn,$ckidandpass);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		if (strlen($ops) >=4 && strlen($ops) <=12){	//控制字串長度4~12Bytes
		if ($myusername == $row['CID']){
			if(($ops == $row['CPass']) && ($nnps == $nps) && ($ops != $nnps)){
				$changecpass="UPDATE storagev1.customeridpass 
										SET CPass = '$nps'
										WHERE  CID = '$myusername'";
				if ($conn->query($changecpass) === TRUE) {
					//echo "Your pass changed successfully!!";
					// turn another page
					header("Refresh: 1;URL=/projectv2/customer/html/csaccountmanage.html");
				}else{
					// CPass write in failed
				}
				
			}else{
				//CPass has problem
				echo 'CPass has problem';
			}
		} else{
			// CUser has problem
			echo 'CUser has problem';
		}
		}else{
			echo "Your pass len not true";
		}
   }
   else{
	   echo 'failed';
	   echo $_SESSION['cs'];
   }
   
   
	/**
	input
   
	4	$myusername
	5	$nops			舊密碼
	6	$nnps			新密碼
		annps			再次輸入新密碼
	
   output
   
   */
?>