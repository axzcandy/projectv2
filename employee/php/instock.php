<?php
	include('sqlconnstorage.php');

	//**check id valid here
	if(checkUserLoginValid()==true){//checkUserLoginValid()==true
	//value from client
	$icode= $_REQUEST["icode"];
	$quantity= $_REQUEST["quantity"];
	$sid= $_REQUEST["sid"];
	// $eid= $_REQUEST["eid"];
	$eid= $_SESSION['us'];
	$datein= $_REQUEST["datein"];
	$exdate= $_REQUEST["exdate"];
	$serial=$icode."-".$exdate."-".$sid;
	$i=0;
	
	do{
		$i++;
		$serial=$icode."-".$exdate."-".$sid.$i;
		$sql="SELECT * FROM itemstack WHERE Serial = '$serial'";
		$results1 = mysqli_query($conn,$sql);
		$rows1 = mysqli_fetch_array($results1,MYSQLI_ASSOC);
		@$checkserial = $rows1['Serial'];
		//echo $checkserial."<br>";
	}while($serial==$checkserial);
	
	$statue="new";
	$price=$_REQUEST['price'];
	$purpose="in";
	
	// $icode= '5';
	// $quantity= '1';
	// $sid= '10';
	// $eid= '20';
	// $datein= '2017-11-28 03:16:46';
	// $exdate= '2017-11-29 03:16:46';
	// $serial=$icode."-".$exdate."-".$sid;
	// $statue="new";
	// $price='1000';
	// $purpose="in";
	
	//get item detail from sql
	$infosql="SELECT * from item WHERE ICode = '$icode'";
	$result = mysqli_query($conn,$infosql);
	$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$iname= $rowinfo['IName'];
	$itype= $rowinfo['IType'];
	$quantitytype= $rowinfo['IQuantityType'];
	
	
	$q="";
	$empty="";
	
	$instocksql="INSERT INTO inandout( ICode, IName, IType, Quantity, QuantityType, Serial, Statue, SID, EID, StockReceive, StockDeliver,  ExDate, Purpose, OID, Price) 
	VALUES ('$icode','$iname','$itype','$quantity','$quantitytype','$serial','$statue','$sid','$eid','$datein','$empty','$exdate','$purpose','$empty','$price')";
	if ($conn->query($instocksql) === TRUE) {
		//insert success
		$q = $conn->insert_id;
		
			$sql="SELECT * FROM storage WHERE ICode = '$icode'";
			$result = mysqli_query($conn,$sql);
			$count = mysqli_num_rows($result);
			if($count == 1)
			{
		
				$storagesql="UPDATE storagev1.storage 
										SET TQuantity = TQuantity+$quantity
										WHERE  ICode = '$icode'";
			}else{
				$storagesql="INSERT INTO storage(ICode, IName, TQuantity, QuantityType, Price) 
				VALUES ('$icode','$iname','$quantity','$quantitytype','$price')";
			}		
			//echo $storagesql;
				if ($conn->query($storagesql) === TRUE) {
					//after success update storage total quantity
					$statue="updated";
					$sqlupdateatatue="UPDATE storagev1.inandout
										SET Statue = '$statue'
										WHERE  id = '$q'
										AND ICode = '$icode'
										AND Quantity ='$quantity'";
					
										
					if ($conn->query($sqlupdateatatue) === TRUE) {
						
						$sql = "INSERT INTO itemstack(Icode , Price , ExDate , LQuantity , Serial)
							VALUES ('$icode','$price','$exdate','$quantity','$serial')";
							
							if ($conn->query($sql) === TRUE) {
								//echo 'success';
								header("Refresh: 1;URL= /projectv2/employee/html/storageinform.html");
							}else{
								//echo 'failed';
							}
						//echo "<h2>"."Add Item Inform"."</h2>";
						//echo "icode = "."$icode" ."<br>" ."quantity = "."$quantity" ."<br>" ."sid = "."$sid" ."<br>" ."eid = "."$eid" ."<br>" ."datein = "."$datein" ."<br>" ."exdate = "."$exdate";
						
											
						
						
					}else{
						//failed to update statue sqlupdateatatue
						//echo mysqli_error($conn) ."1";
					}
				}else{
					//failed to update TQuantity storagesql
					//echo mysqli_error($conn) ."2";
				}
		
	}else{
		//fail to insert $instocksql
		//echo "instocksql";
	}
;
}else{
	//$qw=$_SESSION['tcode'];
	//$ew=$_SESSION['us'];
	
	
	
	//echo "login error"."-"."'$qw"."-"."$ew";
	//echo '2';
}
/*------------------------
	input
	
		7	$icode
		8	$quantity
		9	$sid
		10	$eid
		11	$datein
		12	$exdate
		15	$price=$_REQUEST['price'];
		
	output
*/
?>