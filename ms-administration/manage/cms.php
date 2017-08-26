<?php

/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */



$pgMod = "cms";

$pgAct = "view";

$pgTable = CMS;

$pgHeading = "Pages";



if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')

    $pgAct = strtolower($_REQUEST['action']);



if ($pgAct == "viewall") {

    include_once("../../config.php");

    $pgTable = CMS;

    $dataAry = array();

    $sqlArray = array();

    $i = 0;

    $table = "";

    $select = "";

    $where = "";

    $whereAry = array();

    array_push($sqlArray, 0);

    $whereAry[] = " underof= ? ";

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

        $whereAry[] = " (linkname like ?) ";

    }

    if (is_array($whereAry) && count($whereAry) > 0)

        $where = " WHERE " . implode(" AND ", $whereAry);



    $pcount = $db->getVal("select count(id) from  " . CMS . " $where", $sqlArray);



    $startV = $_REQUEST['startV'];

    $endV = $_REQUEST['endV'];

    $ProDetail["totPost"] = $pcount;



    $contentDetail = $db->getRows("select id,linkname,status,language from " . CMS . " $where order by lorder DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);

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

            $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"1\")' class='status_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";

            if ($iList["status"] == 1) {

                $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"0\")' class='status_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";

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

            /*if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "r")) {

                $aryPgAct["action"] = "submenu";

                $button .= "<li><a onclick='loc(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-users'></i> Submenus</a></li>";

            }*/

            //delete

            /*if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "r")) {

                $aryPgAct["action"] = "delete";

                $button .= "<li><a onclick='del(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-times'></i> Delete</a></li>";

            }*/

            $button .= "</ul>

                  </div>";

            $i++;

            $aryPgAct["action"] = "submenu";

            $aryData[] = array(

                $checkbox,

                "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $iList["linkname"] ."</a>",

                $iList["language"],
                
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

    $pgTable = CMS;

    $dataAry = array();

    $sqlArray = array();

    $i = 0;

    $table = "";

    $select = "";

    $where = "";

    $whereAry = array();

    array_push($sqlArray, $_REQUEST["id"]);

    $whereAry[] = " underof= ? ";

    if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {

        array_push($sqlArray, $_REQUEST["status"]);

        $whereAry[] = " status= ? ";

    }

    
    if (isset($_REQUEST["q"]) && $_REQUEST["q"] != "") {

        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');

        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');

        $whereAry[] = " (heading like ? or linkname like ?) ";

    }

    if (is_array($whereAry) && count($whereAry) > 0)

        $where = " WHERE " . implode(" AND ", $whereAry);



    $pcount = $db->getVal("select count(id) from  " . CMS . " $where", $sqlArray);



    $startV = $_REQUEST['startV'];

    $endV = $_REQUEST['endV'];

    $ProDetail["totPost"] = $pcount;



    $contentDetail = $db->getRows("select id,linkname,status,language from " . CMS . " $where order by lorder DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);

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

            $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"1\")' class='status_" . $iList['id'] . "'><small class='label btn-danger btn'>Inactive</small></div>";

            if ($iList["status"] == 1) {

                $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"0\")' class='status_" . $iList['id'] . "'><small class='label btn-green btn'>Active</small></div>";

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

                "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $iList["linkname"] . "</a>",


                $iList["language"], 

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

        $sqlArray = array($_POST['email']);

        $email_id = $db->getVal("select email from " . SITE_USER . " where email =?", $sqlArray);

        $sqlArray = array($_POST['username']);

        $username = $db->getVal("select username from " . SITE_USER . " where username =?", $sqlArray);

        if (isset($_POST["status"]))

            $status = 1;





        if (trim($_POST['linkname']) != '' && is_numeric($_POST['linkname']) && $pgAct == "add" && $pagenameadd != "") {

            $flgEr = TRUE;

            //array_push($alert_err,"page Linkname already exist.");

            echo "error==>page Linkname already exist.";

        } elseif (trim($_POST['linkname']) != '' && is_numeric($_POST['linkname']) && $pgAct == "edit" && $pagenameedit != "") {

            $flgEr = TRUE;

            //array_push($alert_err,"page Linkname already exist.");

            echo "error==>page Linkname already exist.";

        } elseif (!isset($_POST['linkname']) || trim($_POST['linkname']) == '') {

            $flgEr = TRUE;

            echo "error==>Please enter Link name.";

        } elseif (!preg_match("#^[-A-Za-z\&\#0-9\;' .]*$#", $_POST['linkname'])) {

            $flgEr = TRUE;

            echo "error==>Link name with special characters are not allowed";

        } 


        if ($flgEr != TRUE) {

            $alphanum = md5(microtime());

            $codestr = substr(str_shuffle($alphanum), 0, 16);



            $status = 0;

            $header = 0;

            $footer = 0;

            $footer = 0;

            $m_left = 0;

            if (isset($_POST["status"]))

                $status = 1;if (isset($_POST["header"]))

                $header = 1;

            if (isset($_POST["footer"]))

                $footer = 1;if (isset($_POST["m_left"]))

                $m_left = 1;



            $aryData = array('linkname' => POST('linkname'),
                'heading' => POST('heading'),
                'lorder' => POST('lorder'),
                'pbody' => POST('pbody'),
                'status' => $status,
                'language' => POST('language'));

              
            $flgIn = $db->insertAry(CMS, $aryData);



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

        $details = $db->getRow("select username,email,avatar from " . SITE_USER . " where id=?", $sqlArray);



        $sqlArray = array($_POST['email'], $details["email"]);

        $email_id = $db->getVal("select email from " . SITE_USER . " where email =? and email !=?", $sqlArray);

        $sqlArray = array($_POST['username'], $details["username"]);

        $username = $db->getVal("select username from " . SITE_USER . " where username =? and username !=?", $sqlArray);

        if (isset($_POST["status"]))

            $status = 1;

        if (trim($_POST['linkname']) != '' && is_numeric($_POST['linkname']) && $pgAct == "add" && $pagenameadd != "") {

            $flgEr = TRUE;

            //array_push($alert_err,"page Linkname already exist.");

            echo "error==>page Linkname already exist.";

        } elseif (trim($_POST['linkname']) != '' && is_numeric($_POST['linkname']) && $pgAct == "edit" && $pagenameedit != "") {

            $flgEr = TRUE;

            //array_push($alert_err,"page Linkname already exist.");

            echo "error==>page Linkname already exist.";

        } elseif (!isset($_POST['linkname']) || trim($_POST['linkname']) == '') {

            $flgEr = TRUE;

            echo "error==>Please enter Link name.";

        } elseif (!preg_match("#^[-A-Za-z\&\#0-9\;' .]*$#", $_POST['linkname'])) {

            $flgEr = TRUE;

            echo "error==>Link name with special characters are not allowed";

        }



        if ($flgEr != TRUE) {

            $status = 0;

            if (isset($_POST["status"]))

                $status = 1;

            $status = 0;

            $header = 0;

            $footer = 0;

            $footer = 0;

            $m_left = 0;

            if (isset($_POST["status"]))

                $status = 1;if (isset($_POST["header"]))

                $header = 1;

            if (isset($_POST["footer"]))

                $footer = 1;if (isset($_POST["m_left"]))

                $m_left = 1;



            $aryData = array('linkname' => POST('linkname'),
                'heading' => POST('heading'),
                'lorder' => POST('lorder'),
                'pbody' => POST('pbody'),
                'status' => $status,
                'language' => POST('language'));



            $sqlArray = array($_POST['id']);

            $flgUp = $db->updateAry(CMS, $aryData, "where id=?", $sqlArray);

            if ($flgUp > 0) {

                $_SESSION['msg'] = 'Saved Successfully';

                unset($_SESSION['form']);

                echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));

            } else {

                echo "error==>" . $db->em();

            }

        }

    }

} elseif ($pgAct == "delete" && isset($_GET['id']) && trim($_GET['id']) != '') {

    //error_reporting(99);

    //$id=$_GET['id'];

    $details = $db->getRow("select username,email,avatar from " . CMS . " where id='" . $_GET['id'] . "'");

    $res = $db->delete("DELETE FROM " . CMS . " WHERE id='" . $_GET['id'] . "'");

    if (!is_null($res)) {

        $_SESSION['msg'] = 'Deleted Successfully';

        @unlink("../uploads/avatar/" . $details["avatar"]);

        $subdetails = $db->getRows("select username,email,avatar,id from " . CMS . " where underof=" . $id, $sqlArray);

        if (is_array($subdetails) && count($subdetails) > 0) {

            foreach ($subdetails as $sub) {

                @unlink("../uploads/avatar/" . $sub["avatar"]);

                $resub = $db->delete("delete from " . CMS . "where id=" . $sub["id"], $sqlArray);

            }

        }

        redirect(URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));

    } else {

        array_push($alert_err, $db->em());

    }

    echo $db->em() . $db->lq();

} elseif ($pgAct == "checkdelete") {

    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {

        foreach ($_POST["ids"] as $ids) {

            $details = $db->getRow("select username,email,avatar from " . CMS . " where id='" . $ids . "'", $sqlArray);

            $res = $db->delete("delete from " . CMS . " where id='" . $ids . "'", $sqlArray);

            if ($res) {

                @unlink("../uploads/avatar/" . $details["avatar"]);

                $subdetails = $db->getRows("select username,email,avatar,id from " . CMS . " where underof=" . $ids, $sqlArray);

                if (is_array($subdetails) && count($subdetails) > 0) {

                    foreach ($subdetails as $sub) {

                        @unlink("../uploads/avatar/" . $sub["avatar"]);

                        $resub = $db->delete("delete from " . CMS . " where id=" . $sub["id"], $sqlArray);

                    }

                }

            }

        }

    }

    echo $db->lq() . $db->em();

    print_r($sqlArray);

} elseif ($pgAct == "checkinactive") {

    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {

        foreach ($_POST["ids"] as $ids) {

            $res = $db->updateAry(CMS, array("status" => 0), "where id='" . $ids . "'");

        }

    }

} elseif ($pgAct == "checkactive") {

    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {

        foreach ($_POST["ids"] as $ids) {

            $res = $db->updateAry(CMS, array("status" => 1), "where id='" . $ids . "'");

        }

    }

} elseif ($pgAct == "view" || $pgAct == "add" || $pgAct == "edit" || $pgAct == "submenu") {

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

                            $aryForm = $db->getRow("SELECT * FROM " . CMS . " WHERE id=?", $sqlUserEditArray);

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

                            <div class="form-group">

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
                            <?php if($pgAct=="add"){ ?>
                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="linkname">Page For</label>

                                <div class="col-lg-10">

                                    <select id="linkname" name="linkname" class="form-control">

                                        <?php
											
										$pageNameList=array("TermsofUses","Career","Services","faq","Aboutus","Privacypolicy");
                                        foreach ($pageNameList as $c) {
                                            ?>
                                            <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                                        <?php } ?>

                                    </select>

                                </div>

                            </div>
                            <?php }else{ ?>

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="linkname">Links Name</label>

                                <div class="col-lg-10">

                                    <input type="text" name="linkname" readonly id="linkname" value="<?php echo unPOST($aryForm['linkname']); ?>" class="form-control" />

                                </div>

                            </div>
                            <?php } ?>

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="lorder"> Order </label>

                                <div class="col-lg-10">

                                    <input type="text" name="lorder" id="lorder" value="<?php echo $aryForm['lorder']; ?>" class="form-control"></input>

                                </div>

                            </div>



                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="body"> Page Body </label>

                                <div class="col-lg-10">



                                    <textarea id="pbody" class="form-control" rows="10" name="pbody"><?php echo unPOST($aryForm['pbody']); ?></textarea>

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

                        <div class="modal  fade" id="modalEditor" tabindex="-1" role="dialog" aria-labelledby="modalEditorLabel" aria-hidden="true" style="">

                            <div class="modal-body">

                                <div id="divEditor"></div>

                            </div>

                            <div class="modal-footer">

                                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>

                                <button class="btn btn-primary" onclick="save()"> &nbsp; Ok &nbsp; </button>

                            </div>

                        </div>



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

                                    <th class="first"><a href="#" title="linkname">Heading</a></th>
<th class="first"><a href="#" title="linkname">Second Heading</a></th>
                                   
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

                        <div id="filterDiv">

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

                        </div>
                        
                        

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

                                    <th class="first"><a href="#" title="linkname">Heading</a></th>

                                    <th><a href="#" title="linkname">Language</a></th>

                                    
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