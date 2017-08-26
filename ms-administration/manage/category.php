<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$pgMod = "category";
$pgAct = "view";
$pgTable = CATEGORY;
$pgHeading = "Category";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {
    include_once("../../config.php");
    $pgTable = CATEGORY;
    $dataAry = array();
    $sqlArray = array();
    $i = 0;
    $table = "";
    $select = "";
    $where = "";
    $whereAry = array();
    array_push($sqlArray, 0);
    $whereAry[] = " under_of= ? ";
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
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        $whereAry[] = " (heading like ? or linkname like ?) ";
    }
    if (is_array($whereAry) && count($whereAry) > 0)
        $where = " WHERE " . implode(" AND ", $whereAry);

    $pcount = $db->getVal("select count(id) from  " . CATEGORY . " $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;

    $contentDetail = $db->getRows("select id,name,type,under_of,status,language from " . CATEGORY . " $where order by lorder DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
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
			
			if($iList['under_of']=='0')
			$under_of="Main";
			else{
			$sqlUserEditArray = array($iList['under_of']);
			$under_of=$db->getVal("select name from ".CATEGORY." where id=?",$sqlUserEditArray);
			}
            $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"1\")' class='status_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";
            if ($iList["status"] == 1) {
                $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"0\")' class='status_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";
            }
            $header = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"header\", \"1\")' class='header_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";
            if ($iList["header"] == 1) {
                $header = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"header\", \"0\")' class='header_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";
            }
            $footer = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"footer\", \"1\")' class='footer_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";
            if ($iList["footer"] == 1) {
                $footer = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"footer\", \"0\")' class='footer_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";
            }
            $sidebar = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"m_left\", \"1\")' class='m_left_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";
            if ($iList["m_left"] == 1) {
                $sidebar = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"m_left\", \"0\")' class='m_left_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";
            }
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
            //submenu
            if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "r")) {
                $aryPgAct["action"] = "submenu";
                $button .= "<li><a onclick='loc(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-users'></i> Submenus</a></li>";
            }
            //delete
            if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "r")) {
                $aryPgAct["action"] = "delete";
                $button .= "<li><a onclick='del(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-times'></i> Delete</a></li>";
            }
            $button .= "</ul>
                  </div>";
            $i++;
            $aryPgAct["action"] = "submenu";
            $aryData[] = array(
               $checkbox,
                "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $iList["name"] . "(" . $iList["language"] . ")</a>",
				
                $under_of,
                $status,
                $button,
            );
        }
        $ProDetail["Result"] = $aryData;
    }
    echo json_encode($ProDetail);
    exit;
} elseif ($pgAct == "viewallsubmenu") {
    include_once("../../config.php");
    $pgTable = CATEGORY;
    $dataAry = array();
    $sqlArray = array();
    $i = 0;
    $table = "";
    $select = "";
    $where = "";
    $whereAry = array();
    array_push($sqlArray, $_REQUEST["id"]);
    $whereAry[] = " under_of= ? ";
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
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        $whereAry[] = " (heading like ? or linkname like ?) ";
    }
    if (is_array($whereAry) && count($whereAry) > 0)
        $where = " WHERE " . implode(" AND ", $whereAry);

    $pcount = $db->getVal("select count(id) from  " . CATEGORY . " $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;

    $contentDetail = $db->getRows("select id,name,type,under_of,status,language from " . CATEGORY . " $where order by lorder DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
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
			
			if($iList['under_of']=='0')
			$under_of="Main";
			else{
			$sqlUserEditArray = array($iList['under_of']);
			$under_of=$db->getVal("select name from ".CATEGORY." where id=?",$sqlUserEditArray);
			}
			
            $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"1\")' class='status_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";
            if ($iList["status"] == 1) {
                $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"0\")' class='status_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";
            }
            $header = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"header\", \"1\")' class='header_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";
            if ($iList["header"] == 1) {
                $header = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"header\", \"0\")' class='header_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";
            }
            $footer = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"footer\", \"1\")' class='footer_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";
            if ($iList["footer"] == 1) {
                $footer = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"footer\", \"0\")' class='footer_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";
            }
            $sidebar = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"m_left\", \"1\")' class='m_left_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";
            if ($iList["m_left"] == 1) {
                $sidebar = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"m_left\", \"0\")' class='m_left_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";
            }
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
            //submenu
            if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "r")) {
                $aryPgAct["action"] = "submenu";
                $button .= "<li><a onclick='loc(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-users'></i> Submenus</a></li>";
            }
            //delete
            if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "r")) {
                $aryPgAct["action"] = "delete";
                $button .= "<li><a onclick='del(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-times'></i> Delete</a></li>";
            }
            $button .= "</ul>
                  </div>";
            $i++;
            $aryPgAct["action"] = "submenu";
            $aryData[] = array(
                $checkbox,
                "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $iList["name"] . "(" . $iList["language"] . ")</a>",
				
                $under_of,
                $status,
                $button,
            );
        }
        $ProDetail["Result"] = $aryData;
    }
    echo json_encode($ProDetail);
    exit;
} elseif ($_POST && ($pgAct == "edit" || $pgAct == "add")) {
	
    $_SESSION['form'] = $_POST;
    $caption = $_POST['caption'];
    $flgEr = FALSE;
    if ($pgAct == "add") {
        $status = 0;
       if (isset($_POST["status"]))
            $status = 1;

        if (!isset($_POST['name']) || trim($_POST['name']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter Category Name";
        }

        if ($flgEr != TRUE) {
           $aryData=array(	'status'=>$status,
						'name'=>$_POST['name'],
						'type'=>POST('type'),
						'under_of'=>POST('under_of'),
						'detail'=>POST('detail'),
						'language'=>"en"
		);
		
		//echo "error==>aahello<pre>".$db->lq();print_r($_POST);print_r($aryData);exit;
		$flgIn = $db->insertAry(CATEGORY, $aryData);
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
         if (!isset($_POST['name']) || trim($_POST['name']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter Category Name";
        }

        if ($flgEr != TRUE) {
           $aryData=array(	'status'=>$status,
						'name'=>$_POST['name'],
						'type'=>POST('type'),
						'under_of'=>POST('under_of'),
						'detail'=>POST('detail'),
						'language'=>"en"
		);
		$sqlArray = array($_POST['id']);
		
            $flgUp = $db->updateAry(CATEGORY, $aryData, "where id=?", $sqlArray);
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
    $details = $db->getRow("select username,email,avatar from " . CATEGORY . " where id=?", $sqlArray);
    $res = $db->delete("delete from " . CATEGORY . " where id=?", $sqlArray);
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
            $details = $db->getRow("select username,email,avatar from " . CATEGORY . " where id=?", $sqlArray);
            @unlink("../uploads/avatar/" . $details["avatar"]);
            $res = $db->delete(CATEGORY, "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkinactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(CATEGORY, array("status" => 0), "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(CATEGORY, array("status" => 1), "where id='" . $ids . "'");
        }
    }
}elseif ($pgAct == "view" || $pgAct == "add" || $pgAct == "edit" || $pgAct == "submenu") {
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo ucwords($pgAct . " " . $pgHeading); ?> </div>
                    <?php if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "w")) { ?>
                        <div class="messenger-header-actions pull-right">
                            <button type="button" onclick="window.location = '<?php echo URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod, "action" => "add")); ?>'" class="btn btn-default btn-gradient dropdown-toggle" data-toggle="dropdown"> <span class="glyphicons glyphicons-circle_plus padding-right-sm"></span> Add new </button>
                        </div>
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
                            $aryForm = $db->getRow("SELECT * FROM " . CATEGORY . " WHERE id=?", $sqlUserEditArray);
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
                  <label class="col-lg-2 control-label" for="heading"> Name </label>
                  <div class="col-lg-10">
                    <input type="text" name="name" id="name" value="<?php echo unPOST($aryForm["name"]); ?>" class="form-control" />
                  </div>
                </div>	
                
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Category Type </label>
                  <div class="col-lg-10">
                    <select name="type" class="form-control" id="type" onChange="show_sub_cat(this.value)" >
                    <option value="1" <?php if($aryForm['type']=='1') echo 'selected'?>>Main Category</option>
                    <option value="2" <?php if($aryForm['type']=='2') echo 'selected'?>>Sub Category</option>
                    </select>
                  </div>
                </div>
                
                
                
                <div class="form-group" id="sub_cat" <?php
                            if ($pgAct == "add")
                                echo 'style="display:none"';
                            else if ($aryForm['type'] == '1')
                                echo 'style="display:none"';
                            ?>>
                  <label class="col-lg-2 control-label" for="heading"> Under Of </label>
                  <div class="col-lg-10">
                    <select name="under_of" class="form-control" id="under_of">
                    <option value="" selected>Select Category Type</option>
                    <?php  $sqlUserEditArray = array(0);
                            $aryForms = $db->getRows("SELECT * FROM " . CATEGORY . " WHERE under_of=?", $sqlUserEditArray);
							
							if (is_array($aryForms) && count($aryForms) > 0) {
						        foreach ($aryForms as $abc_aryForm) {
							?>
                    <option value="<?php echo $abc_aryForm['id'] ?>"  <?php if($aryForm['under_of']==$abc_aryForm['id']) echo "selected" ?>><?php echo $abc_aryForm['name'] ?></option>
                    <?php } 
					}
					?>
                    </select>
                  </div>
                </div>
                
                
				<div class="form-group">
                  <label class="col-lg-2 control-label" for="detail"> Detail </label>
                  <div class="col-lg-10">
					<textarea id="detail" class="form-control" name="detail" ><?php echo unPOST($aryForm["detail"]); ?></textarea>
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
                    elseif ($pgAct == "submenu" && isset($_GET["id"]) && $_GET["id"] != "") {
                        if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "w")) {
                            ?>
                            <div class="btn-group" style="right: 100px;position: absolute; z-index: 999;top: 2px;">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"> Action <span class="caret"></span> </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="javascript:;" onclick="add_action('active', '<?php echo $pgMod; ?>')">Active</a></li>
                                    <li><a href="javascript:;" onclick="add_action('inactive', '<?php echo $pgMod; ?>')">Inactive</a></li>
                                    <li><a href="javascript:;" onclick="add_action('delete', '<?php echo $pgMod; ?>')">Delete</a></li>
                                </ul>
                            </div>
                            <?php
                        }
                        $extraAry = array();
                        ?>
                        <!-- <div id="filterDiv">
                             <div class="form-horizontal">
                                 <div class="form-group">
                                     <label for="datepicker_2" class="col-lg-2 control-label">Added Date</label>
                                     <div class="col-md-4">
                                         <div class="input-group"> <span class="input-group-addon"><i class="fa fa-calendar "></i> </span>
                                             <input type="text" id="datepicker_2" class="form-control margin-top-none daterange searchFilter" placeholder="10/25/2013 - 10/25/2013" name="udate">
                                         </div>
                                     </div>
                                     <label for="status" class="col-lg-2 control-label">Status.</label>
                                     <div class="col-md-4">
                                         <select id="status" name="status" class="form-control margin-top-none searchFilter">
                                             <option value="">Select Status</option>
                                             <option value="1">Active</option>
                                             <option value="o">InActive</option>
                                             <option value="2">Unverified</option>
                                         </select>
                                     </div>

                                 </div>
                                 <div class="form-group">
                                     <div class="col-md-2">&nbsp;</div>
                                     <div class="col-md-10"><input onclick="applyfilter()" type="button" class="btn btn-info btn-sm" value="Apply and search" /></div>
                                 </div>
                             </div>
                         </div>-->
                        <div class="btn-group" style="right: 133px;position: absolute; z-index: 999;top: 11px;">
                            <input type="hidden" id="CurrentPage" name="CurrentPage" value="1">
                            <input type="hidden" name="startV" id="startV" value="0">
                            <input type="hidden" name="endV" id="endV" value="<?php echo $LinksDetails["recordPerPage"] ?>">
                        </div>
                        <div class="searchData"></div>
                        <table data-action="ViewAllSubMenu" data-extra="<?php
                        if (isset($_REQUEST["id"]) && $_REQUEST["id"] != "") {
                            echo "id=" . $_REQUEST["id"];
                        }
                        ?>" data-page="<?php echo $pgMod ?>" data-table="<?php echo $pgTable ?>" class="table table-widget table-striped" id="mssresulttable" data-export="0,4,5">
                            <thead>
                                <tr>
                                    <th><input onchange="checkAll('checkbox')" id="checkbox" class="row-checkbox" value="<?php echo $iList['id'] ?>" type="checkbox"></th>
                                     <th class="first"><a href="#" title="linkname">Name</a></th>
                                    <!--<th class="first"><a href="#" title="linkname">Name</a></th>-->
                                    <th><a href="#" title="linkname">Under Of</a></th>
                                   
                                    <th><a href="#" title="status">Status</a></th>

                                    <th class="last" >Actions</th>
                                </tr>
                            </thead>
                            <tbody id="resultBody">
                            </tbody>
                        </table>
                        <div class="paginationData"></div>
                        <?php
                    } else {

                        if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "w")) {
                            ?>
                            <div class="btn-group" style="right: 100px;position: absolute; z-index: 999;top: 2px;">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" style="
                                        "> Action <span class="caret"></span> </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="javascript:;" onclick="add_action('active', '<?php echo $pgMod; ?>')">Active</a></li>
                                    <li><a href="javascript:;" onclick="add_action('inactive', '<?php echo $pgMod; ?>')">Inactive</a></li>
                                    <li><a href="javascript:;" onclick="add_action('delete', '<?php echo $pgMod; ?>')">Delete</a></li>
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
                        <table data-action="viewAll" data-extra="<?php
                        if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {
                            $extraAry["status"] = $_REQUEST["status"];
                        }
                        if (is_array($extraAry) && count($extraAry) > 0)
                            echo getQueryString($extraAry);
                        ?>" data-page="<?php echo $pgMod ?>" data-table="<?php echo $pgTable ?>" class="table table-widget table-striped" id="mssresulttable" data-export="0,4,5">
                            <thead>
                                <tr>
                                    <th><input onchange="checkAll('checkbox')" id="checkbox" class="row-checkbox" value="<?php echo $iList['id'] ?>" type="checkbox"></th>
                                   <th class="first"><a href="#" title="linkname">Name</a></th>
                                    <!--<th class="first"><a href="#" title="linkname">Name</a></th>-->
                                    <th><a href="#" title="linkname">Under Of</a></th>
                                   
                                    <th><a href="#" title="status">Status</a></th>

                                    <th class="last" >Actions</th>
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