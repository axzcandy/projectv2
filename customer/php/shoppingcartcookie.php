<?php
	include('sqlconnstorage.php');
	// $array = array(
		// array("a", 2,1010),
		// array("b", 5,200),
		// array("c", 2,2200),
		// array("d", 5,21020),
		// array("e", 6,200),
		// array("f", 5,2030),
		// array("g", 12,2200),
		// array("h", 45,32010),
		// array("i", 52,1200),
		// array("j", 27,1100),
	// );
	//$str = serialize($array);
	if(isset($_REQUEST['cookieid'])){
		//$ip =$_SERVER["HTTP_X_REAL_IP"];
		
		//echo $cookieid."fff";
		//$cookieid="testingcookie";
		$cookieid=$_REQUEST['cookieid'];
		$sql="SELECT * FROM anonymouscart WHERE UniqueCookieID = '$cookieid'";
		$result = mysqli_query($conn,$sql);
		$count = mysqli_num_rows($result);
		
		if($count==1){
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$str = $row['2DArray'];
			
			$unserializedString = unserialize($str);
			//print_r($unserializedString) ;
			// print_r($array);
			//echo  ($str);
			echo json_encode($unserializedString);
			
		}else{
			echo json_encode('nodata');
		}
		
	}else{
		
	echo json_encode('cookie isset');
	}
?>