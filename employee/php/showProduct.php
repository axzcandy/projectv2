<?php
   include('sqlconnstorage.php');
 
$what="show";

if($what=="show")
{
	$sql = "SELECT * 
	FROM   `storage`
	WHERE TQuantity > 0  
	ORDER  BY `ICode`";
		
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