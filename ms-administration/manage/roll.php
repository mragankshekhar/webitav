<?php

$pgMod = "roll";

$pgAct = "view";



if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')

    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {

    include_once("../../config.php");

    $dataAry = array();

    $i = 0;

    $table = "";

    $select = "";

    $where = "";

    $whereAry = array();

    $whereQueryAry = array();

    $whereAry[] = " ";

    if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {

        $whereQueryAry[] = $_REQUEST["status"];

        $whereAry[] = " roll.status=?";

    }

    if (isset($_REQUEST["q"]) && $_REQUEST["q"] != "") {

        $whereQueryAry[] = $_REQUEST["q"];

        $whereQueryAry[] = $_REQUEST["q"];

        $whereAry[] = " (roll.name like '%?%' OR roll.detail like '%?%') ";

    }

    if (is_array($whereAry) && count($whereAry) > 0)

        $where = " WHERE " . implode(" AND ", $whereAry);



    $pcount = $db->getVal("select count(id) from  " . ROLL . " as roll $where");



    $startV = $_REQUEST['startV'];

    $endV = $_REQUEST['endV'];

    $ProDetail["totPost"] = $pcount;



    $contentDetail = $db->getRows("select u.*,roll.name as roll_name from " . SITE_USER . " as u," . ROLL . " as roll $where order by id DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"));

    $ProDetail["query"] = $db->getLastQuery() . $db->getErMsg();

    $ProDetail["ncount"] = count($contentDetail);

    $ProDetail["tcolumn"] = 7;

    if (is_array($contentDetail) && count($contentDetail) > 0) {

        $aryData = array();

        $i = 0;

        foreach ($contentDetail as $iList) {

            $button = "";

            $aryPgAct["id"] = $iList['id'];

            $aryPgAct["page_id"] = $pgMod;

            $status = "<div class='status_" . $iList['id'] . "'><small class='label btn-danger'>Inactive</small></div>";

            if ($iList["status"] == 1) {

                $status = "<div class='status_" . $iList['id'] . "'><small class='label btn-green'>Active</small></div>";

            } elseif ($iList["status"] == 2) {

                $status = "<div class='status_" . $iList['id'] . "'><small class='label btn-red'>Banned</small></div>";

            }

            $checkbox = "<input class='checkbox row-checkbox' name='check[]' value='" . $iList['id'] . "' type='checkbox'>";

            $button = "<div class='btn-group'>

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

            $departMent = $db->getVal("select name from " . DEPARTMENT . " where id='" . $iList["department"] . "'");

            if ($departMent == "")

                $departMent = "Not Set";

            $designation = "<br /><small>" . $db->getVal("select name from " . DESIGNATION . " where id='" . $iList["designation"] . "'") . "</small>";

            if ($designation == "")

                $designation = "";$reportTo = "";

            $report_dept = $db->getVal("select name from " . DEPARTMENT . " where id='" . $iList["reporting_department"] . "'");

            $report_desig = $db->getVal("select name from " . DESIGNATION . " where id='" . $iList["reporting_designation"] . "'");

            if ($report_dept != '')

                $reportTo = " Report to " . $report_desig . "(" . $report_dept . ")";

            $Limit = "";

            if ($iList["role"] == 7) {

                $Limit = "<br /><small>Approve Limit (" . $iList["min_amount"] . "-" . $iList["max_amount"] . ")</small>";

            }

            $aryData[] = array(

                $checkbox,

                "<img src='" . URL_ROOT . "/uploads/avatar/32/32/" . $iList["avatar"] . "'>",

                $iList["username"] . $designation . $reportTo,

                $iList["email"] . $Limit,

                ucfirst(strtolower($iList["roll_name"])),

                $departMent,

                $status,

                $button,

            );

        }

        $ProDetail["Result"] = $aryData;

    }

    echo json_encode($ProDetail);

    exit;

} 
elseif ($_POST && ($pgAct == "edit" || $pgAct == "add" || $pgAct == "permission")) {

    $flgEr = FALSE;

    if ($pgAct == "permission") {

        $db->delete("delete from " . PRIVILAGES . " where roll_id=?", array($_GET["id"]));

        foreach ($_POST["name"] as $name) {

            $delete = 0;

            $edit = 0;

            $all = 0;

            $none = 0;

            $view = 0;

            if ($_POST["permission-" . $name][0] == "a")

                $all = 1;

            elseif ($_POST["permission-" . $name][0] == "v")

                $view = 1;

            elseif ($_POST["permission-" . $name][0] == "n")

                $none = 1;

            $aryData = array('page_name' => trim(strtolower($name)),

                'a' => $all,

                'r' => $delete,

                'w' => $edit,

                'v' => $view,

                'n' => $none,

                'roll_id' => $_GET["id"]

            );



            $flgIn = $db->insertAry(PRIVILAGES, $aryData);

        }

        $_SESSION['msg'] = 'Saved Successfully';

        echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod, "action" => $pgAct, "id" => $_GET["id"])));

    }

    elseif ($flgEr != TRUE && $pgAct == "add") {

        if ($_POST['status'] == 1) {

            $db->updateAry(ROLL, array("status" => 0));

        }

        $aryData = array('name' => POST('name'),

            'status' => trim($_POST['status'])

        );

        $flgIn = $db->insertAry(ROLL, $aryData);

        if (!is_null($flgIn)) {



            $_SESSION['msg'] = 'Saved Successfully';



            echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));

        } else {

            echo "error==>" . $db->getErMsg();

        }

    } elseif ($pgAct == "edit" && isset($_POST['id']) && trim($_POST['id']) != '') {

        $aryData = array('name' => POST('name'),

            'status' => trim($_POST['status'])

        );

        if ($_POST['status'] == 1) {

            $db->updateAry(ROLL, array("status" => 0));

        }

        $flgUp = $db->updateAry(ROLL, $aryData, "where id='" . $_POST['id'] . "'");

        if (!is_null($flgUp)) {

            $_SESSION['msg'] = 'update Successfully';

            echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));

        } else {

            echo "error==>" . $db->getErMsg() . $db->getLastQuery();

        }

    }

} elseif ($pgAct == "delete" && isset($_GET['id']) && trim($_GET['id']) != '') {

    $sqlArray = array($_GET['id']);

    $res = $db->delete("delete from " . ROLL . " where id=?", $sqlArray);

    if (!is_null($res)) {

        $_SESSION['msg'] = 'Deleted Successfully';

        redirect(URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));

    } else {

        echo "error==>" . $db->getErMsg();

    }

} elseif ($pgAct == "checkdelete") {

    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {

        foreach ($_POST["ids"] as $ids) {

            $res = $db->delete(ROLL, "where id='" . $ids . "'");

            echo $db->getLastQuery();

        }

    }

    echo $db->getErMsg();

} elseif ($pgAct == "checkinactive") {

    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {

        foreach ($_POST["ids"] as $ids) {

            $res = $db->updateAry(ROLL, array("status" => 0), "where id='" . $ids . "'");

            echo $db->getLastQuery();

        }

    }

    echo $db->getErMsg();

    print_r($_REQUEST);

} elseif ($pgAct == "checkactive") {

    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {

        foreach ($_POST["ids"] as $ids) {

            $res = $db->updateAry(ROLL, array("status" => 1), "where id='" . $ids . "'");

            echo $db->getLastQuery();

        }

    }

    echo $db->getErMsg();

} elseif ($pgAct == "view" || $pgAct == "add" || $pgAct == "edit" || $pgAct == "permission") {

    ?>

    <div class="row">

        <div class="col-md-12">

            <div class="panel">

                <div class="panel-heading">

                    <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo ucwords($pgAct); ?> Page for <?php if (isset($_GET['id'])) echo $db->getVal("select name from " . ROLL . " where id=" . $_GET["id"]) ?></div>

                    <div class="messenger-header-actions pull-right">

                            <button type="button" onclick="window.location = '<?php echo URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod, "action" => "add")); ?>'" class="btn btn-default btn-gradient dropdown-toggle" data-toggle="dropdown"> <span class="glyphicons glyphicons-circle_plus padding-right-sm"></span> Add new </button>

                        </div>

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

                            $sqlEdirRowllArray = array($_GET['id']);

                            $aryForm = $db->getRow("SELECT * FROM " . ROLL . " WHERE id=?", $sqlEdirRowllArray);

                        }

                        if (isset($_SESSION['form'])) {

                            $aryForm = $_SESSION['form'];

                            unset($_SESSION['form']);

                        }

                        $aryFrmAct = array("page_id" => $pgMod, "action" => $pgAct);

                        if ($pgAct == "edit")

                            $aryFrmAct['id'] = $_GET['id'];

                        ?>

                        <form  class="form-horizontal form_ajax" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>">

                            <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />

                            <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="name">Roll Name</label>

                                <div class="col-lg-10">

                                    <input type="text" name="name" id="name" value="<?php echo unPOST($aryForm['name']); ?>" class="form-control" />

                                </div>

                            </div>

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="linkname">  Is Defalt User Role? </label>

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

                    elseif ($pgAct == "permission" && isset($_GET['id']) && trim($_GET['id']) != '') {

                        ?>

                        <form method="post" action="" class="form_ajax">

                            <table class="table table-widget table-bordered">

                                <thead>

                                    <tr>

                                        <th class="first"><div>Sr.No</div></th>

                                        <th>Page Name</th>

                                        <th>All</th>

                                        <th>View Only</th>

                                        <th class="last" width="25px">NoAccess</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    $admenu = $db->getRows("select * from " . MENUS . " where is_root=0 and status=1 order by lorder");

                                    if (is_array($admenu) && count($admenu) > 0) {

                                        $j = 0;

                                        foreach ($admenu as $value) {

                                            $j++;

                                            $SqlMymenuArray = array($value['mid']);

                                            $files1 = $db->getRows("select * from " . MENUS . " where status=1 and is_root=? order by lorder", $SqlMymenuArray);

                                            ?>



                                            <?php if (count($files1) > 0) { ?>

                                                <tr bgcolor="#999999" style="color:#fff">

                                                    <th><?php echo $j; ?></th>

                                                    <th><?php echo "" . $value['header'] . ""; ?></th>

                                                    <th colspan="3"></th>

                                                </tr>

                                                <?php

                                            } else {

                                                $per = checkPermission($value['name'], $_GET["id"]);

                                                ?>

                                                <tr bgcolor="#999999" style="color:#fff">

                                                    <th><?php echo $j . " - " . $per; ?></th>

                                                    <td><input type="hidden" name="name[]" value="<?php echo $value['name']; ?>" />

                                                        <?php echo "" . $value['header'] . ""; ?>(<?php echo $value['name']; ?>.php)

                                                    </td>

                                                    <td>

                                                        <input type="radio" name="permission-<?php echo $value['name']; ?>[]" value="a" <?php if ($per == "a") echo "checked"; ?> /></td>

                                                    <td><input type="radio" name="permission-<?php echo $value['name']; ?>[]" value="v" <?php if ($per == "v") echo "checked"; ?> /></td>

                                                    <td><input type="radio" name="permission-<?php echo $value['name']; ?>[]" value="n" <?php if ($per == "n") echo "checked"; ?> /></td>

                                                </tr>

                                            <?php }

                                            ?>

                                            <?php

                                            $i = 0;

                                            $sqlmenuArray = array($value['mid']);

                                            $files1 = $db->getRows("select * from " . MENUS . " where status=1 and is_root=? order by lorder", $sqlmenuArray);

                                            if (is_array($files1) && count($files1) > 0) {

                                                foreach ($files1 as $val) {

                                                    $i++;

                                                    $per = checkPermission($val['name'], $_GET["id"]);

                                                    ?>

                                                    <tr>

                                                        <td><?php echo $i; ?></td>

                                                        <td><input type="hidden" name="name[]" value="<?php echo $val['name']; ?>" />

                                                            <?php echo "" . $val['header'] . ""; ?>(<?php echo $val['name']; ?>.php)

                                                        </td>

                                                        <td><input type="radio" name="permission-<?php echo $val['name']; ?>[]" value="a" <?php if ($per == "a") echo "checked"; ?> /></td>

                                                        <td><input type="radio" name="permission-<?php echo $val['name']; ?>[]" value="v" <?php if ($per == "v") echo "checked"; ?> /></td>

                                                        <td><input type="radio" name="permission-<?php echo $val['name']; ?>[]" value="n" <?php if ($per == "n") echo "checked"; ?> /></td>

                                                    </tr>

                                                    <?php

                                                    //}

                                                }

                                            }

                                        }

                                    }

                                    //echo $db->getErMsg().$db->getLastQuery();;

                                    ?></tbody>

                            </table>

                            <div class="form-row row-fluid">

                                <div class="span12">

                                    <div class="row-fluid">

                                        <div class="form-actions">

                                            <div class="span3"></div>

                                            <div class="span9 controls">

                                                <button type="submit" class="btn btn-info">Save changes</button>

                                                <button type="button" onclick="window.location = ''" class="btn">Cancel</button>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </form>

                        <?php

                    }

                    else {

                        ?>

                        <div class="btn-group" style="right: 133px;position: absolute; z-index: 999;top: 11px;">

                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="

                                    "> Action <span class="caret"></span> </button>

                            <ul class="dropdown-menu" role="menu">

                                <li><a href="javascript:;" onclick="add_action('active', '<?php echo $pgMod; ?>')">Active</a></li>

                                <li><a href="javascript:;" onclick="add_action('inactive', '<?php echo $pgMod; ?>')">Inactive</a></li>

                            </ul>

                        </div>

                        <?php

                        $sqlLimit = "SELECT * FROM " . ROLL . " ORDER BY name,id ASC";

                        $aryList = $db->getRows($sqlLimit);



                        if (is_array($aryList)) {

                            if (count($aryList) > 0) {

                                ?>

                                <table  class="table table-widget table-striped table-checklist" id="datatable">

                                    <thead>

                                        <tr>

                                            <th class="first"><div></div></th>

                                            <th><a href="#" title="linkname">Roll Name</a></th>

                                            <th><a href="#" title="status">Default User Role</a></th>

                                            <th class="last" >Actions</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        foreach ($aryList as $iList) {

                                            $aryPgAct["id"] = $iList['id'];

                                            $aryPgAct['page_id'] = $pgMod;

                                            ?>

                                            <tr id="tr_<?php echo $iList['id'] ?>">

                                                <td><input class="checkbox row-checkbox" name="check[]" value="<?php echo $iList['id'] ?>" type="checkbox"></td>

                                                <td class="text-slash"><?php echo ucwords($iList['name']); ?></td>

                                                <td class="text-slash status_<?php echo $iList['id'] ?>">

                                                    <?php if ($iList["status"] == 0) { ?>

                                                        <span class="label btn-red2 margin-right-sm">Inactive</span>

                                                    <?php } else { ?>

                                                        <span class="label btn-green">Active</span>

                                                    <?php } ?>

                                                </td>

                                                <td class="last" style="width:130px">

                                                    <div class="btn-group">

                                                        <?php

                                                        if ($iList['id'] != 1) {

                                                            $aryPgAct['action'] = "edit";

                                                            ?>

                                                            <button title="edit" onClick="loc('<?php echo URL_ADMIN_HOME . getQueryString($aryPgAct); ?>')" type="button" class="btn btn-info btn-gradient"> <span class="glyphicons glyphicons-edit"></span> </button>

                                                            <?php

                                                            $aryPgAct['action'] = "permission";

                                                            ?>

                                                            <button type="button" title="permission" onClick="loc('<?php echo URL_ADMIN_HOME . getQueryString($aryPgAct); ?>')" class="btn btn-success  btn-gradient"> <span class="glyphicons glyphicons-rotation_lock"></span> </button>

                                                            <?php

                                                            $aryPgAct['action'] = "delete";

                                                            ?>

                                                            <button title="delete" type="button" onClick="del('<?php echo URL_ADMIN_HOME . getQueryString($aryPgAct); ?>')" class="btn btn-danger btn-gradient" style="display:none"> <span class="glyphicons glyphicons-delete"></span> </button>

                                                        <?php } else {

                                                            ?>

                                                            <?php

                                                            $aryPgAct['action'] = "permission";

                                                            ?>

                                                            <button type="button" title="permission" onClick="loc('<?php echo URL_ADMIN_HOME . getQueryString($aryPgAct); ?>')" class="btn btn-success  btn-gradient"> <span class="glyphicons glyphicons-rotation_lock"></span> </button>

                                                        <?php }

                                                        ?>

                                                    </div>

                                                </td>

                                            </tr>

                                            <?php

                                        }

                                        ?>

                                    </tbody>

                                </table>

                                <?php

                            } else {

                                echo '<div class="alert alert-info alert-dismissable">

                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                        <strong>Notice !</strong> Sorry No record Found. </div>';

                            }

                        } else {

                            echo '<div class="alert alert-danger alert-dismissable">

                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                        <strong>error !</strong> ' . $db->getErMsg() . '. </div>';

                        }

                    }

                    ?>

                </div>

            </div>

        </div>

    </div>



    <?php

}

?>