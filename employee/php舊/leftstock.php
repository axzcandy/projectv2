<?php
   include('sqlconnstorage.php');
 

//$what="left";

//check id valid code here
if(checkUserLoginValid()==true){
$what=$_REQUEST["what"];

	if($what=="left")
	{
		
		$sql = "SELECT * 
				FROM   `storage` 
				WHERE TQuantity>0";
		
		
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
}
?>