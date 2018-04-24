<?php
	include('sqlconnstorage.php');
	$spname=$_REQUEST["spname"];
	$spemail=$_REQUEST["spemail"];
	$sptel=$_REQUEST["sptel"];
	function checkusername($spname,$spemail){
		$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		
	    $sql = "SELECT COUNT(SName) AS 'countsupplier' FROM supplier WHERE SName = '$spname' or Email = '$spemail'";
	    $result = mysqli_query($conn,$sql);
		$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    $count = $rowinfo['countsupplier'];
		//echo $count;
	    if($count!=0){
			mysqli_close($conn);
		    return false;
	    }else{
			$sql1 = "SELECT COUNT(SName) AS countid FROM supplier";
			$result = mysqli_query($conn,$sql1);
			$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
			global $id;
			$id=$rowinfo['countid'];
	 	    mysqli_close($conn);
		    return true;
	    }
	}
	
	$currentdate=date("Y-m-d");
	if(checkusername($spname,$spemail)==true){
		$sid="SP".$currentdate.$id;
		
		$insertsupplier="INSERT INTO supplier(SID, SName, Email, Tel) 
		VALUES ('$sid','$spname','$spemail','$sptel')";	
		if ($conn->query($insertsupplier) === TRUE) {
			echo "success";
			header("Refresh: 1;URL= /projectv2/employee/html/supplier.html");
		}else{
			echo "fail";
			header("Refresh: 1;URL= /projectv2/employee/html/supplier.html");
		}		
	}else{
		//checkusername return false
		//echo mysqli_error($conn);
		echo 'name or email error';
		header("Refresh: 1;URL= /projectv2/employee/html/supplier.html");
	}
	
	
?>