<?php
	include('sqlconnstorage.php');
	
	if(checkCustomerLoginValid()==true){
		// checkCustomerLoginValid()==true
		
		$cid = $_SESSION['cs'];
		// $cid = 'cid111';
		$deliverydate = $_REQUEST["deliverydates"];
		$deliverytype = $_REQUEST["deliverytypes"];
		// $deliveryaddress = $_REQUEST["deliveryaddress"];
		$location = $_REQUEST['deliveryaddresss'];
		
		// $deliverydate = '2017-12-01 00:00:00';
		// $datereceive = '2017-12-01 00:00:00'; //test
		// $deliverytype = '123';
		// $deliveryaddress = 'test';
		// $location = 'here';
		
		$paymentmethod = '';  // 繳費方式
		$payok = 0;  // 訂單是否已經繳費
		$oprocess = '';
		
		//echo $_REQUEST["itemcodearray"];
		$itemcodearray = json_decode($_REQUEST["itemcodearray"],true);
		$itemquantityarray = json_decode($_REQUEST["itemquantityarray"],true);
		$itemnumber = count($itemcodearray);
		//echo ($itemcodearray);
		//print_r($itemquantityarray);
		$sql = "select COUNT(id) as ct from `order` WHERE CID = '$cid'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$ct = $row['ct'];
		$oid=$cid."-".$ct."_";
		$statue = 'new';
		$purpose = 'order';
		$eid = 'SYS0000';
		//print_r($itemcodearray);
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
					
			$icode="".$itemcodearray[$i];
			$iquantity=(int)$itemquantityarray[$i];
		
			$sql = "select SUM(LQuantity) as totlquantity from itemstack where Icode ='$icode' AND ExDate > '$deliverdateplusminnum'";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$totlquantity = $row['totlquantity'];
			//echo $totlquantity."--".$iquantity;
			if(((int)$totlquantity - (int)$iquantity) < 0){
				$ss=0;
				
				break;
			}
		}
			//echo	$ss;
			if($ss==1){
				$currentdate=date("y-m-d");
				$insertordersql = "INSERT into `order` (OID,CID,ItemNumber,Location,DateReceive,DeliveryDate,DeliveryType,PaymentMethod,Pay_ok,OProcess,Statue) VALUES 
						('$oid','$cid','$itemnumber','$location','$currentdate','$deliverydate','$deliverytype','$paymentmethod','$payok','$oprocess','$statue')";
						
				if ($conn->query($insertordersql) === TRUE) {

				
					for($i=0;$i<$itemnumber;$i++){
						
						$icode=$itemcodearray[$i];
						$iquantity=$itemquantityarray[$i];
						
						//echo $icode.'+'.$iquantity.'<br>';
						$sql = "SELECT Price as price from storage where ICode = '$icode'";
						$result = mysqli_query($conn,$sql);
						$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
						$iprice = $row['price'];
						$iprice = (int)$iprice * (int)$iquantity;
						$oidd=$oid."$i";
						
						$insertorderitemsql = "INSERT INTO orderitem(OIDD, OID, ICode, Quantity, Price, DeliveryDate) VALUES
								('$oidd','$oid','$icode','$iquantity','$iprice','$deliverydate')";
						if ($conn->query($insertorderitemsql) === TRUE) {
							$sql = "select * from item where ICode ='$icode'";
							$result = mysqli_query($conn,$sql);
							$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
							$iname = $row['IName'];
							$iType = $row['IType'];
							
							$crdate=date('Y-m-d H:i:s');
							
							
							
							$insertinandoutsql = "INSERT INTO inandout(ICode, IName, IType, Price, Quantity, QuantityType, Serial, Statue, SID, EID, StockReceive, StockDeliver, Purpose, OID,DateCreate) 
							VALUES ('$icode','$iname','$iType','$iprice','$iquantity','$empty','$empty','$statue','$empty','$eid','$empty','$deliverydate','$purpose','$oid','$crdate')";
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
									
									$updateinandout ="UPDATE storagev1.inandout
										set Statue = 'updated'
										WHERE id = '$q'";
										  
										if ($conn->query($updateinandout) === TRUE) {
											//echo '$conn->query($updateinandout success';
											//for last stage
										}else{
											//echo '$conn->query($updateinandout) === false<br>';
											//echo mysqli_error($conn);
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
						//echo 'updateorderstatuesuccess';
						//end here
						$str="";$cookieid=$_REQUEST['cookieid'];
						$sql="UPDATE storagev1.anonymouscart SET 2DArray='$str' WHERE UniqueCookieID='$cookieid'";
						if($conn->query($sql) === TRUE){
							echo "success...redirecting";
							header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
						}
						else{
							echo "success w ...redirecting";
							header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
						}
					}else{
						//echo '$conn->query($updateorderstatue) === false';
					}
				}else{
					//echo '$conn->query($insertordersql) === false<br>';
					//echo mysqli_error($conn);
				}
				
			}else if($ss==0){
				//LQuantity - iquantity <0
				$currentdate=date("y-m-d");
				$statue="updated";
				$insertordersql = "INSERT into `order` (OID,CID,ItemNumber,Location,DateReceive,DeliveryDate,DeliveryType,PaymentMethod,Pay_ok,OProcess,Statue) VALUES 
						('$oid','$cid','$itemnumber','$location','$currentdate','$deliverydate','$deliverytype','$paymentmethod','$payok','$oprocess','$statue')";
					
				if ($conn->query($insertordersql) === TRUE) {
					$s=0;
					for($i=0;$i<$itemnumber;$i++){
						
						$icode=$itemcodearray[$i];
						$iquantity=$itemquantityarray[$i];
						
						//echo $icode.'+'.$iquantity.'<br>';
						$sql = "SELECT Price as price from storage where ICode = '$icode'";
						$result = mysqli_query($conn,$sql);
						$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
						$iprice = $row['price'];
						
						$oidd=$oid."$i";
						
						$insertorderitemsql = "INSERT INTO orderitem(OIDD, OID, ICode, Quantity, Price, DeliveryDate) VALUES
								('$oidd','$oid','$icode','$iquantity','$iprice','$deliverydate')";
						if ($conn->query($insertorderitemsql) === TRUE) {
							//echo 'else success';
							
						}else{
							//echo 'elsefailed1';
							//echo mysqli_error($conn);
							$s=1;
						}
					}//for end
					if($s==0){
						$sql="UPDATE storagev1.anonymouscart SET 2DArray='$str' WHERE UniqueCookieID='$cookieid'";
						if($conn->query($sql) === TRUE){
							echo "order added for approve ... Redirecting";
							header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
						}
						else{
							echo "order added for approve W ... Redirecting";
							header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
						}
					}else{
						echo "order added with error ... Redirecting";
						header("Refresh: 1;URL= /projectv2/customer/html/csmainpage.html");
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