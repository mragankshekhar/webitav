<?php
include("../config.php");
$ch = strtolower($_REQUEST["type"]);
if($ch== "fetch-page") {
	$status=array();
	$sqlUserArray = array($_REQUEST["id"], $_REQUEST["lang"]);
	$ids = $db->getRow("select pbody from  " . CMS . " where linkname=? and language=? and status=1", $sqlUserArray);
	if (is_array($ids) && count($ids) > 0){	
		echo (unPOST($ids["pbody"]));
	} else {
		echo "<h1>Invalid excess or page under construction </h1>".$db->lq()."\n\n".$_REQUEST["id"]."-----". $_REQUEST["lang"];
	} 
	exit;
}
else if($ch=="fetch-all-page"){
	$sqlUserArray = array($_REQUEST["lang"]);$data=array();
	$ids = $db->getRows("select linkname,heading from  " . CMS . " where language=? and status=1", $sqlUserArray);
	if (is_array($ids) && count($ids) > 0){$i=0;	
		foreach($ids as $k){
			$data["list"][$i]["linkname"]=$k["linkname"];
			$data["list"][$i]["heading"]=$k["heading"];
			$i++;
		}
		$data["status"]="success";
	} 
	echo json_encode($data);
}
else{
	echo json_encode(array("status" => "error", "action" => "default-" . $ch, "request" => $_POST, "msg" => "please check you ajax request type is not define in form"));
}