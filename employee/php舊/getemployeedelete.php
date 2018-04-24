<?php
include('sqlconnstorage.php');
	if(checkUserLoginValid()==true){
		if($_REQUEST['what']=="getallname"){
		
			$user=$_SESSION['us'];
			
				$sql="SELECT employee.EID, employee.EName, employee.DeptID, employee.Tel, employeeidpass.Statue FROM employee 
				 JOIN employeeidpass ON employee.EID = employeeidpass.EID WHERE employeeidpass.Statue != 'unknow' and employeeidpass.EID !='SYS0000' 
				and employeeidpass.EID != '$user'";
				$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));	
				$emparray = array();
				while($row =mysqli_fetch_assoc($result))
				{
					$emparray[] = $row;
				}	
				echo json_encode($emparray);	
				mysqli_close($conn);
			
		}else if($_REQUEST['what']=="getsingleinfo"){
			$eid=$_REQUEST['eid'];
			$sql="SELECT employee.EID, employee.EName, employee.DeptID, employee.Tel, employeeidpass.Statue FROM employee 
			 JOIN employeeidpass ON employee.EID = employeeidpass.EID WHERE employeeidpass.EID = '$eid'";
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