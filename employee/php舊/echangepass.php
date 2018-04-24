<?php
   include('sqlconnstorage.php');
	
	
   if(checkUserLoginValid()==true){
		$myusername=$_SESSION['us'];
		$nops=trim($_REQUEST["ops"]);	//delete space
		$nps=trim($_REQUEST["nps"]);
		$nnps=trim($_REQUEST["nnps"]);
		
		// $myusername='test';
		// $nops='1234';	//delete space
		// $nnps='12345';
		// $annps='12345';
		
		$opt = preg_replace("/[^A-Za-z0-9 ]/", "", $nops);	//刪除除了英文和數字以外的符號
		$ops = md5($opt);
		$nps = preg_replace("/[^A-Za-z0-9 ]/", "", $nps);
		$annps = preg_replace("/[^A-Za-z0-9 ]/", "", $nnps);
	
		$ckidandpass = "SELECT * FROM `employeeidpass` WHERE EPass = '$ops' and EID = '$myusername'";
		$result = mysqli_query($conn,$ckidandpass);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		if (strlen($opt) >=4 && strlen($opt) <=12){	//控制字串長度4~12Bytes
		if ($myusername == $row['EID']){
			if(($ops == $row['EPass']) && ($annps == $nps) && ($ops != $annps)){
				$changecpass="UPDATE storagev1.employeeidpass 
										SET EPass = '$ops'
										WHERE  EID = '$myusername'";
				if ($conn->query($changecpass) === TRUE) {
					//echo "Your pass changed successfully!!";
					// turn another page
					header("Refresh: 1;URL=/projectv2/employee/html/accountmanage.html");
				}else{
					// CPass write in failed
				}
				
			}else{
				//CPass has problem
				echo 'EPass has problem';
			}
		} else{
			// CUser has problem
			echo 'EUser has problem';
		}
		}else{
			echo "Your pass len not true";
		}
   }
   else{

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