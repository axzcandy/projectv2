<?php
	include('sqlconnstorage.php');
	//**check id valid here
	if(checkUserLoginValid()==true){
		$sql="SELECT * FROM supplier";
			
			
			$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));	
			$emparray = array();
			while($row =mysqli_fetch_assoc($result))
			{
				$emparray[] = $row;
			}	
			echo json_encode($emparray);
		
	}else{
		//failvaliduser
	}
	
?>