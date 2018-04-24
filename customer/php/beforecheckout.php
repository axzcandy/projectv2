<?php
include('sqlconnstorage.php');
	if(isset($_SESSION['cs'])){
		if(checkCustomerLoginValid()==true){
			echo json_encode('true');
		}else{
			echo json_encode('false');
		}
	}else{
		echo json_encode('false');
	}
?>