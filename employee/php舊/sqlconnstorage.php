<?php

	//資料庫設定
	session_start();
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'storage');
	define('DB_PASSWORD', 'pass');
	define('DB_DATABASE', 'storagev1');
	//連線資料庫伺服器
	$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

	if (mysqli_connect_errno($conn))
	  //die("無法連線資料庫伺服器");
		echo("Connection failed: " . $conn->connect_error);
	//設定連線的字元集為 UTF8 編碼
	mysqli_set_charset($conn, "utf8_general_ci");
	
	//make idpass seperate from item sql????
	
	function checkUserLoginValid(){
		$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		
		if((isset($_SESSION['us'])) || (isset($_SESSION['tcode']))){
		//include_once('sqlConnect.php');
		//global $conn;
		$user_check=$_SESSION['us'];
		$tpcode=$_SESSION['tcode'];
		
		$sql="select Statue as st from employeeidpass where EID = '$user_check' and TempCode = '$tpcode'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$st = $row['st'];
		if($st!='new'){
			if(!isset($_SESSION['us'])){
				header("Refresh: 1;URL=/projectv2/employee/html/elogin.html");
				return false;
				//header("location:/project/login.html");
			}else{
				$user_check=$_SESSION['us'];
				$lasteactive=$_SESSION['lastactive'];
				$tpcode=$_SESSION['tcode'];
				
				$sql = "select LastActiveTime from employeeidpass where EID = '$user_check' and TempCode = '$tpcode'";
				$result2 = mysqli_query($conn,$sql);
				$row = mysqli_fetch_array($result2,MYSQLI_ASSOC);
				$count2 = mysqli_num_rows($result2);
				if($count2==1)
				{
					// record code here;
					return true;
				}else{
					return false;
				}
			}
		}else{
			// statue false
			echo 'fail';
			header("Refresh: 1;URL=/projectv2/employee/html/elogin.html");
		}
		}else{
			echo "Please login!!";
			header("Refresh: 1;URL=/projectv2/employee/html/elogin.html");
		}
	}
	
	function checkCustomerLoginValid(){
	$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	
	if((isset($_SESSION['cs'])) || (isset($_SESSION['tcode']))){
	//include_once('sqlConnect.php');
	//global $conn;
	$user_check=$_SESSION['cs'];
	$tpcode=$_SESSION['tcode'];
	
	$sql="select Statue from customeridpass where CID = '$user_check' and TempCode = '$tpcode'";
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

	if($row['Statue']!='new'){
		$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(!isset($_SESSION['cs'])){
			header("Refresh: 1;URL=/projectv2/customer/html/customerlogin.html");
			return false;
			//header("location:/project/login.html");
		}else{
			$user_check=$_SESSION['cs'];
			$lasteactive=$_SESSION['lastactive'];
			$tpcode=$_SESSION['tcode'];
			
			$sql = "select LastActiveTime from customeridpass where CUser = '$user_check' and TempCode = '$tpcode' ";
			$result2 = mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($result2,MYSQLI_ASSOC);
			$count2 = mysqli_num_rows($result2);
			if($count2==1)
			{
				// record code here;
				return true;
			}else{
				return false;
			}
		}
	}else{
		//echo 'fail';
		header("Refresh: 1;URL=/projectv2/customer/html/customerlogin.html");
	}
	}else{
		echo "Please login!!";
		header("Refresh: 1;URL=/projectv2/customer/html/customerlogin.html");
	}
}
	/*----------------------------------------------------------------------
	input
	27		$_SESSION['us'];
	53		$_SESSION['cs'];
	28		$_SESSION['lastactive'];
	29		$_SESSION['tcode'];
	
	output
	
	*/
	
	
	
?>