<?php
	include('sqlconnstorage.php');
	
	if(checkUserLoginValid()==true){// checkUserLoginValid()==true
		
		$iname = $_REQUEST['iname'];
		$itype = $_REQUEST['itype'];
		$iquantitytype = $_REQUEST['iquantitytype'];
		
		$maxquantity = $_REQUEST['maxquantity'];
		$maxprice = $_REQUEST['maxprice'];
		
		
		$remark = $_REQUEST['remark'];
		$price = $_REQUEST['price'];
		
		// $iname = 'test1';
		// $itype = 'G';
		// $iquantitytype = '123';
		//$picture = $_REQUEST['picture'];
		// $maxquantity = '789';
		// $maxprice = '345';
		// $maxquantityvalue = '89';
		
		// $remark = 'good';
		$empty="";
		$statue1 = "show";
		
		$sql = "select count(IType) as ity from item where IType = '$itype'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$searchcountitype = $row['ity'];
		
		$icode = 'I'.$itype.$searchcountitype;
		//echo $_FILES["fileToUpload"]["tmp_name"] . "<br>";
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
				$picturename = 'pictures\nopicture.jpg';
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file1)) {
					//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
					$picturename = 'pictures\\'.$picturnname;
					//$statue1 = "show";
				}else{
					//echo "(move_uploaded_file($_FILES[fileToUpload][tmp_name], $target_file1)) failed ";
					$picturename = 'pictures\nopicture.jpg';
				}
			}
		}else{
			$picturename = 'pictures\nopicture.jpg';
		}//1st loop end here
		
				//insert sql
				//$picturename = '\projectv2\pictures\item\\'.$picturnname;
				//echo "<br>".$picturename;
				$pictures = mysqli_real_escape_string($conn,$picturename);
				$sql = "INSERT INTO item(ICode, IName, IType, IQuantityType, Remark, Picture) VALUES 
										('$icode','$iname','$itype','$iquantitytype','$remark','$pictures')";

				if ($conn->query($sql) === TRUE) {
					
					 
					$sql="INSERT INTO customerproductshow(ICode, IName, IType, Price, Remark, Picture, Statue) VALUES
														('$icode','$iname','$itype','$price','$remark','$pictures','$statue1')";
														
					if ($conn->query($sql) === TRUE) {
						$q=0;
						$sql="INSERT INTO storage(ICode, IName, TQuantity, QuantityType, Price) VALUES
														('$icode','$iname','$q','$iquantitytype','$price')";
						if ($conn->query($sql) === TRUE) {
						// success
							if($maxquantity > 0){ // 如果maxquantity有勾選的話，執行敘述，沒有勾選的話，到else區域執行
								$configname="conf_maxquantity_".$icode;
								$statue="activated";
								$detail = "Max Quantity allow for " . $icode;
								$sql="INSERT INTO `config`(ConfigName, CharPR1N, CharPara1, CharPR2N, CharPara2, IntPR1N, IntPara1,  Statue, Detail) VALUES
														  ('$configname','$empty','$empty','$empty','$empty','g','$maxquantity','$statue','$detail')";
								if ($conn->query($sql) === TRUE) {
									
									
									
									$configname="conf_maxprice_".$icode;
									$statue="activated";
									$detail = "Max Price allow for " . $icode;
									$sql="INSERT INTO `config`(ConfigName, CharPR1N, CharPara1, CharPR2N, CharPara2, IntPR1N, IntPara1, Statue, Detail) VALUES
															  ('$configname','$empty','$empty','$empty','$empty','NT','$maxprice','$statue','$detail')";
									if ($conn->query($sql) === TRUE) {
										// insert into config success maxprice
										//echo 'all success';
										header("Refresh: 1;URL= /projectv2/employee/html/eaddnewitem.html");
									}else{
										// insert into config fail maxprice
										echo "mp".mysqli_error($conn);
									}

								}else{
									echo "mq".mysqli_error($conn);
									
								}
							}else{
								$configname="conf_maxquantity_".$icode;
								$statue="deactivated";
								$detail = "Max Quantity allow for " . $icode;
								$sql="INSERT INTO `config`(ConfigName, CharPR1N, CharPara1, CharPR2N, CharPara2, IntPR1N, IntPara1, Statue, Detail) VALUES
														  ('$configname','$empty','$empty','$empty','$empty','g','$maxquantity','$statue','$detail')";
								if ($conn->query($sql) === TRUE) {
									$configname="conf_maxprice_".$icode;
									$statue="deactivated";
									$detail = "Max Price allow for " . $icode;
									$sql="INSERT INTO `config`(ConfigName, CharPR1N, CharPara1, CharPR2N, CharPara2, IntPR1N, IntPara1, Statue, Detail) VALUES
															  ('$configname','$empty','$empty','$empty','$empty','NT','$maxprice','$statue','$detail')";
									if ($conn->query($sql) === TRUE) {
										// insert into config success maxprice
										echo 'all success';
										header("Refresh: 1;URL= /projectv2/employee/html/eaddnewitem.html");
									}else{
										// insert into config fail maxpriceecho mysqli_error($conn)
										echo mysqli_error($conn);
									}

								}else{
								echo mysqli_error($conn);
									
								}
							}
						
						}else{echo mysqli_error($conn);}
					}else{echo mysqli_error($conn);}
				}else{echo mysqli_error($conn);}

		
	}
?>