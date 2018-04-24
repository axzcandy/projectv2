<?php


	include('sqlconnstorage.php');

	if(checkUserLoginValid()==true){
		//manual approve
		//$id=$_REQUEST["id"];
		if(isset($_REQUEST["oid"])){
			$oid=$_REQUEST["oid"];
			$eid=$_SESSION['us'];
			
			$result = (mysqli_query($conn,"SELECT * FROM `order` WHERE OID = '$oid'"));
			$rowinfo = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$transporttype=$rowinfo['DeliveryType'];
			
			$eidassign=$_REQUEST["eid"];
			$statue="reject";
			$datereceive=date("Y-m-d");
			
			$insertapprove="INSERT INTO orderapproved( OID, ApprovedByEID, DateApproved, TransportType, AssignToEID) 
							VALUES ('$oid','$eid','$datereceive','$transporttype','$eidassign')";
			if ($conn->query($insertapprove) === TRUE) {
				//update order sql statue
				$updateorder="UPDATE storagev1.order
											SET Statue = '$statue'
											WHERE  OID = '$oid'";
				if ($conn->query($updateorder) === TRUE) {
					//done 
					//**redirect page? return something back to client
					echo json_encode ('success');
					header("Refresh: 1;URL= /projectv2/employee/html/order.html");
				}
				else{
					//echo mysqli_error($conn).'<br>';
					//echo 'fa1';
				}
			}else{
				//echo mysqli_error($conn).'<br>';
				//echo 'fa2';
			}
		}
	}
?>