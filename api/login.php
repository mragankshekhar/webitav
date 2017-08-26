<?php
include("../config.php");
$ch = strtolower($_REQUEST["type"]);
if($ch== "login") {
	$status=array();
	if (trim($_POST["uid"]) == "") {
		$status["status"]="error";
		$status["message"]="Please enter user name";
	} elseif ($_POST["pass"] == "") {
		$status["status"]="error";
		$status["message"]="Please enter password";
	} else {
		$sqlUserArray = array($_POST["uid"], $_POST["uid"], md5($_POST["pass"]));
		$ids = $db->getRow("select * from  " . SITE_USER . " where (username =? or email=?) and pass=? and status=1", $sqlUserArray);
		//echo $db->getLastQuery();exit;
		if (is_array($ids) && count($ids) > 0) {
			$loginData=array("is_online" => 1,
							"language"=>$_POST["lang"],
							"log"=>$_POST["long"],
							"lat"=>$_POST["lat"],
							"is_online"=>1,
							"last_login"=>date("Y-m-d H:i:s"));
			$db->updateAry(SITE_USER, $loginData, " where id='" . $_SESSION["user"]["uid"] . "'");
			$status["status"]="success";
			$status["message"]="Login Successfully...";
			$status["type"]="url";
			$status["url"]=URL_ROOT;
			$_SESSION["user"]["uid"]=$ids["id"];
			setcookie('userid', $_SESSION["user"]["uid"], time() + (3600 * 24 * 30));
			#$status["myfunction"]="saveUser";
		} else {
			$status["status"]="error";
			$status["message"]="invalid Userid or password - ".$ids;
		}
	}
	echo json_encode($status);
	exit;
}
elseif($ch=="register") {
	$status["request"]=$_POST;
	$username=$db->getVal("select id from ".SITE_USER." where username=?",array($_POST["uid"]));
	$email=$db->getVal("select id from ".SITE_USER." where email=?",array($_POST["email"]));
	if (trim($_POST["fullname"]) == "") {
		$status["status"]="error";
		$status["message"]="Please enter full name";
	} elseif (trim($_POST["uid"]) == "") {
		$status["status"]="error";
		$status["message"]="Please enter username";
	} elseif ($email != "") {
		$status["status"]="error";
		$status["message"]="Username already exist";
	} elseif (trim($_POST["email"]) == "") {
		$status["status"]="error";
		$status["message"]="Please enter Email";
	} elseif ($email != "") {
		$status["status"]="error";
		$status["message"]="Email already exist";
	} elseif ($_POST["pass"] == "") {
		$status["status"]="error";
		$status["message"]="Please enter password";
	} elseif ($_POST["repass"] == "") {
		$status["status"]="error";
		$status["message"]="Please enter confirm password";
	} elseif ($_POST["repass"] != $_POST["pass"]) {
		$status["status"]="error";
		$status["message"]="Password and confirm password must same";
	} else{
		$saveData=array("fullname"=>$_POST["fullname"],
						"username"=>$_POST["uid"],
					   "pass"=>md5($_POST["pass"]),
					   "email"=>$_POST["email"],
					   "language"=>$_POST["lang"],
						"long"=>$_POST["long"],
						"lat"=>$_POST["lat"],
						"is_online"=>1,
						"role"=>2,
						"udate"=>date("Y-m-d H:i:s"),
						"last_login"=>date("Y-m-d H:i:s"),
					   "status"=>1);
		$ids=$db->insertAry(SITE_USER,$saveData);
		if($ids>0 && is_numeric($ids)){
			$db->insertAry(PUSH_NOTIFICATION,array("uid"=>$ids,"regId"=>$_POST["reg_id"]));
			$status["status"]="success";
			$status["id"]=$ids;
			$_SESSION["user"]["uid"]=$ids["id"];
			$status["message"]="Register Successfully...";
			$status["type"]="url";
			$status["url"]=URL_ROOT;
			setcookie('userid', $_SESSION["user"]["uid"], time() + (3600 * 24 * 30));
			#$status["myfunction"]="saveUser";
		}else{
			$status["status"]="error";
			$status["message"]=json_encode($saveData);
		}
	}
	echo json_encode($status);
	exit;
}
else{
	echo json_encode(array("status" => "error", "action" => "default-" . $ch, "request" => $_POST, "msg" => "please check you ajax request type is not define in form"));
}	