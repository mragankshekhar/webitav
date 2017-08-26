<?php

$pgMod = "transaction";
$pgAct = "view";
$pgTable = TRANSACTION;
$pgHeading = "Transaction";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {
    
    include_once("../../config.php");
	$pgTable = TRANSACTION;
    $dataAry = array();
    $sqlArray = array();
    $i = 0;
    $table = "";
    $select = "";
    $where = "";
    $whereAry = array();
    
    if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {
        array_push($sqlArray, $_REQUEST["status"]);
        $whereAry[] = " status= ? ";
    }
    if (isset($_REQUEST["udate"]) && $_REQUEST["udate"] != "") {
        $daterange = explode("-", $_REQUEST["udate"]);
        $from = date("Y-m-d", strtotime($daterange[0]));
        $to = date("Y-m-d", strtotime($daterange[1]));
        array_push($sqlArray, $from);
        array_push($sqlArray, $to);
        $whereAry[] = " (udate between ? and ?)";
    }
    if (isset($_REQUEST["q"]) && $_REQUEST["q"] != "") {
        array_push($sqlArray, $_REQUEST["q"]);
        array_push($sqlArray, $_REQUEST["q"]);
        $whereAry[] = " (heading like '%?%' or linkname like '%?%') ";
    }
	
    if (is_array($whereAry) && count($whereAry) > 0)
        $where = " WHERE " . implode(" AND ", $whereAry);
		
    $pcount = $db->getVal("select count(id) from  " . TRANSACTION . " $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;

    $contentDetail = $db->getRows("select id,from_id,to_id,amount,type,udate,status from " . TRANSACTION . " $where order by id DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
    $ProDetail["query"] = $db->lq() . $db->em();
    $ProDetail["ncount"] = count($contentDetail);
    $ProDetail["tcolumn"] = 7;
    if (is_array($contentDetail) && count($contentDetail) > 0) {
        $aryData = array();
        $i = 0;
        foreach ($contentDetail as $iList) {
            $button = "";
            $aryPgAct["id"] = $iList['id'];
            $aryPgAct["page_id"] = $pgMod;
			$status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"1\")' class='status_" . $iList['id'] . "'><small class='label btn-danger'>Inactive</small></div>";
            if ($iList["status"] == 1) {
                $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"0\")' class='status_" . $iList['id'] . "'><small class='label btn-green'>Active</small></div>";
            }
			 $fid = array($iList['from_id']);
             $from_id = $db->getVal("SELECT username FROM " . SITE_USER . " WHERE id=?", $fid);
			$toid = array($iList['from_id']);
             $to_id = $db->getVal("SELECT username FROM " . SITE_USER . " WHERE id=?", $toid);
			
			
			
            $image="<img src='".URL_ROOT."uploads/slider/".$iList['image']."' width='50'  />";
			
            $checkbox = "<input class='checkbox row-checkbox' name='check[]' value='" . $iList['id'] . "' type='checkbox'>";
            $button = "";
            $button .= "<span class='sort-handler btn btn-info btn-sm'><i class='fa fa-arrows'></i></span>";
            $button .= "<div class='btn-group'>
                    <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'> Action <span class='caret'></span> </button>
                    <ul class='dropdown-menu' role='menu'>";
            //edit
            if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "w")) {
                $aryPgAct["action"] = "edit";
                $button .= "<li><a onclick='loc(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-edit'></i> Edit</a></li>";
            }
            
            //delete
            if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "r")) {
                $aryPgAct["action"] = "delete";
                $button .= "<li><a onclick='del(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-times'></i> Delete</a></li>";
            }
            $button .= "</ul>
                  </div>";
            $i++;
            //$aryPgAct["action"] = "submenu";
            $aryData[] = array(
                $checkbox,
                "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $from_id . "</a>",
				
                $to_id,
				$iList['amount'],
				$iList['type'],
				$iList['udate'],
                $status,
                
            );
        }
        $ProDetail["Result"] = $aryData;
    }
    echo json_encode($ProDetail);
    exit;
	//-----------------------------------------------------------------------------------------
} elseif ($_POST && ($pgAct == "edit" || $pgAct == "add")) {
	
    $_SESSION['form'] = $_POST;
    $caption = $_POST['caption'];
    $flgEr = FALSE;
    if ($pgAct == "add") {
        $status = 0;
       if (isset($_POST["status"]))
            $status = 1;

        if (!isset($_POST['heading']) || trim($_POST['heading']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter Image heading";
        }

        if ($flgEr != TRUE) {
           $aryData=array(	'status'=>$status,
						'heading'=>POST('heading'),
						'detail'=>POST('detail'),
						'link'=>POST('link'),
						'language'=>"en"
		);
		if(isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"]))
		{
			
			$lfilename = basename($_FILES['lpimg']['name']);
			$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
			if(in_array($lext,array('jpeg','jpg','gif','png')))
			{
				
				$lnewfile=md5(microtime()).".".$lext;
				if(move_uploaded_file($_FILES['lpimg']['tmp_name'],"../uploads/slider/".$lnewfile))
				{
					$aryData['image']=$lnewfile;	
				}
			}
		 }elseif($_POST["video"]!=""){
			 $youtube=explode("?v=",$_POST["video"]);
			 $aryData['image']=$youtube["1"];
		 }
		
		$flgIn = $db->insertAry(SLIDER, $aryData);
			//echo "error==>aahello<pre>".$db->getlq();print_r($_POST);print_r($_FILES);print_r($aryData);exit;
	
            if (!is_null($flgIn)) {
                $_SESSION['msg'] = 'Saved Successfully';
                unset($_SESSION['form']);
                echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
            } else {
                echo "error==>" . $db->em();
            }
        }
    } 
	elseif ($pgAct == "edit" && isset($_POST['id']) && trim($_POST['id']) != '') {
        $sqlArray = array($_POST['id']);
       if (isset($_POST["status"]))
            $status = 1;
         if (!isset($_POST['heading']) || trim($_POST['heading']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter Image heading";
        }

        if ($flgEr != TRUE) {
           $aryData=array(	'status'=>$status,
						'heading'=>POST('heading'),
						'detail'=>POST('detail'),
						'link'=>POST('link'),
						'language'=>"en"
		);
		if(isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"]))
		{
			
			$lfilename = basename($_FILES['lpimg']['name']);
			$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
			if(in_array($lext,array('jpeg','jpg','gif','png')))
			{
				
				$lnewfile=md5(microtime()).".".$lext;
				if(move_uploaded_file($_FILES['lpimg']['tmp_name'],"../uploads/slider/".$lnewfile))
				{
					$aryData['image']=$lnewfile;	
				}
			}
		 }elseif($_POST["video"]!=""){
			 $youtube=explode("?v=",$_POST["video"]);
			 $aryData['image']=$youtube["1"];
		 }
		
		$sqlArray = array($_POST['id']);
		
            $flgUp = $db->updateAry(SLIDER, $aryData, "where id=?", $sqlArray);
            if (!is_null($flgUp)) {
                $_SESSION['msg'] = 'Saved Successfully';
                unset($_SESSION['form']);
                echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
            } else {
                echo "error==>" . $db->em();
            }
        }
    }
} elseif ($pgAct == "delete" && isset($_GET['id']) && trim($_GET['id']) != '') {
    $sqlArray = array($_GET['id']);
    $details = $db->getRow("select username,email,avatar from " . TRANSACTION . " where id=?", $sqlArray);
    $res = $db->delete("delete from " . TRANSACTION . " where id=?", $sqlArray);
    if (!is_null($res)) {
        $_SESSION['msg'] = 'Deleted Successfully';
        @unlink("../uploads/avatar/" . $details["avatar"]);
        redirect(URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
    } else {
        array_push($alert_err, $db->em());
    }
} elseif ($pgAct == "checkdelete") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $sqlArray = array($ids);
            $details = $db->getRow("select username,email,avatar from " . TRANSACTION . " where id=?", $sqlArray);
            @unlink("../uploads/avatar/" . $details["avatar"]);
            $res = $db->delete(TRANSACTION, "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkinactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(TRANSACTION, array("status" => 0), "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(TRANSACTION, array("status" => 1), "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "view" || $pgAct == "add" || $pgAct == "edit" ) {
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo ucwords($pgAct . " " . $pgHeading); ?> </div>
                    <?php if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "w")) { ?>
                        
                    <?php } ?>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_SESSION["msg"])) {
                        ?>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>Well done!</strong> <?php echo $_SESSION["msg"]; ?>. </div>
                        <?php
                        unset($_SESSION["msg"]);
                    }
                    if ($pgAct == "add" || ($pgAct == "edit" && isset($_GET['id']) && trim($_GET['id']) != '')) {
                        //print_r($_SERVER);
                        if ($pgAct == "edit" && !isset($_SESSION['form'])) {
                            $sqlUserEditArray = array($_GET['id']);
                            $aryForm = $db->getRow("SELECT * FROM " . TRANSACTION . " WHERE id=?", $sqlUserEditArray);
                        }
                        if (isset($_SESSION['form'])) {
                            $aryForm = $_SESSION['form'];
                            unset($_SESSION['form']);
                        }
                        echo $db->em();
                        $aryFrmAct = array("page_id" => $pgMod, "action" => $pgAct);
                        if ($pgAct == "edit")
                            $aryFrmAct['id'] = $_GET['id'];
                        ?>
                        <style>
                            .chosen-container{width:100% !important}
                        </style>
                        <form  class="form-horizontal form_ajax" enctype="multipart/form-data" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>">
              <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
              <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
              
              <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Heading </label>
                  <div class="col-lg-10">
                    <input type="text" name="heading" id="heading" value="<?php echo unPOST($aryForm['heading']); ?>" class="form-control" />
                  </div>
                </div>	
                <?php $ext=explode(".",$aryForm['image']); ?>
                <div class="form-group" style="display:none">
                  <label class="col-lg-2 control-label" for="video"> video </label>
                  <div class="col-lg-10">
                    <input type="text" name="video" id="video" value="<?php if($ext[1]=="")echo "https://www.youtube.com/watch?v=".$aryForm['image']; ?>" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-lg-2 control-label" for="linkname"> Image </label>
                  <div class="col-lg-10">
                    <input type="file" name="lpimg" class="form-control" />
					<?php
					  if($_GET['action']=="edit" && $aryForm['image']!='' && $ext[1]!="")
					  {
						
						 ?>
						  <br/>
						  <img src="../uploads/slider/<?php echo $aryForm['image'];?>" width="100" />
						   
						  <?php
					  }
					  ?>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-lg-2 control-label" for="detail"> Detail </label>
                  <div class="col-lg-10">
					<textarea id="detail" class="form-control" name="detail"><?php echo $aryForm["detail"] ?></textarea>
                  </div>
				</div>	
                <div class="form-group" style="display:none">
                  <label class="col-lg-2 control-label" for="link"> Link </label>
                  <div class="col-lg-10">
                    <input type="text" name="link" id="link" value="<?php echo unPOST($aryForm['link']); ?>" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-lg-2 control-label" for="linkname">  Activate this Page ? </label>
                  <div class="col-lg-10">
                    <div class="make-switch" data-on="success" data-off="danger">
                          <input name="status" type="checkbox" value="1" <?php if($aryForm['status']==1)echo "checked"; ?> >
                        </div>
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="submit">&nbsp; </label>
                  <div class="col-lg-10">
                    <input class="submit btn btn-blue" type="submit" value="Submit" />
                  </div>
                </div>
			</form>
                        <?php
                    }
                    else {

                        if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "w")) {
                            ?>
                            <div class="btn-group" style="right: 100px;position: absolute; z-index: 999;top: 2px;">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" style="
                                        "> Action <span class="caret"></span> </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="javascript:;" onclick="add_action('active', '<?php echo $pgMod; ?>')">Active</a></li>
                                    <li><a href="javascript:;" onclick="add_action('inactive', '<?php echo $pgMod; ?>')">Inactive</a></li>
                                </ul>
                            </div>
                            <?php
                        }
                        $extraAry = array();
                        ?>
                        
                        <div class="btn-group" style="right: 133px;position: absolute; z-index: 999;top: 11px;">
                            <input type="hidden" id="CurrentPage" name="CurrentPage" value="1">
                            <input type="hidden" name="startV" id="startV" value="0">
                            <input type="hidden" name="endV" id="endV" value="<?php echo $LinksDetails["recordPerPage"] ?>">
                        </div>
                        <div class="searchData"></div>
                        <table data-action="viewAll" data-extra="<?php $extraAry="";
                        if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {
                            $extraAry = "&status=".$_REQUEST["status"];
                        }
						/*if (isset($_REQUEST["cid"]) && $_REQUEST["cid"] != "") {
                            $extraAry = "&cid=".$_REQUEST["cid"];
                        }*/
                        if ($extraAry!="")
                            echo "data=yes".$extraAry;
                        ?>" data-page="<?php echo $pgMod ?>" data-table="<?php echo $pgTable ?>" class="table table-widget table-striped" id="mssresulttable" data-export="0,4,5">
                            <thead>
                                <tr>
                                    <th><input onchange="checkAll('checkbox')" id="checkbox" class="row-checkbox" value="<?php echo $iList['id'] ?>" type="checkbox"></th>
                                    <th class="first"><a href="#" title="linkname">From</a></th>
                                   <th class="first"><a href="#" title="linkname">To</a></th>
                                    <th><a href="#" title="linkname">Amount</a></th>
                                    <th><a href="#" title="linkname">Type</a></th>
                                    <th><a href="#" title="linkname">Date</a></th>
                                   
                                    <th><a href="#" title="status">Status</a></th>

                                   
                                </tr>
                            </thead>
                            <tbody id="resultBody">
                            </tbody>
                        </table>
                        <div class="paginationData"></div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>