<?php
	include('sqlconnstorage.php');
	

	
	if(checkUserLoginValid()==true){
		
		$icode=$_REQUEST["icode"];

		$sql = "select Price from storage where ICode = '$icode'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		$oldprice = $row['Price'];
		
		echo json_encode($oldprice);
	}
?>