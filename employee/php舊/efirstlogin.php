<?php
	include('sqlconnstorage.php');

	if(checkUserLoginValid()==true){
		
		// 如果雙密碼都有存在的話
		if((isset($_REQUEST['password'])) && (isset($_REQUEST['password']))){
		
		// 如果雙密碼都相同的話
			if($_REQUEST['password'] == $_REQUEST['password1']){
				$eid = $_SESSION['us'];

				// 去sql employee找尋欄位資料
				$sql = "select * from employeeidpass where EID = '$eid'";
				$result = mysqli_query($conn,$sql);
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

				// if(!isset($_REQUEST['euser1'])){ // 如果帳號沒有輸入的話,帳號會為sql預設值,有輸入的話則為輸入的內容
					$euser1 = $row['EUser'];
				// }else{
					// $euser1 = $_REQUEST['euser1'];
				// }
				
				if(!isset($_REQUEST['password'])){ // 如果密碼沒有輸入的話,密碼會為sql預設值,有輸入的話則為輸入的內容
					$epass1 = $row['EPass'];
				}else{
					$epass1 = $_REQUEST['password'];
				}
				
				$md5epass = md5($epass1);
				
				$agreement = $_REQUEST['agreement'];
				
				
				// update employeeidpass
				$sql = "UPDATE employeeidpass SET EPass = '$md5epass' WHERE EID = '$eid'";
				if ($conn->query($sql) === TRUE) {
					//echo 'Successfully!!';
					
					//update employee
					// $sql = "UPDATE employee SET EUser = '$euser1' WHERE EID = '$eid'";
					// if ($conn->query($sql) === TRUE) {
						
						// update ereg
						$sql = "UPDATE ereg SET EPass = '$md5epass' WHERE EID = '$eid'";
						if ($conn->query($sql) === TRUE) {
							header("Refresh: 1;URL= /projectv2/employee/html/accountmanage.html");
							// echo 'Successfully!!';
						}
					// }
					
				}else{
					// echo mysqli_error($conn);
					//echo 'fail<br>123'.$euser.$epass;
				}
			}else{
				// echo "password false'";
				header("Refresh: 1;URL= /projectv2/employee/html/efirstlogin.html");
			}
		}
	}