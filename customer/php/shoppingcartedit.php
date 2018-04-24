<?php
	include('sqlconnstorage.php');
	
	if(isset($_REQUEST['cookieid'])){
		$cookieid=$_REQUEST['cookieid'];
		$icode=$_REQUEST['itemname'];
		//$ip =$_SERVER["HTTP_X_REAL_IP"];
		$cookieid=$_REQUEST['cookieid'];
		$sql="SELECT * FROM anonymouscart WHERE UniqueCookieID = '$cookieid'";
		$result = mysqli_query($conn,$sql);
		$count = mysqli_num_rows($result);
		
		if($count==1){
			
			if($icode=="clearall"){
				//remove all
				$sql="DELETE FROM anonymouscart WHERE UniqueCookieID='$cookieid'";
				if($conn->query($sql) === TRUE){
					echo json_encode("success remove data");
				}else{
					echo json_encode("error remove data");
				}
			}else{
				//edit 1 item
				if(isset($_REQUEST['newshoppingcart'])){
					//echo json_encode("icode=".$icode."-id=".$cookieid);
					$newshoppingcart=$_REQUEST['newshoppingcart'];
					$str=serialize($newshoppingcart);
					
					$sql="UPDATE storagev1.anonymouscart SET 2DArray='$str' WHERE UniqueCookieID='$cookieid'";
					if($conn->query($sql) === TRUE){
						//update success
						echo json_encode("success");
					}else{
						//update failed
						echo json_encode("update failed");
					}
				}else{
					//newshoppingcart is null
				}
			}
			
			//echo json_encode($cookieid."-".$icode);
		}else{
			//no id match return something to front call function to create new
			//echo json_encode("noidfound");
			
			if(isset($_REQUEST['newshoppingcart'])){
				$cookieid=$_REQUEST['cookieid'];
				$newshoppingcart=$_REQUEST['newshoppingcart'];
				$str=serialize($newshoppingcart);
				$sql="INSERT INTO anonymouscart(UniqueCookieID, 2DArray) VALUES ('$cookieid','$str')";
				if($conn->query($sql) === TRUE){
					echo json_encode("createnew");
				}
			}else{
				//noitemvalue
			}
		}
	}else{
		//isset($_REQUEST['cookieid'])==false
	}
?>