<?php
$pgMod = "package";
$pgAct = "view";
$pgTable = MEMBERSHIPS;
$pgHeading = "Package";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {

    include_once("../../config.php");
    $pgTable = MEMBERSHIPS;
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

    $pcount = $db->getVal("select count(id) from  " . MEMBERSHIPS . " $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;

    $contentDetail = $db->getRows("select id,name,mcat,price,day,status from " . MEMBERSHIPS . " $where order by id DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
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
            $fid = array($iList['mcat']);
            $category = $db->getVal("SELECT catname FROM " . MCAT . " WHERE id=?", $fid);



            $image = "<img src='" . URL_ROOT . "uploads/slider/" . $iList['image'] . "' width='50'  />";

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
                "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $iList['name'] . "</a>",
                $category,
                $iList['price'],
                $iList['day'],
                $status,
                $button
            );
        }
        $ProDetail["Result"] = $aryData;
    }
    echo json_encode($ProDetail);
    exit;
    //-----------------------------------------------------------------------------------------
} elseif ($_POST && ($pgAct == "edit" || $pgAct == "add")) {
	
	//echo "error==>".$db->lq();print_r($_POST);print_r($_FILES);exit;
            
	//
    $_SESSION['form'] = $_POST;
    $caption = $_POST['caption'];
    $flgEr = FALSE;

    $attr = array();
    $size = array();
    $aryImgData = array();
    $QAattr = array();
    if (is_array($_POST["attr_name"]) && count($_POST["attr_name"]) > 0) {
        for ($i = 0; $i <= count($_POST['attr_name']) - 1; $i++) {
            if ($_POST["attr_name"][$i] != "" && $_POST['attr_value'][$i] != "") {
                $attr[] = array(
                    'attr_name' => $_POST['attr_name'][$i],
                    'attr_value' => $_POST['attr_value'][$i]
                );
            }
        }
        $all_attr = json_encode($attr);
    }
   

    if ($pgAct == "add") {
		//echo "error==><pre>".$db->lq();print_r($_POST);exit;

        $status = 0;$alert_err=array();
        if (isset($_POST["status"]))
            $status = 1;

        if (!isset($_POST['name']) || trim($_POST['name']) == '') {
            $flgEr = TRUE;

            array_push($alert_err, "Please Package Link name.");
        }
		 elseif (!preg_match("#^[-A-Za-z\&\#0-9\;' .]*$#", $_POST['name'])) {

            $flgEr = TRUE;

            array_push($alert_err, "Package name with special characters are not allowed");
        }
		 elseif (!isset($_POST['price']) || trim($_POST['price']) == '') {

            $flgEr = TRUE;

            array_push($alert_err, "Please enter price.");
        } 
		elseif (!is_numeric($_POST['price'])) {

            $flgEr = TRUE;

            array_push($alert_err, "Price must numeric.");
        } 
		elseif ($_POST['day'] != "" && !is_numeric($_POST['day'])) {

            $flgEr = TRUE;
            array_push($alert_err, "Day must numeric.");
        }

        if($flgEr == FALSE){
           
            $status = 0;
            $default = 0;
            $featured = 0;
            $usefor = 0;$addon_service =0; $hard_copy_message = 0;$is_heighlight = 0; $claim_it = 0;
			 $CS_verification = 0;
            if (isset($_POST["status"]))
                $status = 1;
            if (isset($_POST["addon_service"]))
                $addon_service = 1;
				
			if (isset($_POST["is_heighlight"]))
                $is_heighlight = 1;
				
			if (isset($_POST["claim_it"]))
                $claim_it = 1;
			if (isset($_POST["CS_verification"]))
                $CS_verification = 1;
			
            $aryData = array('name' => POST('name'),
                'price' => trim($_POST['price']),
                'day' => trim($_POST['day']),
                'mcat' => POST('mcat'),
				'lorder' => POST('lorder'),
				'status' => $status,
                'detail' => POST('detail'),
                'gift' => POST('gift'),
                
				'hard_copy_message' => $_POST['hard_copy_message'],
				'hard_copy' => $_POST['hard_copy'],
                'text_message' => $_POST['text_message'],
                'high_security_message' => $_POST['high_security_message'],
				'pdf_message' => $_POST['pdf_message'],
                'audio_message' => $_POST['audio_message'],
                'video_message' => $_POST['video_message'],
				'image_message' => $_POST['image_message'],
				
                'addon_service' => $addon_service,
				//'hard_copy_message' => $hard_copy_message,
				'is_heighlight' => $is_heighlight,
				'claim_it' => $claim_it,
				'CS_verification' => $CS_verification,
				'language' => 'en',
                
                
            );

            if (isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"])) {
                $lfilename = basename($_FILES['lpimg']['name']);
                $lext = strtolower(substr($lfilename, strrpos($lfilename, '.') + 1));
                if (in_array($lext, array('jpeg', 'jpg', 'gif', 'png'))) {

                    $lnewfile = md5(microtime()) . "." . $lext;
                    //echo $_FILES['lpimg']['tmp_name'],"../uploads/gallery/".$lnewfile;
                    if (move_uploaded_file($_FILES['lpimg']['tmp_name'], PATH_UPLOAD."content" .DS. $lnewfile)) {
                        $aryData['image'] = $lnewfile;
                    }
                }
            }

            $flgIn = $db->insertAry(MEMBERSHIPS, $aryData);
            if (!is_null($flgIn)) {
                $_SESSION['msg'] = 'Saved Successfully';
                unset($_SESSION['form']);
                echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
            } else {
                echo "error==>" . $db->getErMsg();
            }
        }else{
			echo "error==>yes".$alert_err[0];
		}
    } 
	elseif ($pgAct == "edit" && isset($_POST['id']) && trim($_POST['id']) != '') {
		$status = 0;
            $default = 0;
            $featured = 0;
            $usefor = 0;$addon_service =0; $hard_copy_message = 0;$is_heighlight = 0; $claim_it = 0;
			 $CS_verification = 0;
        $sqlArray = array($_POST['id']);
        if (isset($_POST["status"]))
                $status = 1;
            if (isset($_POST["addon_service"]))
                $addon_service = 1;
				
			
            if (isset($_POST["is_heighlight"]))
                $is_heighlight = 1;
				
			if (isset($_POST["claim_it"]))
                $claim_it = 1;
			if (isset($_POST["CS_verification"]))
                $CS_verification = 1;
			
           
            $aryData = array('name' => POST('name'),
                'price' => trim($_POST['price']),
                'day' => trim($_POST['day']),
                'mcat' => POST('mcat'),
				'lorder' => POST('lorder'),
				'status' => $status,
                'detail' => POST('detail'),
                'gift' => POST('gift'),
                
				'hard_copy_message' => $_POST['hard_copy_message'],
				'hard_copy' => $_POST['hard_copy'],
                'text_message' => $_POST['text_message'],
                'high_security_message' => $_POST['high_security_message'],
				'pdf_message' => $_POST['pdf_message'],
                'audio_message' => $_POST['audio_message'],
                'video_message' => $_POST['video_message'],
				'image_message' => $_POST['image_message'],
				
                'addon_service' => $addon_service,
				//'hard_copy_message' => $hard_copy_message,
				'is_heighlight' => $is_heighlight,
				'claim_it' => $claim_it,
				'CS_verification' => $CS_verification,
				'language' => 'en',
                
                
            );

            if (isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"])) {
                $lfilename = basename($_FILES['lpimg']['name']);
                $lext = strtolower(substr($lfilename, strrpos($lfilename, '.') + 1));
                if (in_array($lext, array('jpeg', 'jpg', 'gif', 'png'))) {

                    $lnewfile = md5(microtime()) . "." . $lext;
                    //echo $_FILES['lpimg']['tmp_name'],"../uploads/gallery/".$lnewfile;
                    if (move_uploaded_file($_FILES['lpimg']['tmp_name'], PATH_UPLOAD."content" .DS. $lnewfile)) {
                        $aryData['image'] = $lnewfile;
                    }
                }
            }
       $sqlArray = array($_POST['id']);
		//echo "error==><pre>".$aryData['image'].$db->lq(); print_r($aryData);echo $db->em();exit;
        $flgUp = $db->updateAry(MEMBERSHIPS, $aryData, "where id=?", $sqlArray);
		 //echo "error==><pre>".$db->lq(); print_r($aryData);echo $db->em();exit;
            //echo "error==>hello<pre>".$all_attr; echo $db->getLastQuery();print_r($aryData);exit;
        //echo "error==>".$_POST['pbody'].$_POST['pbody'];exit;
        //print_r($flgUp); echo $db->getLastQuery();exit;
        if (!is_null($flgUp)) {

            //echo $db->getLastQuery(); exit;
            $_SESSION['msg'] = 'Saved Successfully';
            unset($_SESSION['form']);
            echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
            //echo "success==>msg==>Record save successfully";
        } else {
            echo "error==>" . $db->getErMsg() . $db->getLastQuery();
        }
    }
} elseif ($pgAct == "delete" && isset($_GET['id']) && trim($_GET['id']) != '') {
    $sqlArray = array($_GET['id']);
    $details = $db->getRow("select image from " . MEMBERSHIPS . " where id=?", $sqlArray);
    $res = $db->delete("delete from " . MEMBERSHIPS . " where id=?", $sqlArray);
    if (!is_null($res)) {
        $_SESSION['msg'] = 'Deleted Successfully';
        @unlink(PATH_UPLOAD."content" .DS. $details["image"]);
        redirect(URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
    } else {
        array_push($alert_err, $db->em());
    }
} elseif ($pgAct == "checkdelete") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $sqlArray = array($ids);
            $details = $db->getRow("select image from " . MEMBERSHIPS . " where id=?", $sqlArray);
            @unlink(PATH_UPLOAD."content" .DS. $details["image"]);
            $res = $db->delete("delete from " . MEMBERSHIPS . " where id=?",$sqlArray);
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
            $res = $db->updateAry(MEMBERSHIPS, array("status" => 1), "where id='" . $ids . "'");
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
    }
    if ($pgAct == "add" || ($pgAct == "edit" && isset($_GET['id']) && trim($_GET['id']) != '')) {
        //print_r($_SERVER);
        if ($pgAct == "edit" && !isset($_SESSION['form'])) {
            $sqlUserEditArray = array($_GET['id']);
            $aryForm = $db->getRow("SELECT * FROM " . MEMBERSHIPS . " WHERE id=?", $sqlUserEditArray);
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
                        <script type="text/javascript">
                            var add_tr = '<tr><td ><input type="text" class="st-forminput" style="width:86%;" name="attr_name[]" /></td><td><input type="text" class="st-forminput" style="width:90%;" name="attr_value[]" /></td><td><a class="delete" href="javascript:;">X</a></td></tr>';

                            var add_Qtr = '<tr><td ><input type="text" class="st-forminput" style="width:86%;" name="attr_question[]" /></td><td><input type="text" class="st-forminput" style="width:90%;" name="attr_answer[]" /></td><td><a class="deleteQ" href="javascript:;">X</a></td></tr>';

                            $(document).ready(function () {
                                $("#add").click(function () {
                                    $("#tbody").append($(add_tr));
                                    $('#tbody td a.delete').click(function () {
                                        var rowCount = $('#tbody tr').length;
                                        if (rowCount <= 1) {
                                            alert("This item cannot deleted !!!");
                                        } else {
                                            $(this).parent().parent().remove();
                                        }
                                    });
                                });
                                $('#tbody td a.delete').click(function () {
                                    var rowCount = $('#tbody tr').length;
                                    if (rowCount <= 1) {
                                        alert("This item cannot deleted !!!");
                                    } else {
                                        $(this).parent().parent().remove();
                                    }
                                });

                            });
                            function checkAll(checkboxa) {
                                if ($('#' + checkboxa).is(':checked')) {
                                    $('.' + checkboxa).attr('checked', 'checked');
                                    $('.' + checkboxa).parent('span').addClass('checked');
                                } else {
                                    $('.' + checkboxa).parent('span').removeClass('checked');
                                    $('.' + checkboxa).removeAttr('checked');
                                }
        //alert($('.'+checkboxa).val());
                            }
                        </script>
                        <form  class="form-horizontal form_ajax" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>">
                            <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
                            <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="mcat">Membership Category </label>
                                    <div class="col-lg-10">
                                        <select id="mcat" name="mcat" class="form-control">
                        <?php
                        $pages = $db->getRows("select * from " . MCAT . " where status=1");

                        foreach ($pages as $c) {
                            ?>
                                                <option value="<?php echo $c["id"]; ?>"<?php echo selected($c['id'], $aryForm['mcat']); ?>><?php echo $c["catname"]; ?></option>
        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="Name">Membership Name </label>
                                    <div class="col-lg-10">
                                        <input type="text" name="name" id="name" value="<?php echo $aryForm['name']; ?>" class="form-control"></input>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="Price">Price</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $LinksDetails['currency_symbol']; ?></span>
                                            <input class="form-control" id="Price" value="<?php echo $aryForm['price']; ?>" type="text" name="price" />
                                        </div>
                                    </div>
                                </div>


                                
                                
                                

								<div class="form-group">
                                    <label class="col-lg-2 control-label" for="Day">Gift</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="gift" id="gift" value="<?php echo $aryForm['gift']; ?>" class="form-control"></input>
                                    </div>
                                </div>
                                

                               
                                
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="Day">No of Day</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="day" id="day" value="<?php echo $aryForm['day']; ?>" class="form-control"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="linkname"> Image </label>
                                    <div class="col-lg-10">
                                        <input type="file" name="lpimg" class="form-control" />
        <?php
        if ($_GET['action'] == "edit" && $aryForm['image'] != '') {
            ?>
                                            <br/>
                                            <img src="<?php echo URL_ROOT; ?>uploads/content/<?php echo $aryForm['image']; ?>" width="100" />

                                                <?php
                                            }
                                            ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="detail">Short Detail </label>
                                    <div class="col-lg-10">
                                        <textarea id="detail"  rows="10" class="form-control textarea" name="detail"><?php echo unPOST($aryForm['detail']); ?></textarea>
                                    </div>
                                </div>


                                

                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="useremail"> Custom Attributes </label>
                                    <div class="col-lg-10">
                                        <table border="0" cellpadding="0" id="total_rows" cellspacing="0" class=" table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="titleh">Name</th>
                                                    <th class="titleh" >Value</th>
                                                    <th style="width:10px"></th>
                                                </tr>
                                            </thead>
        <?php
        if (isset($_POST["attr_name"]) && is_array($_POST["attr_name"]) && count($_POST["attr_name"]) > 0) {
            for ($i = 0; $i <= count($_POST["attr_name"]) - 1; $i++) {
                ?>
                                                    <tbody id="tbody">
                                                        <tr id="row_1">
                                                            <td ><input type="hidden" name="attr_id[]" value="<?php echo $_POST["attr_id"][$i]; ?>" />
                                                                <input type="text" class="st-forminput" style="width:86%;" name="attr_name[]" value="<?php echo $_POST["attr_name"][$i]; ?>"  required /></td>

                                                            <td><input type="text" class="st-forminput" style="width:90%;" name="attr_value[]" value="<?php echo $_POST["attr_value"][$i]; ?>"  required  /></td>
                                                            <td><a class="delete" href="javascript:;"> X </a></td>
                                                        </tr>
                                                    </tbody>
            <?php
            }
        } elseif ($_GET["id"] != '') {
            $all_product = json_decode($aryForm['attr']);
            //echo"<pre>";print_r($aryForm['attr']);echo"</pre>";
            if (is_array($all_product) && count($all_product)) {
                $i = '0';
                foreach ($all_product as $iPro) {
                    ?>
                                                        <tbody id="tbody">
                                                            <tr id="row_1">
                                                                <td >
                                                                    <input type="hidden" name="attr_id[]" value="<?php echo $_GET["id"]; ?>" />
                                                                    <input type="text" class="st-forminput" style="width:86%;" name="attr_name[]" value="<?php echo $iPro->attr_name; ?>"  /></td>
                                                                <td><input type="text" class="st-forminput" style="width:90%;" name="attr_value[]" value="<?php echo $iPro->attr_value; ?>"    /></td>
                                                                <td><a class="delete" href="javascript:;"> X </a></td>
                                                            </tr>
                                                        </tbody>
                    <?php
                    $i++;
                }
            } else {
                ?>
                                                    <tbody id="tbody">
                                                        <tr id="row_1">
                                                            <td ><input type="text" class="st-forminput" style="width:86%;" name="attr_name[]"   /></td>

                                                            <td><input type="text" class="st-forminput" style="width:90%;" name="attr_value[]"    /></td>
                                                            <td><a class="delete" href="javascript:;"> X </a></td>
                                                        </tr>
                                                    </tbody>
                <?php }
        } else {
            ?>
                                                <tbody id="tbody">
                                                    <tr id="row_1">
                                                        <td ><input type="text" class="st-forminput" style="width:86%;" name="attr_name[]"   /></td>

                                                        <td><input type="text" class="st-forminput" style="width:90%;" name="attr_value[]"    /></td>
                                                        <td><a class="delete" href="javascript:;"> X </a></td>
                                                    </tr>
                                                </tbody>
        <?php } ?>
                                            <tfoot>
                                                <tr id="add_row">
                                                    <td colspan="2">
                                                        <span style="float:left;"><a id="add" style="cursor:pointer;">Add Row</a></span>

                                                    </td>
                                                    <td width="15%"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>


                                


                                <style>
                                    .table-sortable tbody tr {
                                        cursor: move;
                                    }
                                </style>

                                <script>
                                    $(document).ready(function () {
                                        $("#add_rows").on("click", function () {
                                            // Dynamic Rows Code

                                            // Get max row id and set new id
                                            var newid = 0;
                                            $.each($("#tab_logic tr"), function () {
                                                if (parseInt($(this).data("id")) > newid) {
                                                    newid = parseInt($(this).data("id"));
                                                }
                                            });
                                            newid++;

                                            var tr = $("<tr></tr>", {
                                                id: "addr" + newid,
                                                "data-id": newid
                                            });

                                            // loop through each td and create new elements with name of newid
                                            $.each($("#tab_logic tbody tr:nth(0) td"), function () {
                                                var cur_td = $(this);

                                                var children = cur_td.children();

                                                // add new td and element if it has a nane
                                                if ($(this).data("name") != undefined) {
                                                    var td = $("<td></td>", {
                                                        "data-name": $(cur_td).data("name")
                                                    });

                                                    var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                                                    c.attr("name", $(cur_td).data("name"));
                                                    c.appendTo($(td));
                                                    td.appendTo($(tr));
                                                } else {
                                                    var td = $("<td></td>", {
                                                        'text': $('#tab_logic tr').length
                                                    }).appendTo($(tr));
                                                }
                                            });

                                            // add delete button and td
                                            /*
                                             $("<td></td>").append(
                                             $("<button class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>")
                                             .click(function() {
                                             $(this).closest("tr").remove();
                                             })
                                             ).appendTo($(tr));
                                             */

                                            // add the new row
                                            $(tr).appendTo($('#tab_logic'));

                                            $(tr).find("td button.row-removes").on("click", function () {
                                                $(this).closest("tr").remove();
                                            });
                                        });

                                        $("tr").find("td button.row-removes").on("click", function () {
                                            $(this).closest("tr").remove();
                                        });



                                        // Sortable Code
                                        var fixHelperModified = function (e, tr) {
                                            var $originals = tr.children();
                                            var $helper = tr.clone();

                                            $helper.children().each(function (index) {
                                                $(this).width($originals.eq(index).width())
                                            });

                                            return $helper;
                                        };

                                        $(".table-sortable tbody").sortable({
                                            helper: fixHelperModified
                                        }).disableSelection();

                                        $(".table-sortable thead").disableSelection();



                                        // $("#add_rows").trigger("click");
                                    });

                                </script>

                                <div class="form-group hide">
                                    <label class="col-lg-2 control-label" for="default">Default </label>
                                    <div class="col-lg-10">
                                        <div class="make-switch" data-on="success" data-off="danger" >
                                            <input name="default" type="checkbox" value="1" <?php if ($aryForm['is_default'] == 1) echo "checked"; ?> >
                                            Is Default Membership? </div>
                                    </div>
                                </div>
                                
                                 
                                <div class="form-group">
                                
                                    <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="hard_copy">Hard Copy </label>
                                    <div class="col-lg-7">
                                        <input type="text" name="hard_copy" id="hard_copy" value="<?php echo $aryForm['hard_copy']; ?>" class="form-control"></input>
                                            
                                        
                                    </div>
                                </div>
                                    <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="Day">Text Message</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="text_message" id="text_message" value="<?php echo $aryForm['text_message']; ?>" class="form-control"></input>
                                    </div>
                                    </div>
                                   
                                    <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="Day">Heigh Security Msg</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="high_security_message" id="high_security_message" value="<?php echo $aryForm['high_security_message']; ?>" class="form-control"></input>
                                    </div>
                                    </div>
                                    
                                    
                                </div>
                                
                                
                                <div class="form-group">
                                
                                 <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="Day">PDF</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="pdf_message" id="pdf_message" value="<?php echo $aryForm['pdf_message']; ?>" class="form-control"></input>
                                    </div>
                                    </div>
                                <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="Day">Audio</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="audio_message" id="audio_message" value="<?php echo $aryForm['audio_message']; ?>" class="form-control"></input>
                                    </div>
                                    </div>
                                    
                                    <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="Day">Video</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="video_message" id="video_message" value="<?php echo $aryForm['video_message']; ?>" class="form-control"></input>
                                    </div>
                                    </div>
                                    
                                </div>
                                
                              
                                
                                
                            <div class="form-group "> 
                             <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="Name">Image Message </label>
                                    <div class="col-lg-7">
                                    <input type="text" name="image_message" id="image_message" value="<?php echo $aryForm['image_message']; ?>" class="form-control"></input>
                                    
                                    </div>
                                </div>
                            
                               
 						<div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="linkname"> Add On Service </label>
                                    <div class="col-lg-7">
                                        <div class="make-switch" data-on="success" data-off="danger">
                                            <input name="addon_service" type="checkbox" value="1" <?php if ($aryForm['addon_service'] == 1) echo "checked"; ?> >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="linkname"> Is Heighlight </label>
                                    <div class="col-lg-7">
                                        <div class="make-switch" data-on="success" data-off="danger">
                                            <input name="is_heighlight" type="checkbox" value="1" <?php if ($aryForm['is_heighlight'] == 1) echo "checked"; ?> >
                                        </div>
                                    </div>
                                </div>
                                    
                                
                                
                                
                                </div>
                                
                                

                                <div class="form-group">
                                
                                <div class=" col-lg-4">
                                    <label class="col-lg-5 control-label" for="linkname">Claim It </label>
                                    <div class="col-lg-7">
                                        <div class="make-switch" data-on="success" data-off="danger">
                                            <input name="claim_it" type="checkbox" value="1" <?php if ($aryForm['claim_it'] == 1) echo "checked"; ?> >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="linkname"> Call/SMS Verification </label>
                                    <div class="col-lg-7">
                                        <div class="make-switch" data-on="success" data-off="danger">
                                            <input name="CS_verification" type="checkbox" value="1" <?php if ($aryForm['CS_verification'] == 1) echo "checked"; ?> >
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                
                                <div class="col-lg-4">
                                    <label class="col-lg-5 control-label" for="linkname"> Status </label>
                                    <div class="col-lg-7">
                                        <div class="make-switch" data-on="success" data-off="danger">
                                            <input name="status" type="checkbox" value="1" <?php if ($aryForm['status'] == 1) echo "checked"; ?> >
                                        </div>
                                    </div>
                                </div>
                                
                                </div>



                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="language">Lorder</label>
                                    <div class="col-lg-10" >
                                        <input name="lorder" class="form-control" type="text" id="lorder" value="<?php echo $aryForm['lorder']; ?>" >
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
                        <table data-action="viewAll" data-extra="<?php
        $extraAry = "";
        if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {
            $extraAry = "&status=" . $_REQUEST["status"];
        }
        /* if (isset($_REQUEST["cid"]) && $_REQUEST["cid"] != "") {
          $extraAry = "&cid=".$_REQUEST["cid"];
          } */
        if ($extraAry != "")
            echo "data=yes" . $extraAry;
        ?>" data-page="<?php echo $pgMod ?>" data-table="<?php echo $pgTable ?>" class="table table-widget table-striped" id="mssresulttable" data-export="0,4,5">
                            <thead>
                                <tr>
                                    <th><input onchange="checkAll('checkbox')" id="checkbox" class="row-checkbox" value="<?php echo $iList['id'] ?>" type="checkbox"></th>
                                    <th class="first"><a href="#" title="linkname">Name</a></th>
                                    <th class="first"><a href="#" title="linkname">Category</a></th>
                                    <th><a href="#" title="linkname">Price</a></th>
                                    <th><a href="#" title="linkname">Day</a></th>

                                    <th><a href="#" title="status">Status</a></th>
                                    <th><a href="#" title="status">Action</a></th>


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
                <script>
                    function hide_underof(valu)
                    {
                        if (valu == 1) {
                            $('#section_underof').hide('slow');
                            $("#under").val('0');
                        } else if (valu == 2) {
                            $('#section_underof').show('slow');
                            $('.chosen-container').attr("style", "width:100% !important");
                        } else {
                            alert("please select Page type");
                            $('#section_underof').hide('slow');
                            $("#under").val('0');
                        }
                    }
                </script>
            </div>
        </div>
    </div>

    <?php
}
?>