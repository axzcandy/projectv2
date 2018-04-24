<?php
include('sqlconnstorage.php');
	if(isset($_SESSION['cs'])){
		if(checkCustomerLoginValid()==true){
			//cs is login so change cart cookir id to cs
			$sqlupdatecartcookieid="";
			
			
			
			echo json_encode('true');
		}else{
			echo json_encode('false');
		}
	}else{
		echo json_encode('false');
	}
?>