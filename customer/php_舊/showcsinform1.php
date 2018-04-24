<?php
   include('sqlconnstorage.php');
$what="show";

if($what=="show")
{
	if(isset($_SESSION['cs'])){
		$cid= $_SESSION['cs'];
		$sql = "select * from customer where CID = '$cid'";
			
		$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));	
		$emparray = array();
		while($row =mysqli_fetch_assoc($result))
		{
			$emparray[] = $row;
		}	
		echo json_encode($emparray);	
		
		mysqli_close($conn);
	}else{
		echo json_encode("nocid");	
	}
} 
?>