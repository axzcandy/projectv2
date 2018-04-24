<?php
    include('sqlconnstorage.php');

	if(checkCustomerLoginValid()==true){
		//**update employeeidpass sql tcode
		$myusername=$_SESSION['cs'];
		$tempcode="";
		$statue="logoff";
		$logoffsql="UPDATE storagev1.customeridpass 
									SET     Statue = '$statue' , TempCode = '$tempcode'
									WHERE  CID = '$myusername'";
		if ($conn->query($logoffsql) === TRUE) {
			unset($_SESSION['cs']);
			unset($_SESSION['lastactive']);
			unset($_SESSION['tcode']);
			//echo 'You have successfully logoff';
			header("Refresh: 1;URL= /projectv2/customer/html/csaccountmanage.html");
		}else{
			//do something when fail
		}	
	}else{
		//do something when check return fail
		//**need to remove when finish project
		//echo $_SESSION['cs'];
			unset($_SESSION['cs']);
			unset($_SESSION['lastactive']);
			unset($_SESSION['tcode']);
			//echo 'fail';
			header("Refresh: 1;URL= /projectv2/customer/html/csaccountmanage.html");
	}
	/*
	input
	6		$myusername
	
	*/
?>