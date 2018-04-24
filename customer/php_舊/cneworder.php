<?php
	include('sqlconnstorage.php');
	
	if(checkCustomerLoginValid()==true){
		// checkCustomerLoginValid()==true
		//$cid = 'cid111';
		$cid = $_SESSION['cs'];
		$deliverydate = $_REQUEST["deliverydate"];
		$deliverytype = $_REQUEST["deliverytype"];
		$deliveryaddress = $_REQUEST["deliveryaddress"];
		$location = $_REQUEST['location'];
		
		// $deliverydate = '2017-12-01 00:00:00';
		// $datereceive = '2017-12-01 00:00:00'; //test
		// $deliverytype = '123';
		// $deliveryaddress = 'test';
		// $location = 'here';
		
		$paymentmethod = 'creditcard';  // 繳費方式
		$payok = 0;  // 訂單是否已經繳費
		$oprocess = 'truck';  // 取貨方式
		// $itemcodearray = $a;
		// $itemquantityarray = $b;
		// $itemnumber = count($b);
		$itemcodearray = $_REQUEST["itemcodearray"];
		$itemquantityarray = $_REQUEST["itemquantityarray"];
		$itemnumber = count($_REQUEST['itemquantityarray']);
		echo $itemcodearray[0]."<br>";
		echo $itemquantityarray[0]."<br>";
		$sql = "select COUNT(id) as ct from `order` WHERE CID = '$cid'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$ct = $row['ct'];
		$oid=$cid."-".$ct."_";
		$statue = 'new';
		$purpose = 'order';
		$eid = 'SYS0000';
		 
		$empty="";	
		$ss=1;
		
		$sqlconf="select * from config where ConfigName = 'conf_exdate_itemout';";
							$results = mysqli_query($conn,$sqlconf);
							$rows = mysqli_fetch_array($results,MYSQLI_ASSOC);
							$minnum = $rows['IntPara1'];
		
		$sql = "select DATE_ADD('$deliverydate', INTERVAL $minnum DAY) as datet";
								$result = mysqli_query($conn,$sql);
								$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
								$deliverdateplusminnum = $row['datet'];
		//echo $deliverdateplusminnum."<br>";
		for($i=0;$i<$itemnumber;$i++){
					
			$icode=$itemcodearray[$i];
			$iquantity=$itemquantityarray[$i];
		
			$sql = "select SUM(LQuantity) as totlquantity from itemstack where Icode ='$icode' AND ExDate > '$deliverdateplusminnum'";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$totlquantity = $row['totlquantity'];
			if(($totlquantity - $iquantity) < 0){
				$ss=0;
				break;
			}
		}
			//echo	$ss;
			if($ss==1){
			
				$insertordersql = "INSERT into `order` (OID,CID,ItemNumber,Location,DeliveryDate,DeliveryType,PaymentMethod,Pay_ok,OProcess,Statue) VALUES 
						('$oid','$cid','$itemnumber','$location','$deliverydate','$deliverytype','$paymentmethod','$payok','$oprocess','$statue')";
						
				if ($conn->query($insertordersql) === TRUE) {

				
					for($i=0;$i<$itemnumber;$i++){
						
						$icode=$itemcodearray[$i];
						$iquantity=$itemquantityarray[$i];
						
						//echo $icode.'+'.$iquantity.'<br>';
						$sql = "SELECT Price as price from storage where ICode = '$icode'";
						$result = mysqli_query($conn,$sql);
						$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
						$iprice = $row['price'];
						$oidd=$oid."$i";
						
						$insertorderitemsql = "INSERT INTO orderitem(OIDD, OID, ICode, Quantity, Price, Statue, DeliveryDate) VALUES
								('$oidd','$oid','$icode','$iquantity','$iprice','$statue','$deliverydate')";
						if ($conn->query($insertorderitemsql) === TRUE) {
							$sql = "select * from item where ICode ='$icode'";
							$result = mysqli_query($conn,$sql);
							$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
							$iname = $row['IName'];
							$iType = $row['IType'];
							
							
							
							
							
							$insertinandoutsql = "INSERT INTO inandout(ICode, IName, IType, Price, Quantity, QuantityType, Serial, Statue, SID, EID, StockReceive, StockDeliver, Purpose, OID) 
							VALUES ('$icode','$iname','$iType','$iprice','$iquantity','$empty','$empty','$statue','$empty','$eid','$empty','$deliverydate','$purpose','$oid')";
							if ($conn->query($insertinandoutsql) === TRUE) {
								$q = $conn->insert_id;
								
								
								
								$updatestoragesql = "UPDATE storagev1.storage 
													 SET TQuantity = TQuantity - '$iquantity'
													 WHERE ICode = '$icode'";

								if ($conn->query($updatestoragesql) === TRUE) {
									$o=0;
									while($iquantity!=0){
										
										$sql="select * from itemstack where ExDate= 
										(select Min(ExDate) from itemstack where LQuantity > '0' 
											and ICode = '$icode' AND ExDate > '$deliverdateplusminnum') 
										AND ICode ='$icode' AND LQuantity > '0' limit 1";
									
										$results1 = mysqli_query($conn,$sql);
										$rows1 = mysqli_fetch_array($results1,MYSQLI_ASSOC);
										
										$serial = $rows1['Serial'];
										//echo $serial.'+'.'$i';
										$lq = $rows1['LQuantity'];
										$sum = $lq - $iquantity;
										//echo $serial.'+'.$sum.'<br>';
										if($sum < 0){
											$sql1 = "update storagev1.itemstack set LQuantity = LQuantity - $lq
													where `Serial` = '$serial' ";
											if ($conn->query($sql1) === TRUE) {
												$iquantity = ABS($sum);
												//echo 'succ'.$quantity;
											}else{
												//echo mysqli_error($conn).'<br>';
												//echo 'update failed82<br>';
												break;
											}
										}else{
											$sql = "update storagev1.itemstack set LQuantity = LQuantity - $iquantity 
													where Serial = '$serial' AND ExDate > '$deliverdateplusminnum'";
												if ($conn->query($sql) === TRUE) {
													$iquantity= 0;
												}else{
													//echo 'update failed';
												}
										}
										$o+=1;
										if($o>3){
											//echo 'faildddddd';
											break;
										}
										
									}//while end here
									$updateorderitem = "UPDATE storagev1.orderitem
										set Statue = 'updated'
										WHERE OIDD = '$oidd'";	
									if ($conn->query($updateorderitem) === TRUE) {
										$updateinandout ="UPDATE storagev1.inandout
										  set Statue = 'updated'
										  WHERE id = '$q'";
										  
										  if ($conn->query($updateinandout) === TRUE) {
											  //echo '$conn->query($updateinandout success';
										  }else{
											 // echo '$conn->query($updateinandout) === false<br>';
											 // echo mysqli_error($conn);
										  }
										  
									}else{
										//echo  mysqli_error($conn)."<br>";
										//echo '$conn->query($updateorderitem) === false';
									}
									
								}else{
									//echo '$conn->query($updatestoragesql) === false';
								}
							}else{
								//echo '$conn->query($insertinandoutsql) === false<br>';
								//echo mysqli_error($conn);
							}
							
						}else{
							//echo '$conn->query($insertorderitemsql) === false';
							//echo mysqli_error($conn);
						}
					}//for end here
					
					$updateorderstatue = "UPDATE storagev1.order
										  SET Statue = 'sapprove'
										  WHERE OID = '$oid'"; // sapprove = system approve
					if ($conn->query($updateorderstatue) === TRUE) {
						// end here success
						//echo 'success';
					}else{
						//echo '$conn->query($updateorderstatue) === false';
					}
				}else{
					//echo '$conn->query($insertordersql) === false<br>';
					//echo mysqli_error($conn);
				}
				
			}else{
				//LQuantity - iquantity <0
				$updateorderstatue1 = "UPDATE storagev1.order
										  SET Statue = 'update'
										  WHERE OID = '$oid'";
				if ($conn->query($updateorderstatue1) === TRUE) {
						// end here success
						header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
						// echo 'success';
						$updateorderitem = "UPDATE storagev1.orderitem
										set Statue = 'new'
										WHERE OIDD = '$oidd'";	
						if ($conn->query($updateorderitem) === TRUE) {
							//echo 'else success';
						}else{
							//echo 'else failed';
						}
						
					}else{
						//echo '$conn->query($updateorderstatue1) === false';
					}
			}
		
	}else{
		//echo 'checkCustomerLoginValid=false';
	}
	/*-------------------------------------------
	input
	7	$cid
	9	$deliverydate
	10	$deliverytype
	11	$deliveryaddress
	12	$location
	23	$itemcodearray[]
	24	$itemquantityarray[]
	25	$itemnumber
	
	*/
?>