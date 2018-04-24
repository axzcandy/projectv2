<?php
   include('sqlconnstorage.php');
   
      if(checkUserLoginValid()==true){
		$what="show";
		$user_check=$_SESSION['us'];
		if($what=="show")
		{
			$sql = "select * from employee where EID = '$user_check'";
				
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