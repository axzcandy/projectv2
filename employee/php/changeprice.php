<?php
	include('sqlconnstorage.php');
	$icode=$_REQUEST["icode"];
	$itype=$_REQUEST["itype"];
	$price=$_REQUEST["price"];
	
	if(checkUserLoginValid()==true){
		$changeprice = "UPDATE `storage` SET Price='$price' WHERE ICode = '$icode'";
		
		if ($conn->query($changeprice) === TRUE) {
			$changeprice1 = "UPDATE `customerproductshow` SET Price='$price' WHERE ICode = '$icode'";
			
			if ($conn->query($changeprice1) === TRUE) {
				echo "Price change successfully!!";
				header("Refresh: 1;URL=/projectv2/employee/html/adminstorage.html");
			}else{
				
			}
		}else{
			// echo "change price failed!!";
			// echo mysqli_error($conn);
		}
	}
?>