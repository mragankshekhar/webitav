<?php
$pgMod = "admin_user";
$pgAct = "view";
$pgTable = SITE_USER;
$pgHeading = "Site User";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {
    
	 include_once("../../config.php");
	$pgTable = SITE_USER;
    $dataAry = array();
    $sqlArray = array();
    $i = 0;
    $table = "";
    $select = "";
    $where = "";
    $whereAry[] = " u.role=roll.id ";
	
	
	
    if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {
        array_push($sqlArray, $_REQUEST["status"]);
        $whereAry[] = " u.status= ? ";
    }
    if (isset($_REQUEST["udate"]) && $_REQUEST["udate"] != "") {
        $daterange = explode("-", $_REQUEST["udate"]);
        $from = date("Y-m-d", strtotime($daterange[0]));
        $to = date("Y-m-d", strtotime($daterange[1]));
        array_push($sqlArray, $from);
        array_push($sqlArray, $to);
        $whereAry[] = " (u.udate between ? and ?)";
    }
    if (isset($_REQUEST["q"]) && $_REQUEST["q"] != "") {
        array_push($sqlArray, $_REQUEST["q"]);
        $whereAry[] = " (u.fullname like '%?%') ";
    }
    if (is_array($whereAry) && count($whereAry) > 0)
        $where = " WHERE " . implode(" AND ", $whereAry);

    $pcount = $db->getVal("select count(u.id) from  " . SITE_USER . " as u," . ROLL . " as roll $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;

    $contentDetail = $db->getRows("select u.*,roll.name as roll_name from " . SITE_USER . " as u," . ROLL . " as roll $where order by u.lorder DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
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
                $status = "<div  onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"0\")' class='status_" . $iList['id'] . "'><small class='label btn-green'>Active</small></div>";
            } elseif ($iList["status"] == 2) {
                $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"1\")' class='status_" . $iList['id'] . "'><small class='label btn-red'>Banned</small></div>";
            }
            $checkbox = "<input class='checkbox row-checkbox' name='check[]' value='" . $iList['id'] . "' type='checkbox'>";
            $button = "";
            if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "w"))
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
            $aryData[] = array(
                $checkbox,
                "<img src='" . URL_ROOT . "/uploads/avatar/" . $iList["avatar"] . "' style=' width:35px'>",
                $iList["username"],
                $iList["email"],
                ucfirst(strtolower($iList["roll_name"])),
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

        if (!isset($_POST['username']) || trim($_POST['username']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter UserName";
        } elseif (trim($username) !== "") {
            $flgEr = TRUE;
            echo "error==>UserName Already exist ";
        } elseif ($_POST['password'] == "") {
            $flgEr = TRUE;
            echo "error==>password Cannot Blank.";
        } elseif (!isset($_POST['email']) || trim($_POST['email']) == '') {
            $flgEr = TRUE;
            echo "error==>Enter Email address";
        } elseif (trim($email_id) !== "") {
            $flgEr = TRUE;
            echo "error==>Email Already exist " . $email_id;
        }

        if ($flgEr != TRUE) {
            $alphanum = md5(microtime());
            $codestr = substr(str_shuffle($alphanum), 0, 16);
            $aryData = array(
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'pass' => md5($_POST['password']),
                'role' => trim($_POST['role']),
                'status' => $status
            );
            if (isset($_FILES["avatar"]["name"]) && !empty($_FILES["avatar"]["name"])) {
                $lfilename = basename($_FILES['avatar']['name']);
                $lext = strtolower(substr($lfilename, strrpos($lfilename, '.') + 1));
                if (in_array($lext, array('jpeg', 'jpg', 'gif', 'png'))) {
                    $lnewfile = md5(microtime()) . "." . $lext;
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], "../uploads/avatar/" . $lnewfile)) {
                        $aryData['avatar'] = $lnewfile;
                    }
                }
            }
            $flgIn = $db->insertAry(SITE_USER, $aryData);
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
        if (!isset($_POST['username']) || trim($_POST['username']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter UserName";
        } elseif (trim($username) !== "") {
            $flgEr = TRUE;
            echo "error==>UserName Already exist.";
        } elseif (!isset($_POST['email']) || trim($_POST['email']) == '') {
            $flgEr = TRUE;
            echo "error==>Enter Email address";
        } elseif (trim($email_id) !== "") {
            $flgEr = TRUE;
            echo "error==>Email Already exist please check";
        }

        if ($flgEr != TRUE) {
            $status = 0;
            if (isset($_POST["status"]))
                $status = 1;
            $aryData = array(
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'status' => $status
            );
            if (isset($_FILES["avatar"]["name"]) && !empty($_FILES["avatar"]["name"])) {

                $lfilename = basename($_FILES['avatar']['name']);
                $lext = strtolower(substr($lfilename, strrpos($lfilename, '.') + 1));
                if (in_array($lext, array('jpeg', 'jpg', 'gif', 'png'))) {

                    $lnewfile = md5(microtime()) . "." . $lext;
                    @unlink("../uploads/avatar/" . $details["avatar"]);
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], "../uploads/avatar/" . $lnewfile)) {
                        $aryData['avatar'] = $lnewfile;
                    }
                }
            }
            if ($_POST["password"] != "") {
                $aryData['pass'] = md5($_POST['password']);
				
            }
			//echo "error==>";print_r($_POST);print_r($aryData);exit;
            $sqlArray = array($_POST['id']);
            $flgUp = $db->updateAry(SITE_USER, $aryData, "where id=?", $sqlArray);
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
    $details = $db->getRow("select username,email,avatar from " . SITE_USER . " where id=?", $sqlArray);
    $res = $db->delete("delete from " . SITE_USER . " where id=?", $sqlArray);
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
            $details = $db->getRow("select username,email,avatar from " . SITE_USER . " where id=?", $sqlArray);
            @unlink("../uploads/avatar/" . $details["avatar"]);
            $res = $db->delete(SITE_USER, "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkinactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(SITE_USER, array("status" => 0), "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(SITE_USER, array("status" => 1), "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "view" || $pgAct == "add" || $pgAct == "edit") {
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
                    } elseif ($errorMsg != "") {
                        ?>
                        <div class="alert alert-error alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>Some Error!</strong> <?php echo $errorMsg; ?>. </div>
                        <?php
                    }
                    if ($pgAct == "add" || ($pgAct == "edit" && isset($_GET['id']) && trim($_GET['id']) != '')) {
                        //print_r($_SERVER);
                        if ($pgAct == "edit" && !isset($_SESSION['form'])) {
                            $sqlUserEditArray = array($_GET['id']);
                            $aryForm = $db->getRow("SELECT * FROM " . SITE_USER . " WHERE id=?", $sqlUserEditArray);
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
                        <script>
                            function checkRole(id) {
                                if ($("#" + id).val() == 7)
                                    $('#admin_approval').show();
                                else
                                    $('#admin_approval').hide();

                            }
                            function checkDesignation(id, div, name) {
                                var deprt = $(id).val();
                                $.get("ajax.php?type=checkDesignation&id=" + deprt + "&name=" + name, function (data) {
                                    if (data != "")
                                        $("#" + div).show().html(data)
                                    else
                                        $("#" + div).hide()
                                })
                            }
                        </script>
                        <form  class="form-horizontal form_ajax" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>">
                            <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
                            <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="email">Email* </label>
                                <div class="col-lg-10">
                                    <input type="email" name="email" id="email" value="<?php echo unPOST($aryForm['email']); ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="username">User name*</label>
                                <div class="col-lg-10">
                                    <input type="text" name="username" id="username" value="<?php echo unPOST($aryForm['username']); ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="password">Password* </label>
                                <div class="col-lg-10">
                                    <?php if ($pgAct == "edit") { ?> <a href="#" onclick="$(this).hide();
                                                        $('#password').show()">Change Password</a><?php } ?>
                                    <input <?php
                                    if ($pgAct == "edit") {
                                        echo 'style="display:none"';
                                    }
                                    ?> type="password" name="password" id="password" value="<?php echo unPOST($aryForm['password']); ?>" placeholder="<?php if ($pgAct == "edit") echo "Leave Blank if you dont want to change"; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="role">Role* </label>
                                <div class="col-lg-10">
                                    <select onchange="checkRole('role')" id="role" class="form-control" name="role">
                                        <option value="0">Select</option>
                                        <?php
                                        $user = $db->getRows("select * from " . ROLL);
                                        foreach ($user as $val) {
                                            ?>
                                            <option value="<?php echo $val['id'] ?>" <?php echo selected($val['id'], $aryForm['role']); ?>><?php echo $val['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="avatar">Avatar </label>
                                <div class="col-lg-10">

                                    <input type="file" name="avatar" id="avatar"  class="form-control" />
                                    <?php
                                    if ($aryForm['avatar'] != "") {
                                        echo "<img src='" . URL_ROOT . "uploads/avatar/" . $aryForm['avatar'] . "' height='70px' />";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="linkname">  Activate this User ? </label>
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
                                </ul>
                            </div>
                            <?php
                        }
                        $extraAry = array();
                        ?>
                        <div id="filterDiv">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="datepicker_2" class="col-lg-2 control-label">Register Date</label>
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
                                    <th></th>
                                    <th class="first"><a href="#" title="linkname">Image</a></th>
                                    <th><a href="#" title="linkname">Email</a></th>
                                    <th><a href="#" title="Underof">Role</a></th>
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