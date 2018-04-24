<?php
	include('sqlconnstorage.php');
	

	
	if(checkUserLoginValid()==true){
		
		$icode=$_REQUEST["icode2"];

		$sql = "select Remark from item where ICode = '$icode'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		$oldremark = (string)$row['Remark'];
		
		echo json_encode($oldremark);
	}
?>