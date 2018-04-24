<?php
    include('sqlconnstorage.php');
	//session_start();
	if(checkUserLoginValid()==true){
		//**update employeeidpass sql tcode
		$myusername=$_SESSION['us'];
		$tempcode="";
		$statue="logoff";
		$logoffsql="UPDATE storagev1.employeeidpass 
									SET     Statue = '$statue' , TempCode = '$tempcode'
									WHERE  EUser = '$myusername'";
		if ($conn->query($logoffsql) === TRUE) {
		
			unset($_SESSION['us']);
			unset($_SESSION['lastactive']);
			unset($_SESSION['tcode']);
			header("Refresh: 1;URL= /projectv2/employee/html/elogin.html");
		}else{
			//do something when fail
			
		}	
	}else{
		//do something when check return fail
		//**need to remove when finish project
	}
	
	/*
	input
	6		$myusername
	
	*/
?>