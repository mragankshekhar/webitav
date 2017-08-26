<?php
$pgMod = "manage_menu";

$pgAct = "view";


if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);



if ($_POST && ($pgAct == "edit" || $pgAct == "add")) {

    $_SESSION['form'] = $_POST;

    //print_r($_POST);exit;

    $flgEr = FALSE;

    if (!isset($_POST['icon']) || trim($_POST['icon']) == '') {

        $flgEr = TRUE;

        echo "error==>Please enter Icon Name.";
    } elseif (!isset($_POST['name']) || trim($_POST['name']) == '') {

        $flgEr = TRUE;

        echo "error==>Please enter menu Name.";
    } elseif (!isset($_POST['header']) || trim($_POST['header']) == '') {

        $flgEr = TRUE;

        echo "error==>Please enter header Name.";
    }



    if ($flgEr != TRUE && $pgAct == "add") {

        $aryData = array('icon' => trim($_POST['icon']),
            'header' => trim($_POST['header']),
            'is_root' => $_POST['root'],
            'status' => $_POST['status'],
            'lorder' => $_POST['lorder'],
            'name' => trim($_POST['name'])
        );

        $flgIn = $db->insertAry(MENUS, $aryData);

        if (!is_null($flgIn)) {



            $_SESSION['msg'] = 'Saved Successfully';

            unset($_SESSION['form']);

            echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
        } else {

            echo "error==>" . $db->getErMsg();
        }
    } elseif ($pgAct == "edit" && isset($_POST['id']) && trim($_POST['id']) != '') {

        $aryData = array('icon' => trim($_POST['icon']),
            'header' => trim($_POST['header']),
            'is_root' => $_POST['root'],
            'status' => $_POST['status'],
            'lorder' => $_POST['lorder'],
            'name' => trim($_POST['name'])
        );

        //error_reporting(55);print_r($aryData);

        $flgUp = $db->updateAry(MENUS, $aryData, "where mid='" . $_POST['id'] . "'");

        //echo $db->getLastQuery();

        if (!is_null($flgUp)) {

            $_SESSION['msg'] = 'update Successfully';

            unset($_SESSION['form']);

            echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
        } else {

            echo "error==>" . $db->getErMsg();
        }
    }
} elseif ($pgAct == "delete" && isset($_GET['id']) && trim($_GET['id']) != '') {
    $dataSql = array($_GET['id']);
    $count = $db->getVal("select count(mid) from " . MENUS . " where is_root = ?", $dataSql);

    if ($count == 0) {

        $dataSql = array($_GET['id']);
        $res = $db->delete("delete from " . MENUS . " where mid=?", $dataSql);
        //echo "delete".$db->em();
        if ($res != "") {
            $_SESSION['msg'] = 'Deleted Successfully';
            redirect(URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
        } else {
            array_push($alert_err, "some error in query");
        }
    } else {
        array_push($alert_err, "You can't delete this category ,it has subcategory");
    }
} elseif ($pgAct == "checkinactive") {

    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {

        foreach ($_POST["ids"] as $ids) {

            $res = $db->updateAry(MENUS, array("status" => 0), "where mid='" . $ids . "'");

            echo $db->getLastQuery();
        }
    }

    echo $db->getErMsg();

    print_r($_REQUEST);
} elseif ($pgAct == "checkactive") {

    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {

        foreach ($_POST["ids"] as $ids) {

            $res = $db->updateAry(MENUS, array("status" => 1), "where mid='" . $ids . "'");

            echo $db->getLastQuery();
        }
    }

    echo $db->getErMsg();
} elseif ($pgAct == "view" || $pgAct == "add" || $pgAct == "edit") {
    ?>



    <div class="row">

        <div class="col-md-12">

            <div class="panel">

                <div class="panel-heading">

                    <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo ucwords($pgAct); ?> Page </div>

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
            $SqlmyMenuArr = array($_GET['id']);
            $aryForm = $db->getRow("SELECT * FROM " . MENUS . " WHERE mid=?", $SqlmyMenuArr);
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

                                <label class="col-lg-2 control-label" for="icon">Icon</label>

                                <div class="col-lg-10">

                                    <input type="text" name="icon" id="icon" value="<?php echo unPOST($aryForm['icon']); ?>" class="form-control" />

                                </div>

                            </div>

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="header">Link Name </label>

                                <div class="col-lg-10">

                                    <input type="text" name="name" id="name" value="<?php echo unPOST($aryForm['name']); ?>" class="form-control" />

                                </div>

                            </div>

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="header"> Header </label>

                                <div class="col-lg-10">

                                    <input type="text" name="header" id="header" value="<?php echo unPOST($aryForm['header']); ?>" class="form-control">

                                    </input>

                                </div>

                            </div>

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="root">Is Parent </label>

                                <div class="col-lg-10">

                                    <select  id="root" class="form-control" name="root">

                                        <option value="0"  <?php if ($aryForm['is_root'] == 0) echo 'selected="selected"'; ?>>Main</option>

                        <?php
                        $rw = $db->getRows("select * from " . MENUS . " where is_root=0");

                        if (is_array($rw) && count($rw) > 0) {

                            foreach ($rw as $row) {
                                ?>

                                                <option value="<?php echo $row['mid']; ?>" <?php if ($aryForm['is_root'] == $row['mid']) echo 'selected="selected"'; ?> ><?php echo $row['header']; ?></option>

                            <?php }
                        } ?>

                                    </select>

                                </div>

                            </div>

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="lorder"> Order </label>

                                <div class="col-lg-10">

                                    <input type="text" name="lorder" id="lorder" value="<?php echo $aryForm['lorder']; ?>" class="form-control">

                                    </input>

                                </div>

                            </div>

                            <div class="form-group">

                                <label class="col-lg-2 control-label" for="linkname"> Activate this Page ? </label>

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
        $aryPgAct = array("page_id" => $pgMod, "action" => "add");
        $sqlmyMenuArray = array($_GET['id']);
        $sqlLimit = "SELECT * FROM " . MENUS . " where is_root=? ORDER BY lorder";

        $aryList = $db->getRows($sqlLimit, $sqlmyMenuArray);

        if (is_array($aryList) && count($aryList) > 0) {

            $i = 0;
            ?>



                            <table class="table table-widget table-striped table-checklist" id="datatable">

                                <tfoot>

                                    <tr>

                                        <td colspan="7"></td>

                                    </tr>

                                </tfoot>

                                <thead>

                                    <tr>

                                        <th class="first" width="25px"><div><div class="btn-group" style="right: 291px;position: absolute; z-index: 999;top: 2px;">

                                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" style="

                                                            "> Action <span class="caret"></span> </button>

                                                    <ul class="dropdown-menu" role="menu">

                                                        <li><a href="javascript:;" onclick="add_action('active', '<?php echo $pgMod; ?>')">Active</a></li>

                                                        <li><a href="javascript:;" onclick="add_action('inactive', '<?php echo $pgMod; ?>')">Inactive</a></li>

                                                    </ul>

                                                </div></div></th>

                                        <th></th>

                                        <th>Order No</th>

                                        <th>Name</th>

                                        <th>Header</th>

                                        <th>Status</th>

                                        <th class="last" width="25px">Actions</th>

                                    </tr>

                                </thead>

                                <tbody>

            <?php
            foreach ($aryList as $iList) {

                $i++;

                $aryPgAct["id"] = $iList['mid'];
                ?>

                                        <tr>

                                            <td class="text-slash"><span class="<?php echo $iList['icon']; ?>"></span></td>

                                            <td><input class="checkbox row-checkbox" name="check[]" value="<?php echo $iList['mid'] ?>" type="checkbox"></td>

                                            <td class="text-slash"><?php echo $iList['lorder']; ?></td>

                                            <td class="text-slash"><?php echo ucwords($iList['name']); ?></a></td>

                                            <td class="text-slash" ><?php echo ucwords($iList['header']); ?></a></td>

                                            <td class="text-slash status_<?php echo $iList['mid'] ?>">

                                <?php if ($iList["status"] == 0) { ?>

                                                    <span class="label btn-red2 margin-right-sm">Inactive</span>

                <?php } else { ?>

                                                    <span class="label btn-green">Active</span>

                <?php } ?>

                                            </td>

                                            <td class="text-slash" style="width:90px" >

                                                <div class="btn-group">

                <?php
                $aryPgAct['action'] = "edit";
                ?>

                                                    <button title="edit" onClick="loc('<?php echo URL_ADMIN_HOME . getQueryString($aryPgAct); ?>')" type="button" class="btn btn-info btn-gradient"> <span class="glyphicons glyphicons-edit"></span> </button>

                <?php
                $aryPgAct['action'] = "delete";
                ?>

                                                    <button title="delete" type="button" onClick="del('<?php echo URL_ADMIN_HOME . getQueryString($aryPgAct); ?>')" class="btn btn-danger btn-gradient"> <span class="glyphicons glyphicons-delete"></span> </button>



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

            echo '<div class="alert alert-danger alert-dismissable">

                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                        <strong>error !</strong> Sorry No record Found' . $db->getErMsg() . '. </div>';
        }
    } else {
        ?>



                                <?php
                                $aryPgAct = array("page_id" => $pgMod, "action" => "add");

                                $sqlLimit = "SELECT * FROM " . MENUS . " where is_root=0  ORDER BY lorder";



                                $aryList = $db->getRows($sqlLimit);

                                if (is_array($aryList)) {

                                    if (count($aryList) > 0) {

                                        $i = 0;
                                        ?>

                                <div class="btn-group" style="right: 100px;position: absolute; z-index: 999;top: 2px;">

                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" style="

                                            "> Action <span class="caret"></span> </button>

                                    <ul class="dropdown-menu" role="menu">

                                        <li><a href="javascript:;" onclick="add_action('active', '<?php echo $pgMod; ?>')">Active</a></li>

                                        <li><a href="javascript:;" onclick="add_action('inactive', '<?php echo $pgMod; ?>')">Inactive</a></li>

                                    </ul>

                                </div>

                                <table  class="table table-widget table-striped table-checklist" id="datatable">

                                    <tfoot>

                                        <tr>

                                            <td colspan="7"></td>

                                        </tr>

                                    </tfoot>

                                    <thead>

                                        <tr>

                                            <th class="first" width="25px"><div></div></th>

                                            <th></th>

                                            <th>Order</th>

                                            <th>Header</th>

                                            <th>Status</th>

                                            <th class="last" width="25px">Actions</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php
                                        foreach ($aryList as $iList) {

                                            $i++;

                                            $aryPgAct["id"] = $iList['mid'];
                                            ?>

                                            <tr>

                                                <td  class="text-slash"><span class="<?php echo $iList['icon']; ?>"></span></td>

                                                <td><input class="checkbox row-checkbox" name="check[]" value="<?php echo $iList['mid'] ?>" type="checkbox"></td>

                                                <td class="text-slash"><?php echo $iList['lorder']; ?></td>

                                                <td class="text-slash"><?php echo ucwords($iList['header']);
                    echo " ( " . (int) $db->getVal("select count(mid) from " . MENUS . " where is_root='" . $iList['mid'] . "'") . " )"; ?></a></td>

                                                <td class="text-slash status_<?php echo $iList['mid'] ?>">

                                    <?php if ($iList["status"] == 0) { ?>

                                                        <span class="label btn-red2 margin-right-sm">Inactive</span>

                                    <?php } else { ?>

                                                        <span class="label btn-green">Active</span>

                                    <?php } ?>

                                                </td>

                                                <td class="text-slash" style="width:90px" >

                                                    <div class="btn-group">

                                    <?php
                                    $aryPgAct['action'] = "edit";
                                    ?>

                                                        <button title="edit" onClick="loc('<?php echo URL_ADMIN_HOME . getQueryString($aryPgAct); ?>')" type="button" class="btn btn-info btn-gradient"> <span class="glyphicons glyphicons-edit"></span> </button>

                                    <?php
                                    $aryPgAct['action'] = "delete";
                                    ?>

                                                        <button title="delete" type="button" onClick="del('<?php echo URL_ADMIN_HOME . getQueryString($aryPgAct); ?>')" class="btn btn-danger btn-gradient"> <span class="glyphicons glyphicons-delete"></span> </button>



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

