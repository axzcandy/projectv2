<?php
	include('sqlconnstorage.php');
	
	if(checkUserLoginValid()==true){
		$what="show";

		if($what=="show")
		{
			$sql = "SELECT * 
		FROM   `order` 
		WHERE Statue = 'sapprove'";
				
			$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));	
			$emparray = array();
			while($row =mysqli_fetch_assoc($result))
			{
				$emparray[] = $row;
			}	
			echo json_encode($emparray);	
			
			mysqli_close($conn);
				
		}
	}
?>