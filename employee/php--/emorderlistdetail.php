<?php
	include('sqlconnstorage.php');
	$what=$_REQUEST["what"];
	if(1){
		if($what=="orderdetail"){	
		$oid=$_REQUEST["oid"];
		//$statue=$_REQUEST["statue"];if(checkUserLoginValid()==true){
			
				
				//statue ={new,approved,reject}
				$statue="updated";
				$sql = "SELECT * 
						FROM   `order` 
						WHERE Statue = '$statue' AND
						OID = '$oid'
						";
				
				
				$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
				
				$emparray = array();
				while($row =mysqli_fetch_assoc($result))
				{
					$emparray[] = $row;
				}	
				echo json_encode($emparray);	
				
				mysqli_close($conn);
			
			
		}else if($what=="oiddetail"){
			$oid=$_REQUEST["oid"];
			$sql = "SELECT * 
						FROM   `orderitem` 
						WHERE 
						OID = '$oid'
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
	}
?>