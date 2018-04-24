<?php
   include('sqlconnstorage.php');
	
   if(checkCustomerLoginValid()==true){
	   //checkUserLoginValid()==true
		$myusername=$_SESSION['cs'];
		//$myusername="222";
		$sql="UPDATE storagev1.customer SET ";
		
		// $ckidandpass = "SELECT * FROM customer WHERE CID = '$myusername'";
		// $result = mysqli_query($conn,$ckidandpass);
		// $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		if(isset($_REQUEST["tel"])){
			$tel=trim($_REQUEST["tel"]);//delete space
			$tel = preg_replace("/[^0-9 ]/", "", $tel);	//刪除數字以外的符號
		}else{}
		
		if(isset($_REQUEST["address"])){
			$address=trim($_REQUEST["address"]);
		}else{}
		// echo $tel;
		// echo $address;
		if(($address!="noadd")&&($tel!="notel")){
			$sql=$sql."Tel='$tel' , Address='$address' ";
		}else if(($address=="noadd")&&($tel=="notel")){
			header("Refresh: 1;URL= /projectv2/customer/html/csaccountmanage.html");
		}else if($address=="noadd"){
			$sql=$sql." Tel='$tel' ";
		}else if($tel=="notel"){
			$sql=$sql." Address='$address' ";
		}else{}
		$sql=$sql."WHERE CID='$myusername'";
		// echo $sql;
		if ($conn->query($sql) === TRUE) {
			//all pass here
			// echo ":pass:";
			header("Refresh: 1;URL=/projectv2/customer/html/csaccountmanage.html");
		}else{
			header("Refresh: 1;URL=/projectv2/customer/html/csaccountmanage.html");
			mysqli_error($conn);
		}
	}else{
		echo "valid()fail";
		echo $_REQUEST['cs'];
	}

   
	/**
	input
   
	4	$myusername
	5	$nops			舊密碼
	6	$nnps			新密碼
		annps			再次輸入新密碼
	
   output
   
   */
?>