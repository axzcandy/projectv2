<?php
   include('sqlconnstorage.php');
 
$what=$_REQUEST["what"];
//$what="left";

//check id valid code here
if(checkUserLoginValid()==true){


	if($what=="left")
	{
		
		$sql = "SELECT * 
				FROM   `storage` 
				";
		
		
		$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
		
		$emparray = array();
		while($row =mysqli_fetch_assoc($result))
		{
			$emparray[] = $row;
		}	
		echo json_encode($emparray);	
		
		mysqli_close($conn);
			
	}
}else{
	//**need to remove after finish project
	echo $_SESSION['us']);
}
?>