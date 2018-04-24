<?php
	include('sqlconnstorage.php');

	if(checkUserLoginValid()==true){
		echo 'test1';
	}else{
		echo 'test';
	}
?>