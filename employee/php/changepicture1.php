<?php
	include('sqlconnstorage.php');
	
	
	if(checkUserLoginValid()==true){

		$icode=$_REQUEST["icode"];

		$sql = "select Picture from item where ICode = '$icode'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		$oldPicture = (string)$row['Picture'];
		
		echo json_encode($oldPicture);
	}
?>