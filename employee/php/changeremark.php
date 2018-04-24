<?php
	include('sqlconnstorage.php');
	$icode=$_REQUEST["icode2"];
	(string)$remark=$_REQUEST['remark'];
	
	if(checkUserLoginValid()==true){
		$changeremark = "UPDATE `item` SET Remark='$remark' WHERE ICode = '$icode'";
		
		if ($conn->query($changeremark) === TRUE) {
			$changemark1 = "UPDATE `customerproductshow` SET Remark='$remark' WHERE ICode = '$icode'";
			
			if ($conn->query($changemark1) === TRUE) {
				echo "Remark change successfully!!";
				header("Refresh: 1;URL=/projectv2/employee/html/adminstorage.html");
			}else{
				
			}
		}else{
			// echo "change price failed!!";
			// echo mysqli_error($conn);
		}
	}
?>