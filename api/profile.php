<?php
include("../config.php");
$ch = strtolower($_REQUEST["type"]);
if($ch== "fetch-profile") {
	$status=array();
	$sqlUserArray = array($_REQUEST["id"]);
	$ids = $db->getRow("select * from  " . SITE_USER . " where id =? and status=1", $sqlUserArray);
	if (is_array($ids) && count($ids) > 0){	
		$status=$ids;
		$status["status"]="success";
	} else {
		$status["status"]="error";
		$status["message"]="invalid Userid or password ".$id;
	} 
	echo json_encode($status);
	exit;
}
else if($ch=="update-profile"){
	$sqlUserArray = array($_REQUEST["id"]);
	$ids = $db->updateAry(SITE_USER,array($_REQUEST["field"]=>$_REQUEST["value"]),"where id=?", $sqlUserArray);
	if ($ids >= 0){	
		$status=$_REQUEST["field"]." update successfully ".$ids;
		$status["status"]="success";
	} else {
		$status["status"]="error";
		$status["message"]=$ids;
	} 
	echo json_encode($status);
	exit;
}
else{
	echo json_encode(array("status" => "error", "action" => "default-" . $ch, "request" => $_POST, "msg" => "please check you ajax request type is not define in form"));
}