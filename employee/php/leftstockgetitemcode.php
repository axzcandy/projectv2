<?php
	include('sqlconnstorage.php');

	//**check id valid here
	if(checkUserLoginValid()==true){
		if(isset($_REQUEST["itype"])){
			$itype=$_REQUEST['itype'];
			$sql="";
			if($itype=="ALL"){
				$sql="SELECT * FROM storagev1.item";
			}else{
				$sql="SELECT * FROM item WHERE IType='$itype'";
			}
			
			
			$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));	
			$emparray = array();
			while($row =mysqli_fetch_assoc($result))
			{
				$emparray[] = $row;
			}	
			echo json_encode($emparray);
		}else{
			//$_REQUEST["quantity"] null
		}
	}else{
		//failvaliduser
	}
	
?>