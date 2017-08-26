<?php
include("../config.php");
$ch = $_REQUEST["type"];
if ($ch == "notice") {
    $data = array();
    $sqlDataArray = array($_REQUEST["itype"]);
    $isMag = $db->getRows("select notice from " . NOTIFICATION . " where type=? and status=1", $sqlDataArray);
    $data["count"] = (int) count($isMag);
    if (is_array($isMag) && count($isMag) > 0) {
        foreach ($isMag as $ms) {
            $data["data"][] = $ms["notice"];
        }
    } else {
        $data["data"][] = "No " . $_REQUEST["itype"] . " found";
    }
    echo json_encode($data);
} 

elseif ($ch == "inactive_news") {
    $data = array();
	$userlist = array();
    $sqlDataArray = array($_SESSION["admin"]["uid"]);
    $sql="update " . CONTENT . " set status=0 where date(date)<'" . date('Y-m-d') . "' and status=1 and id!=127";
	$newsDetail = $db->query($sql);
	echo "success==>msg==>record has been successfully update";
	
	//echo json_encode(array("status"=>"success","msg"=>"record has been successfully update"));
    
}

 elseif ($ch == "update_date") {
    $data = array();
	$userlist = array();
	$lang="en"; // $lang="en"(data) and field = heading
     if (isset($_REQUEST['date']) || trim($_REQUEST['date']) != '') {
			
            $date=$_REQUEST['date'];
			$Array = array($lang => $date);
			$field="update_date";
			
     }else{ $date=date('Y-m-d');
            $Array = array($lang => $date);
			$field="update_date";
         
	 }
	 
	 $flgUp = $db->updateAry(SETTINGS, $Array, "field=?", array($field));
	 
	  if($flgUp!=''){
	  echo "success==>msg==>date has been successfully update"; }else {
		 echo "success==>msg==>date has been not update"; echo $db-> lq();  echo $db-> em();  
	  }
	
    
}

elseif ($ch == "update_time") {
    $data = array();
	$userlist = array();
	$lang="en"; // $lang="en"(data) and field = heading
     if (isset($_REQUEST['time']) || trim($_REQUEST['time']) != '') {
			
            $time=$_REQUEST['time'];
			$Array = array($lang => $time);
			$field="update_time";
			
     }else{ $time=date('H:i:s', time() - date('Z'));
            $Array = array($lang => $time);
			$field="update_time";
         
	 }
	 
	 $flgUp = $db->updateAry(SETTINGS, $Array, "field=?", array($field));
	 
	  if($flgUp!=''){
	  echo "success==>msg==>time has been successfully update"; }else {
		 echo "success==>msg==>time has been not update"; echo $db-> lq();  echo $db-> em();  
	  }
	
    
}

elseif ($ch == "total_view") {
    $data = array();
	$userlist = array();
	$lang="en"; // $lang="en"(data) and field = heading
     if (isset($_REQUEST['view']) || trim($_REQUEST['view']) != '') {
			
            $view=$_REQUEST['view'];
			$Array = array($lang => $view);
			$field="total_view";
			
     }
	 
	 $flgUp = $db->updateAry(SETTINGS, $Array, "field=?", array($field));
	 
	  if($flgUp!=''){
	  echo "success==>msg==>total view has been successfully update"; }else {
		 echo "success==>msg==>total view has been not update"; echo $db-> lq();  echo $db-> em();  
	  }
	
    
}


elseif ($ch == "update_timeold") {
    $data = array();
	$userlist = array();
   if (isset($_REQUEST['time']) || trim($_REQUEST['time']) != '') {
			
			//echo "error==>"."iiii"; exit; 
            $time=$_POST['time'];
			//echo "error==>".$date; exit; 
     }else{ $time=date('H:i:s', time() - date('Z'));}
	 
	 //$time2 = date('H:i:s', gmdate('U')); // 13:50:29
     //$time3 = date('H:i:s', time()); // 13:50:29
   
     $sqlArray = array($time);
	
	 $flgUp = $db->updateAry(SETTINGS, $aryData, "where date=?", $sqlArray);
	echo "success==>msg==>record has been successfully update";
	
	//echo json_encode(array("status"=>"success","msg"=>"record has been successfully update"));
    
}

elseif ($ch == "deleteNotice") {
    $data = array();
    $isMag = $db->updateAry(NOTIFICATION, array("status" => 0), " where type='" . $_REQUEST["itype"] . "' and status=1");
} 

elseif ($ch == "fetchNotification") {
    $userlist = array();
    $sqlDataArray = array($_SESSION["admin"]["uid"]);
    $userDetail = $db->getRows("select * from " . NOTIFICATION . " where to_id=? and status=1");
    $logdata["count"] = count($userDetail);
    if (is_array($userDetail) && count($userDetail) > 0) {
        $i = 0;
        foreach ($userDetail as $uDetail) {
            $i++;
            $uid = $uDetail["id"];
            $sqlDataArray = array($uDetail["from_id"]);
            $fdetail = $db->getRow("select * from " . SITE_USER . " where id=?");
            $logdata["user"][$i]["id"] = $uDetail["id"];
            $logdata["user"][$i]["url"] = $uDetail["url"];
            $logdata["user"][$i]["notice"] = $uDetail["notice"];
            $logdata["user"][$i]["username"] = $fdetail["username"];
            $logdata["user"][$i]["section"] = unPOST($uDetail["type"]);
            $logdata["user"][$i]["avatar"] = URL_ROOT . "/uploads/avatar/150/150/" . $fdetail["avatar"];
            $db->updateAry(NOTIFICATION, array("status" => 0), "where id=" . $uDetail["id"]);
        }
    }
    $logdata["error"] = $db->getErMsg();
    $logdata["lastQuery"] = $db->getLastQuery();
    echo json_encode($logdata);
} elseif ($ch == "Lists") {
    ?>
    var emails = [<?php
    $i = 0;
    header('Content-Type: application/javascript');
    $sqlArray = array("1");
    $school_list = $db->getRows("select u.username,u.email,u.status,r.name as rolename from " . SITE_USER . " as u," . ROLL . " as r  where r.id=u.role and u.role!=?", $sqlArray);
    if (is_array($school_list) && count($school_list) > 0) {
        foreach ($school_list as $sList) {
            $i++;
            if ($i > 1) {
                echo ",
			";
            }
            $status = "";
            if ($sList["status"] == 1) {
                $status = "<div class='status_" . $iList['id'] . "'><small class='label btn-green btn-gradient'>Active</small></div>";
            } elseif ($sList["status"] == 0) {
                $status = "<div class='status_" . $iList['id'] . "'><small class='label btn-red btn-gradient'>Inactive</small></div>";
            } elseif ($sList["status"] == 2) {
                $status = "<div class='status_" . $iList['id'] . "'><small class='label btn-yellow btn-gradient'>Unverify</small></div>";
            }
            echo '{ title: "' . $sList["username"] . '", tender_id: "' . $sList["email"] . '", reffrence_id: "' . $sList["rolename"] . '", status: "' . $status . '" }';
			
			
        }
    }
    ?>]<?php
    //echo $db->getErMsg();
} else if ($ch == "updatePosition") {

    function updatePosition($id, $table, $position) {
        global $db;
        $sqlArray = array(":id" => $id);
        echo $db->updateAry($table, array("lorder" => $position), " id=:id", $sqlArray);
        $checkExist = $db->getVal("select id from " . $table . " where lorder=? and id!=?", array($position, $id));
        /* if ($checkExist != "") {
          $newPosiotion = $position - 1;
          updatePosition($checkExist, $table, $newPosiotion);
          } */
    }

    updatePosition($_REQUEST["id"], $_REQUEST["table"], $_REQUEST["index"]);
} else if ($ch == "updateStatus") {
    $sqlArray = array($_REQUEST["id"]);
    echo $db->updateAry($_REQUEST["table"], array($_REQUEST["field"] => $_REQUEST["myvalue"]), " id=?", $sqlArray);
    echo $db->em() . $db->lq();
}else if ($ch == "updateisnew") {
	//print_r($_REQUEST);exit;
    $sqlArray = array($_REQUEST["id"]);
    echo $db->updateAry($_REQUEST["table"], array($_REQUEST["field"] => $_REQUEST["myvalue"]), " id=?", $sqlArray);
    echo $db->em() . $db->lq();
}