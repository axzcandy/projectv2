<?php
   include('sqlconnstorage.php');
$what="show";
$user_check=$_SESSION['cs'];
if($what=="show")
{
	$sql = "SELECT * 
	FROM   `order` 
	WHERE CID = '$user_check'";
		
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