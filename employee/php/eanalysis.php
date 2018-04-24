<?php
	include('sqlconnstorage.php');
	if(checkUserLoginValid()==true)//checkUserLoginValid()==true
	{

		if(isset($_REQUEST['what'])){
			if($_REQUEST['what']=="top"){
				$sql="SELECT IName AS name,ICode AS names,(SUM( Price )-
				(SELECT SUM( Price ) FROM inandout
				WHERE Purpose =  'in' AND ICode = names)) AS y
				FROM inandout WHERE Purpose IN ( 'out',  'order' )
				GROUP BY ICode ORDER BY y LIMIT 0 , 30";
				
				$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
					
				if (mysqli_num_rows($result)==0) {
				   
				}else{
					while($row =mysqli_fetch_assoc($result))
					{
						$emparray[] = $row;
					}
				}
				
				echo json_encode($emparray);
				
				
			}else if($_REQUEST['what']=="single"){
				
				$icode=$_REQUEST['icode'];
				$sql="SELECT YEAR( DateCreate ) AS name,IName AS nn, (SELECT SUM( Price ) 
					FROM inandout
					WHERE YEAR( DateCreate ) = name
					AND ICode =  '$icode'
					AND Purpose
					IN ('out',  'order')) - (
					SELECT SUM( Price ) 
					FROM inandout
					WHERE YEAR( DateCreate ) = name
					AND ICode =  '$icode'
					AND Purpose =  'in'
					) AS y
					FROM inandout
					WHERE ICode =  '$icode'
					GROUP BY name
					LIMIT 0 , 30";
				
				$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
					
				if (mysqli_num_rows($result)==0) {
					$emparray[] = "empty";
					
				}else{
					while($row =mysqli_fetch_assoc($result))
					{
						$emparray[] = $row;
					}
				}
				echo json_encode($emparray);
				
				
			}else if($_REQUEST['what']=="year"){
				$years=(int)$_REQUEST['sdf'];
				
				$sql="SELECT YEAR(DateCreate) AS yy,IName AS name,ICode AS names,(SUM( Price )-
				(SELECT SUM( Price ) FROM inandout
				WHERE Purpose ='in' AND YEAR(DateCreate)='$years' AND ICode = names)) AS y
				FROM inandout WHERE Purpose IN ( 'out',  'order' ) AND YEAR(DateCreate)='$years'
				GROUP BY ICode ORDER BY y LIMIT 0 , 30";
					
				$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
					
				if (mysqli_num_rows($result)==0) {
					echo json_encode("empty");
				}else{
					while($row =mysqli_fetch_assoc($result))
					{
						$emparray[] = $row;
					}
				}
				echo json_encode($emparray);
			}else if($_REQUEST['what']=="getyear"){
				$sql="SELECT DISTINCT(YEAR(DateCreate)) AS yy FROM inandout WHERE 1";
				$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
					
				if (mysqli_num_rows($result)==0) {
					echo json_encode("empty");
				}else{
					while($row =mysqli_fetch_assoc($result))
					{
						$emparray[] = $row;
					}
				}
				echo json_encode($emparray);
				
			}else if($_REQUEST['what']=="cstop"){
				$sql="SELECT order.CID AS name,order.OID as func,(SELECT SUM(orderitem.Price) FROM orderitem WHERE orderitem.OID=func) AS y FROM
				`order` join orderitem on order.OID=orderitem.OID GROUP BY name LIMIT 5";
				
				$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
					
				if (mysqli_num_rows($result)==0) {
					$emparray[] = "empty";
					
				}else{
					while($row =mysqli_fetch_assoc($result))
					{
						$emparray[] = $row;
					}
				}
				echo json_encode($emparray);
			}
		}
	}else{
		//login invalid
	}

?>