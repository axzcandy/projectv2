<?php
	include('sqlconnstorage.php');
	$icode=$_REQUEST["icode3"];
	
	if(checkUserLoginValid()==true){
		$sql = "SELECT SUBSTRING((select Picture from item where ICode = '$icode'), 34) as len";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$len = $row['len'];
		
		echo $len;
		$path = "pictures/".$len;
		if(is_file($path)){
			if($len != "nopicture.jpg"){
				unlink($path);
			}
				//------------------------------1
				if(!empty($_FILES["fileToUpload"]["tmp_name"])){
				$target_dir = "pictures/";
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				// Check if image file is a actual image or fake image
				
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					//echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					//echo "File is not an image.";
					$uploadOk = 0;
				}
				// Check file size
				if ($_FILES["fileToUpload"]["size"] > 500000) {
					//echo "Sorry, your file is too large.";
					$uploadOk = 0;
				}
				// Allow certain file formats
				if($imageFileType == "jpg"){$t = ".jpg";}
				else if($imageFileType == "png" ){$t = ".png";}
				else if($imageFileType == "jpeg"){$t = ".jpeg";}
				else if($imageFileType == "gif"){$t = ".gif";}
				else{
					//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 0;
				}
				$target_file1 = $target_dir .  $icode . $t;
				$picturnname= $icode . $t;
				//echo "<br>".$target_file1;
				//echo "<br>".$_FILES["fileToUpload"]["tmp_name"];
				// Check if file already exists
				if (file_exists($target_file1)) {
					//echo "Sorry, file already exists.";
					$uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					//echo "Sorry, your file was not uploaded.";
					$picturename = '\projectv2\employee\php\pictures\nopicture.jpg';
				// if everything is ok, try to upload file
				} else {
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file1)) {
						//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
						$picturename = '\projectv2\employee\php\pictures\\'.$picturnname;
						//$statue1 = "show";
					}else{
						//echo "(move_uploaded_file($_FILES[fileToUpload][tmp_name], $target_file1)) failed ";
						$picturename = '\projectv2\employee\php\pictures\nopicture.jpg';
					}
				}
			}else{
				$picturename = '\projectv2\employee\php\pictures\nopicture.jpg';
			}
				//--------------------------1
				$pictures = mysqli_real_escape_string($conn,$picturename);
				//---------------2
				
				$changepicture = "UPDATE `item` SET Picture='$pictures' WHERE ICode = '$icode'";
				if ($conn->query($changepicture) === TRUE) {
					$changepicture1 = "UPDATE `customerproductshow` SET Picture='$pictures' WHERE ICode = '$icode'";
					if ($conn->query($changepicture1) === TRUE) {
						echo "Change picture successfully!!";
						header("Refresh: 1;URL=/projectv2/employee/html/adminstorage.html");
					}
				}
				
				//---------------2
		}
		else{
			echo json_encode("No Picutre!!");
		}
		
	}
?>