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
				//remove 1 item
				if(isset(newshoppingcart)){
					//echo json_encode("icode=".$icode."-id=".$cookieid);
					$newshoppingcart=$_REQUEST['newshoppingcart'];
					$str=serialize(newshoppingcart);
					
					$sql="UPDATE storagev1.anonymouscart SET 2DArray='$newshoppingcart' WHERE UniqueCookieID='$cookieid'";
					if($conn->query($newshoppingcart) === TRUE){
						//update success
						echo json_encode("success");
					}else{
						//update failed
					}
				}else{
					//newshoppingcart is null
				}
			}
			
			//echo json_encode($cookieid."-".$icode);
		}else{
			//no id match return something to front call function to create new
			echo json_encode("noidfound");
			
		}
	}
?>