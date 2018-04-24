<?php
    include('sqlconnstorage.php');

	if(checkUserLoginValid()==true){
		//manual approve
		//$id=$_REQUEST["id"];
		$oid=$_REQUEST["oid"];
		$eid=$_REQUEST["eid"];
		$transporttype=$_REQUEST["transporttype"];
		$eidassign=$_REQUEST["eidassign"];
		$statue="approved";
		$datereceive=date("Y-m-d H:i:s");
		
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
			}
			//else {echo mysqli_error($conn).'<br>';
				//echo 'fa1';}
		}//else{echo mysqli_error($conn).'<br>';
			//echo 'fa2';}
		
		
		
		//system approve
		function approveorder(){
			//$oid=;
			//eid = system eid
			//$eid="sys0000";
			//$eidassign="sys0000";
			//$transporttype="213";
			$statue="approved";
			$datereceive=date("Y-m-d H:i:s");
			$statue="approve";
			
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
					header("Refresh: 1;URL= /projectv2/employee/html/order.html");
				}//else
				//{echo 'fa3';}
			
			}
			//else{echo 'fa4';}
		}
	}
	/*--------------------------------
	input
		7	$oid
		8	$eid
		9	$transporttype
		10	$eidassign
		
	output
	
	*/
?>