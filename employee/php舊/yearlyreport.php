<?php
	include('sqlconnstorage.php');
	
	$yearst=""; $yearend="";
	$totalinwithyear="SELECT SUM(Price) AS totalbuyprice, AVG(Price) AS avginprice, SUM(Quantity) AS sminq, AVG() AS avginq 
	FROM inandout WHERE Purpose = 'in' AND (StockReceive BETWEEN '$yearst' AND '$yearend')";

	$totaloutwithyear="SELECT SUM(Price) AS totalsellprice, AVG(Price) AS avgoutprice, SUM(Quantity) AS smoutq, AVG() AS avgoutq 
	FROM inandout WHERE Purpose = 'out' AND (DateCreate BETWEEN '$yearst' AND '$yearend')";
	
	$singleitemin="SELECT SUM(Price) AS smpinrice, AVG(Price) AS avginprice, SUM(Quantity) AS sminq, AVG(Quantity) AS avginq 
	FROM inandout WHERE ICode='$icode' AND Purpose = 'in' AND (DateCreate BETWEEN '$yearst' AND '$yearend')";
	
	$singleitemout="SELECT SUM(Price) AS smoutprice, AVG(Price) AS avgoutprice, SUM(Quantity) AS smoutq, AVG(Quantity) AS avgoutq 
	FROM inandout WHERE ICode='$icode' AND Purpose = 'out' AND (DateCreate BETWEEN '$yearst' AND '$yearend')";
	
	$typeitemin="SELECT SUM(Price) AS smpinrice, AVG(Price) AS avginprice, SUM(Quantity) AS sminq, AVG(Quantity) AS avginq 
	FROM inandout WHERE IType='$itype' AND Purpose = 'in' AND (DateCreate BETWEEN '$yearst' AND '$yearend')";
	
	$typeitemout="SELECT SUM(Price) AS smoutprice, AVG(Price) AS avgoutprice, SUM(Quantity) AS smoutq, AVG(Quantity) AS avgoutq 
	FROM inandout WHERE IType='$itype' AND Purpose = 'out' AND (DateCreate BETWEEN '$yearst' AND '$yearend')";
	
	
?>