<?php
	include('sqlconnstorage.php');
	$spname=$_REQUEST["spname"];
	$spemail=$_REQUEST["spemail"];
	$sptel=$_REQUEST["sptel"];
	function checkusername($SID,$email){
		$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		
	    $sql = "SELECT COUNT(SName) AS 'countsupplier' FROM supplier WHERE SName = '$spname' or Email = '$spemail'";
	    $result = mysqli_query($conn,$sql);
		$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
	    $count = $rowinfo['countsupplier'];
		echo $count;
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
	if((checkusername($cuser,$email)==true){
		$sid="SP".$currentdate.$id;
		
		$insertsupplier="INSERT INTO supplier(SID, SName, Email, Tel) 
		VALUES ('$sid','$spname','$spemail','$sptel')";		
	}else{
		//checkusername return false
		echo mysqli_error($conn);
		echo 'failed';
	}
	
	/*
	input
	44		$cuser
	45		$email
	46		$cname
	47		$ps1
	48		$ps2
	49		$tel
	50		$address
	51		$dob
	53		$agreement
	
	output
	
	*/
?>