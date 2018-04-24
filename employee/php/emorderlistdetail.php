<?php
	include('sqlconnstorage.php');
	$what=$_REQUEST["what"];
	if(checkUserLoginValid()==true){
		if($what=="orderdetail"){	
		$oid=$_REQUEST["oid"];
		//$statue=$_REQUEST["statue"];if(checkUserLoginValid()==true){
			
				
				//statue ={new,approved,reject}
				//$statue="updated";
				$sql = "SELECT * 
						FROM   `order` 
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

		}else if($what=="oiddetail"){
			$oid=$_REQUEST["oid"];
			
			$sql = "SELECT orderitem.OIDD,orderitem.OID,orderitem.ICode,orderitem.Quantity,
					orderitem.Price,orderitem.DeliveryDate,item.IName
					FROM orderitem INNER JOIN item ON orderitem.ICode = item.ICode
					WHERE orderitem.OID = '$oid'";
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