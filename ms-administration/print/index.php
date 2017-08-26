<?php
include_once("../../config.php");
$aryForm=array(); //array to hold form data
//print_r($_SESSION);
if(!isset($_SESSION["admin"]))
{
	exit;
}else
{	
	if(!isset($_GET["type"])){
		echo "chek file";
		exit;
	}else{
		if(file_exists($_GET["type"].".php"))
			include($_GET["type"].".php");
		else{
			echo "invalid file";
			exit;
		}
	}
}
