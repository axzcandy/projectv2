<?php
    include('sqlconnstorage.php');

	if(checkUserLoginValid()==true){
		//get value from client
		$cid=$_REQUEST["cid"];
		$deliverydate=$_REQUEST["deliverydate"];
		$deliverytype=$_REQUEST["deliverytype"];
		$location=$_REQUEST["location"];
		
		$icodearray=$_REQUEST['icodearray']; //不確定是不是會自動為陣列
		$iquantityarray=$_REQUEST['iquantityarray']; //不確定是不是會自動為陣列
		//$quantitytypearray=$_REQUEST['quantitytypearray'];
		//$itemnumber=(int)$_REQUEST["itemnumber"];
		$itemnumber = count($_REQUEST['icodearray']);
		$paymentmethod = 'creditcard';  // 繳費方式
		$payok = 0;  // 訂單是否已經繳費
		$oprocess = 'truck';  // 取貨方式
		/*
		$itemandquantity=array();
		for($n=0;$n<$itemnumber;$n++){
			$itemandquantity[$n]=array(
			$icode[$n],
			$quantity[$n]
			);
			*/
			//need to check quantity on both end??
			/*
			$sqlcheck="SELECT TQuantity from  `storage`  WHERE ICode = '$icodearray[$n]'";
			$result = mysqli_query($conn,$sqlcheck);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			if($iquantityarray[$n]>$row['TQuantity']){
				//do something if require quantity larger then storage , maybe cancel the order
			}else{}
			
		}
		*/
		//assume quantity pass ,create oid
		// $createoid="SELECT count(id) from order WHERE CID = $cid";
		// $result = mysqli_query($conn,$sqlcheck);
		// $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		//$oid1=mysql_result(mysql_query("SELECT count(id) from `order` WHERE CID = '$cid';"),0);
		$result = mysqli_query($conn,"SELECT count(id) as num from `order` WHERE CID = '$cid';");
		$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$oid1=$rowinfo['num'];
		$oid=$cid."-".$oid1."=";
		$datereceive=date("Y-m-d H:i:s");
		$statue="new";
		//statue={new,pending,failed,approve,reject}
		$insertorder="INSERT INTO `order`( OID, CID, ItemNumber, Location, DateReceive, DeliveryDate, DeliveryType, PaymentMethod, Pay_ok, OProcess, Statue) VALUES 
					('$oid','$cid','$itemnumber','$location','$datereceive','$deliverydate','$deliverytype','$paymentmethod','$payok','$oprocess','$statue')";
					
		if ($conn->query($insertorder) === TRUE) {
			//ok next register multiple item
			$oidd=$oid.$itemnumber;
			for($n=0;$n<$itemnumber;$n++){
				$i=0;
				$tried=0;
				
				echo "n=".$n.'<br>';
				while($i==0){
					$insertitemsql="INSERT INTO orderitem(OIDD, OID, ICode, Quantity) 
					VALUES ('$oidd','$oid','$icodearray[$n]','$iquantityarray[$n]')";
					echo "i=".$i.'<br>';
					if($conn->query($insertitemsql) === TRUE){
						$i=1;
						echo "i2=".$i.'<br>';
					}else{
						$i=0;
						echo "error $n"."<br>";
						$tried++;
						if($tried>=3){
							$i=1;
							
							//what to do after 3 tried
						}
						//reinsert
					}
				}
				//success insert n[$n]
			}
			if($tried<3){
				//all success inserted,so we update order sql statue to pending
				$statue="pending";
				$updatestatueorder="UPDATE storagev1.order
										SET Statue = '$statue'
										WHERE  OID = '$oid'
										";
				if($conn->query($updatestatueorder) === TRUE){
					//update success
					//**redirect page??return something to html, php end here
				}
			}else{
				//$tried exceed 2 mean insert not complete, do someting like update order sql statle to failed? where oid=oid
			}
		}else{
			//insert new order failed
		}
	}
	
	/*-------------------------------------------
	input
	6	$cid
	7	$deliverydate
	8	$deliverytype
	9	$location
	11	$icodearray
	12	$iquantityarray
	15	$itemnumber
	
	output
	
	*/
?>