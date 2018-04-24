<?php
   include('sqlconnstorage.php');
   
      if(checkUserLoginValid()==true){
		$what="show";
		$user_check=$_SESSION['us'];
		$deid = $_REQUEST["deid"];
		if($what=="show")
		{
			$sql = "UPDATE storagev1.employeeidpass SET Statue = 'locked WHERE EID = '$deid'";
						
			echo mysqli_error($conn);
			$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));	
			$emparray = array();
			while($row =mysqli_fetch_assoc($result))
			{
				$emparray[] = $row;
			}	
			echo json_encode($emparray);	
			
			mysqli_close($conn);
				
		}
		header("Refresh: 1;URL= /projectv2/employee/html/employeeconf.html");
	  }
?>

