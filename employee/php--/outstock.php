<?php
	include('sqlconnstorage.php');

	//**check id valid here
if(checkUserLoginValid()==true){
	$icode=$_REQUEST["icode"];
	$quantity=$_REQUEST["quantity"];
	$eid=$_REQUEST["eid"];
	$edate=$_REQUEST["edate"];
	$price=$_REQUEST["price"]; //需更改，去sql的storage裡撈資料
	//$oid=$_REQUEST["oid"]; 
	$statue="new";
	$datedelivery=$_REQUEST["datedelivery"];
	$purpose="out";
	// $icode='17';
	// $quantity='103';
	// $eid='test';
	// $oid='test1'; 
	// $statue="new";
	// $datedelivery='2017-12-10 16:33:59';
	// $purpose="out";
	// $oid=$_REQUEST["oid"];
	
	$infosql="SELECT * from item WHERE ICode = '$icode'";
	$result = mysqli_query($conn,$infosql);
	$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
	echo mysqli_error($conn);
	$iname= $rowinfo['IName'];
	$itype= $rowinfo['IType'];
	$quantitytype= $rowinfo['IQuantityType'];
	$empty="";
	
	$sql = "select TQuantity from storage where ICode = '$icode'";
	$result1 = mysqli_query($conn,$sql);
	$rowinfo1 = mysqli_fetch_array($result1,MYSQLI_ASSOC);
	$checkquantity = $rowinfo1['TQuantity'];
	
	if($checkquantity >= $quantity){//$checkquantity >= $quantity
	
		$outstocksql="INSERT INTO inandout(ICode,IName,IType,Quantity,QuantityType,Serial,Statue,SID,EID,StockReceive,StockDeliver,ExDate,Purpose,OID,Price) 
		VALUES ('$icode','$iname','$itype','$quantity','$quantitytype','$empty','$statue','$empty','$eid','$empty','$datedelivery','$edate','$purpose','$empty','$price')";

		if ($conn->query($outstocksql) === TRUE) {//$conn->query($outstocksql
			//succes to insert into sql.Next update storage TQuantity
			$q = $conn->insert_id;
			// $turn="SELECT MIN(id) as er FROM stockout WHERE Statue = 'new'";
			// $result = mysqli_query($conn,$turn);
			// $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			// while($q!= $row['er']){
				// $turn="SELECT MIN(id) as er FROM stockout WHERE Statue = 'new'";
				// $result = mysqli_query($conn,$turn);
				// $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
				
				// pause for 1 sec before check again
				// sleep(1);
			// }
			$sqlconf="select * from config where ConfigName = 'conf_exdate_itemout';";
			$results = mysqli_query($conn,$sqlconf);
			$rows = mysqli_fetch_array($results,MYSQLI_ASSOC);
			
			$minnum = $rows['IntPara1'];
			//$format = $row['IntPR1N'];
			
			$storagesql="UPDATE storagev1.storage 
										SET TQuantity = TQuantity-$quantity
										WHERE  ICode = '$icode'";
								
				//**TQuantity is not int , prob havent solve
				if ($conn->query($storagesql) === TRUE) {
					//statue success updated
					while($quantity!=0){//$quantity!=0
						
						$sql = "select * from itemstack where ExDate=(select Min(ExDate) from itemstack where LQuantity > 0 and ICode = '$icode')";
						$results1 = mysqli_query($conn,$sql);
						$rows1 = mysqli_fetch_array($results1,MYSQLI_ASSOC);
						
						$serial = $rows1['Serial'];
						$lq = $rows1['LQuantity'];
						$sum = $lq - $quantity;
						if($sum < 0){
							$sql ="select MIN(ExDate) as mind from itemstack where
									LQuantity > 0 and Icode = '$icode'";
							$result = mysqli_query($conn,$sql);
							$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
							$mindate = $row['mind'];
							
							$sql = "update storagev1.itemstack set LQuantity = LQuantity - $lq
									where ExDate = '$mindate'";

							if ($conn->query($sql) === TRUE) {
								$quantity = ABS($sum);
								echo 'succ'.$quantity;
							}else{
								echo 'update failed111' . $sum;
							}
						}else{
							$sql = "select MIN(ExDate) as min from itemstack where
													LQuantity > 0 and ICode = '$icode'";
							$result = mysqli_query($conn,$sql);
							$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
							$mindate1 = $row['min'];
							
							$sql = "update storagev1.itemstack set LQuantity = LQuantity - $quantity 
									where ExDate = '$mindate1'";
								if ($conn->query($sql) === TRUE) {
									$quantity= ABS($sum);
								}else{
									echo 'update failed';
								}
						}
					
						$statue="updated";
						$sqlupdatestatue="UPDATE storagev1.inandout
											SET Statue = '$statue'
											WHERE  id = '$q'
											AND ICode = '$icode'
											AND Quantity ='$quantity'";
						
						if ($conn->query($sqlupdatestatue) === TRUE) {
							// success update statue
							// **redirect page??
							echo 'success update statue';
							header("Refresh: 1;URL= /projectv2/employee/html/storageinform.html");
							
						}else{
							// failed to update statue
							echo 'failed to update statue';
						}
						$quantity=0;
				}
			}else{
				//failed to update TQuantity $storagesql
				echo 'failed to update TQuantity $storagesql';
			}
			
	}else{
		//fail to insert $outstocksql
		echo 'fail';
		echo mysqli_error($conn);
	}
	}else{
		echo 'Quantity false';
	}
}

/*----------------------------------------------
	input
	
	6	$icode
	7	$quantity
	8	$eid
	9	$oid
	11	$datedelivery
	13	$oid
	
	output
	
*/
?>