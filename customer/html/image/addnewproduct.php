<?php
	include('sqlconnstorage.php');

	if(1){// checkUserLoginValid()==true
		// $iname = $_REQUEST['iname'];
		// $itype = $_REQUEST['itype'];
		// $iquantitytype = $_REQUEST['iquantitytype'];
		// $picture = $_REQUEST['picture'];
		// $maxquantity = $_REQUEST['maxquantity'];
		// $maxprice = $_REQUEST['maxprice'];
		// $maxquantityvalue = $_REQUEST['maxquantityvalue'];
		// $maxpricevalue = $_REQUEST['maxpricevalue'];
		// $remark = $_REQUEST['remark'];
		
		$iname = 'test1';
		$itype = 'g';
		$iquantitytype = '123';
		// $picture = $_REQUEST['picture'];
		$maxquantity = '789';
		$maxprice = '345';
		$maxquantityvalue = '89';
		$maxpricevalue = '123';
		$remark = 'good';
		
		$empty="";
		
		$sql = "select count(IType) as ity from item where IType = '$itype'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$searchcountitype = $row['ity'];
		
		$icode = 'I'.$itype.$searchcountitype;

		$target_dir = "C:\wamp\www\projectv2\pictures\item\\";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		
		// Allow certain file formats
		if($imageFileType == "jpg"){$t = ".jpg";}
		else if($imageFileType == "png" ){$t = ".png";}
		else if($imageFileType == "jpeg"){$t = ".jpeg";}
		else if($imageFileType == "gif"){$t = ".gif";}
		else{
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		$target_file1 = $target_dir .  $icode . $t;
		$picturnname= $icode . $t;
		echo "<br>".$target_file1;
		echo "<br>".$_FILES["fileToUpload"]["tmp_name"];
		// Check if file already exists
		if (file_exists($target_file1)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file1)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				// 測試用區域
				
				// 測試用區域
				
				//insert sql
				// 應該是這一段有更動
				$picturename = '\projectv2\pictures\item\\'.$picturnname;
				$pictures = mysqli_real_escape_string($conn,$picturename);
				$sql = "INSERT INTO item(ICode, IName, IType, IQuantityType, Remark, Picture) VALUES 
										('$icode','$iname','$itype','$iquantitytype','$remark','$pictures')";
				// 以上
				if ($conn->query($sql) === TRUE) {
					// success
					if($maxquantity > 0){
						$configname="conf_maxquantity_".$icode;
						$statue="activated";
						$detail = "Max Quantity allow for " . $icode;
						$sql="INSERT INTO 'config'(ConfigName, CharPR1N, CharPara1, CharPR2N, CharPara2, IntPR1N, IntPara1, IntPR2N, IntPara2, Statue, Detail) VALUES
												  ('$configname','$empty','$empty','$empty','$empty','g','$maxquantity','$empty','$empty','$statue','$detail')";
						if ($conn->query($sql) === TRUE) {
							$configname="conf_maxprice_".$icode;
							$statue="activated";
							$detail = "Max Price allow for " . $icode;
							$sql="INSERT INTO 'config'(ConfigName, CharPR1N, CharPara1, CharPR2N, CharPara2, IntPR1N, IntPara1, IntPR2N, IntPara2, Statue, Detail) VALUES
													  ('$configname','$empty','$empty','$empty','$empty','NT','$maxprice','$empty','$empty','$statue','$detail')";
							if ($conn->query($sql) === TRUE) {
								// insert into config success maxprice
								echo 'all success';

							}else{
								// insert into config fail maxprice
							}

						}else{
							$configname="conf_maxprice_".$icode;
							$statue="deactivated";
							$detail = "Max Price allow for " . $icode;
							$sql="INSERT INTO 'config'(ConfigName, CharPR1N, CharPara1, CharPR2N, CharPara2, IntPR1N, IntPara1, IntPR2N, IntPara2, Statue, Detail) VALUES
													  ('$configname','$empty','$empty','$empty','$empty','NT','$maxprice','$empty','$empty','$statue','$detail')";
							if ($conn->query($sql) === TRUE) {
								// insert into config success maxprice
							}else{
								// insert into config failed maxprice
							}
							
							
						}
					}else{
						$configname="conf_maxquantity_".$icode;
						$statue="deactivated";
						$detail = "Max Quantity allow for " . $icode;
						$sql="INSERT INTO 'config'(ConfigName, CharPR1N, CharPara1, CharPR2N, CharPara2, IntPR1N, IntPara1, IntPR2N, IntPara2, Statue, Detail) VALUES
												  ('$configname','$empty','$empty','$empty','$empty','g','$maxquantity','$empty','$empty','$statue','$detail')";
						if ($conn->query($sql) === TRUE) {
							// insert into config success maxquantity <= 0
						}else{
							// insert into config failed maxquantity <= 0
						}
					}
					
					
					
					
					
					
					
					// header
				}else{
					echo 'insert item failed';
				}
				
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}

	}
		
?>