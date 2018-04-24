<?php
include('sqlconnstorage.php');
	if(checkUserLoginValid()==true){
		
		$sql="SELECT employee.EID, employee.EName, employee.DeptID, employee.Tel, employeeidpass.Statue FROM employee 
		INNER JOIN employeeidpass ON employee.EID = employeeidpass.EID WHERE employeeidpass.Statue != 'unknow'";
		$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));		
		$emparray = array();
		while($row =mysqli_fetch_assoc($result))
		{
			$emparray[] = $row;
		}	
		echo json_encode($emparray);	
		mysqli_close($conn);
		
	}
?>