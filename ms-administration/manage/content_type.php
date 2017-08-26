<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$pgMod = "content_type";
$pgAct = "view";
$pgTable = CONTENT_TYPE;
$pgHeading = "Pages";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {
    
    include_once("../../config.php");
	$pgTable = CONTENT_TYPE;
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

    $pcount = $db->getVal("select count(id) from  " . CONTENT_TYPE . " $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;

    $contentDetail = $db->getRows("select id,heading,position,image,status,language from " . CONTENT_TYPE . " $where order by id DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
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
            $image="<img src='".URL_ROOT."uploads/avatar/".$iList['image']."' width='30'  />";
			
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
			 unset($aryPgAct["action"]);
            $aryPgAct["page_id"] = "content";
			$aryPgAct["ids"] = $iList["id"];
            $aryData[] = array(
                $checkbox,
                "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $iList["heading"] . "(" . $iList["language"] . ")</a>",
                $image,
                $status,
                $button,
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
            echo "error==>Please Enter heading";
        }

        if ($flgEr != TRUE) {
           $aryData = array(
		   		'language' => "en",
                'heading' => trim($_POST['heading']),
               	'status' => $status,
				'detail'=>($_POST['detail']),
			   'bgcolor'=>($_POST['bgcolor']),
				'lorder'=>POST('lorder'),
				
            );
            if (isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"])) {
                $lfilename = basename($_FILES['lpimg']['name']);
                $lext = strtolower(substr($lfilename, strrpos($lfilename, '.') + 1));
                if (in_array($lext, array('jpeg', 'jpg', 'gif', 'png'))) {
                    $lnewfile = md5(microtime()) . "." . $lext;
                    if (move_uploaded_file($_FILES['lpimg']['tmp_name'], "../uploads/avatar/" . $lnewfile)) {
                        $aryData['image'] = $lnewfile;
                    }
                }
            }
			
			if(isset($_FILES["icon"]["name"]) && !empty($_FILES["icon"]["name"]))
		{	$lfilename = basename($_FILES['icon']['name']);
			$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
			if(in_array($lext,array('jpeg', 'jpg', 'gif', 'png')))
			{ $lnewfile=md5(microtime()).".".$lext;
				if(move_uploaded_file($_FILES['icon']['tmp_name'],"../uploads/avatar/".$lnewfile))
				{
					$aryData['icon']=$lnewfile;	
				}
			}else{
				echo "error==>Please Select Only PNG , JPG , JPEG, GIF Image";
				}
		 }
            $flgIn = $db->insertAry(CONTENT_TYPE, $aryData);
            if (!is_null($flgIn)) {
                $_SESSION['msg'] = 'Saved Successfully';
                unset($_SESSION['form']);
                echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
            } else {
                echo "error==>" . $db->em();
            }
        }
    } elseif ($pgAct == "edit" && isset($_POST['id']) && trim($_POST['id']) != '') {
        $sqlArray = array($_POST['id']);
       if (isset($_POST["status"]))
            $status = 1;
        if (!isset($_POST['heading']) || trim($_POST['heading']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter heading";
        }if ($flgEr != TRUE) {
            $status = 0;
            if (isset($_POST["status"]))
                $status = 1;
            $aryData = array(
			'language' => "en",
                'heading' => trim($_POST['heading']),
               	'status' => $status,
				'bgcolor'=>($_POST['bgcolor']),
				'detail'=>($_POST['detail']),
				'lorder'=>POST('lorder'),
				
            );
            if (isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"])) {
                $lfilename = basename($_FILES['lpimg']['name']);
                $lext = strtolower(substr($lfilename, strrpos($lfilename, '.') + 1));
                if (in_array($lext, array('jpeg', 'jpg', 'gif', 'png'))) {
                    $lnewfile = md5(microtime()) . "." . $lext;
                    if (move_uploaded_file($_FILES['lpimg']['tmp_name'], "../uploads/avatar/" . $lnewfile)) {
                        $aryData['image'] = $lnewfile;
                    }
                }
            }
			
			if(isset($_FILES["icon"]["name"]) && !empty($_FILES["icon"]["name"]))
		{	$lfilename = basename($_FILES['icon']['name']);
			$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
			if(in_array($lext,array('jpeg', 'jpg', 'gif', 'png')))
			{ $lnewfile=md5(microtime()).".".$lext;
				if(move_uploaded_file($_FILES['icon']['tmp_name'],"../uploads/avatar/".$lnewfile))
				{
					$aryData['icon']=$lnewfile;	
				}
			}else{
				echo "error==>Please Select Only PNG , JPG , JPEG, GIF Image";
				}
		 } $sqlArray = array($_POST['id']);
            $flgUp = $db->updateAry(CONTENT_TYPE, $aryData, "where id=?", $sqlArray);
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
    $details = $db->getRow("select username,email,avatar from " . CONTENT_TYPE . " where id=?", $sqlArray);
    $res = $db->delete("delete from " . CONTENT_TYPE . " where id=?", $sqlArray);
    if (!is_null($res)) {
        $_SESSION['msg'] = 'Deleted Successfully';
        @unlink("../uploads/avatar/" . $details["avatar"]);
        redirect(URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
    } else {
        array_push($alert_err, $db->em());
    }
} 
elseif ($pgAct == "checkdelete") {
	//echo "error==>hello";print_r($_POST);exit;
	
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $details = $db->getRow("select username,email,avatar from " . CONTENT_TYPE . " where id='".$ids."'", $sqlArray);
            $res = $db->delete("delete from " . CONTENT_TYPE . " where id='".$ids."'", $sqlArray);
            if ($res) {
				
				$_SESSION['msg']='Delete Successfully';
				echo "success==>".(URL_ADMIN_HOME.getQueryString(array("page_id"=>$pgMod)));
				
                
            }
        }
    }
    echo $db->lq() . $db->em();
    print_r($sqlArray);
}elseif ($pgAct == "checkinactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(CONTENT_TYPE, array("status" => 0), "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(CONTENT_TYPE, array("status" => 1), "where id='" . $ids . "'");
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
                            $aryForm = $db->getRow("SELECT * FROM " . CONTENT_TYPE . " WHERE id=?", $sqlUserEditArray);
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
                        <form  class="form-horizontal form_ajax" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>">
                            <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
                            <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
                            <div class="form-group hide">
                                <label class="col-lg-2 control-label" for="language">Language</label>
                                <div class="col-lg-10">
                                    <select id="language" name="language" class="form-control">
                                        <?php
                                        $sqlArray = array(1);
                                        $pages = $db->getRows("select * from " . LANGUAGE . " where status=?", $sqlArray);
                                        foreach ($pages as $c) {
                                            ?>
                                            <option value="<?php echo $c["code"]; ?>"<?php echo selected($c['code'], $aryForm['language']); ?>><?php echo $c["name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="linkname">Heading</label>
                                <div class="col-lg-10">
                                    <input type="text" name="heading" id="heading" value="<?php echo unPOST($aryForm['heading']); ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="linkname">BG Color</label>
                                <div class="col-lg-10">
                                    <input type="color" name="bgcolor" id="bgcolor" value="<?php echo unPOST($aryForm['bgcolor']); ?>" class="form-control" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="heading">image </label>
                                <div class="col-lg-10">
              <input type="file" name="lpimg" class="form-control" />
              <?php
					  if($_GET['action']=="edit" && $aryForm['image']!='')
					  {
						
						 ?>
              <br/>
             <img src="../uploads/avatar/<?php echo $aryForm['image'];?>" width="100" id="displayimgcontent" />
             
                    <img src="<?php print_r($LinksDetails['closebtn']) ;?> " onclick="closec_typeimage(<?php echo $_GET['id'] ?>)" id="closeimgbtn" style="width:40px" > 
             
             
              <?php
					  }
					  ?>
                                     
<div style="clear:both;"></div>
<div class="status alert alert-success" style="display: none"></div>
            </div>
                            </div>
                            
                              <div class="form-group">
            <label class="col-lg-2 control-label" for="pbody"> Short Detail </label>
            <div class="col-lg-10">
              <textarea id="detail" class="form-control textarea" rows="4" name="detail"><?php echo strip_tags(unPOST($aryForm['detail'])); ?></textarea>
            </div>
          </div>
          
           <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Order </label>
                  <div class="col-lg-10">
                    <input type="text" name="lorder" id="lorder" value="<?php echo unPOST($aryForm['lorder']); ?>" class="form-control" />
                  </div>
                </div>	
                
                
                 <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Icon </label>
                  <div class="col-lg-10">
              <input type="file" name="icon" class="form-control" />
              <?php
					  if($_GET['action']=="edit" && $aryForm['icon']!='')
					  {
						
						 ?>
              <br/>
             <img src="../uploads/avatar/<?php echo $aryForm['icon'];?>" width="100" id="displayimgicon" />
             
                    <img src="<?php print_r($LinksDetails['closebtn']) ;?> " onclick="closec_icon(<?php echo $_GET['id'] ?>)" id="closeicon" style="width:40px" > 
             
             
              <?php
					  }
					  ?>
                                     

            </div>
                  
                </div>
                            
                            
<div class="form-group">
                                <label class="col-lg-2 control-label" for="linkname">  Activate this <?php echo $pgHeading ?> ? </label>
                                <div class="col-lg-10">
                                    <div class="make-switch" data-on="success" data-off="danger">
                                        <input name="status" type="checkbox" value="1" <?php if ($aryForm['status'] == 1) echo "checked"; ?> >
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
                                    <th><a href="#" title="linkname">Image</a></th>
                                   
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