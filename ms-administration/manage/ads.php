<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$pgMod = "ads";
$pgAct = "view";
$pgTable = ADS;
$pgHeading = "Ads";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {
    include_once("../../config.php");
	
	
	
    $pgTable = ADS;
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
  /*  if (isset($_REQUEST["date"]) && $_REQUEST["date"] != "") {
        $daterange = explode("-", $_REQUEST["date"]);
        $from = date("Y-m-d", strtotime($daterange[0]));
        $to = date("Y-m-d", strtotime($daterange[1]));
        array_push($sqlArray, $from);
        array_push($sqlArray, $to);
        $whereAry[] = " (date between ? and ?)";
    }*/
	
    if (isset($_REQUEST["q"]) && $_REQUEST["q"] != "") {
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
		array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
		array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
		array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        
        $whereAry[] = " (heading like ? or location like ? or platform like ? or file like ? or type like ? or date like ? ) ";
    }
   /* if (isset($_REQUEST["cid"]) && $_REQUEST["cid"] != "") {
        array_push($sqlArray, $_REQUEST["cid"]);
        $whereAry[] = " (c_type = ?) ";
    }*/
    if (is_array($whereAry) && count($whereAry) > 0)
        $where = " WHERE " . implode(" AND ", $whereAry);

    $pcount = $db->getVal("select count(id) from  " . ADS . " $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;
  //echo "sss"; exit;
     $contentDetail = $db->getRows("select id,heading,date,type,location,status,platform,file from " . ADS . " $where order by id DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
	 
	 //echo "test"; exit;
	 
   //echo "error==>"."<pre>";print_r($contentDetail); echo $db->getlq(); exit;
	 
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
			
			$name=$db->getRows("select location from ".ADS);
			
			//echo "sdfndf"; "error==>";print_r($name); exit;
			if(count($name)>0){
				foreach($name as $n){
					$location= $db-> getVal(" select heading from ".CONTENT_TYPE." where id  = ".$n['location'] );
			// echo "gggg"."error==>";echo $location; 
				}
			//exit;
			}
			
			if($iList["location"]==1){ 
			 $location= 'header';
			}elseif($iList["location"]==2){
			 $location= 'Footer';
			}elseif($location!=''){ //echo $location;
			} 
			
			
			if($iList["type"]==1){ 
			 $type= 'Image';
			}else{
			 $type= 'Flash';
			}
			
			if($iList['type']==1){
            	$image = "<img src='" . URL_ROOT . "uploads/ads/" . $iList['file'] . "' width='50'  />";
			}else{
				$image = "<object data='" . URL_ROOT . "uploads/ads/" . $iList['file'] . "' width='50' height ='80px' /> </object>";
			}
			
			
    
	         if($iList["platform"]==1){ 
			 $platform= 'Website';
			 }else if($iList["platform"]==1) {
				 
				 $platform= 'Mobile'; }else{$platform= 'Both'; }
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
				
                "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $type. "</a>",
                $iList["heading"],
				$location,
				
				$platform,
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
		
		   // $header = 0;
            //$footer = 0;
            //$content = 0;
        $status = 0;
        if (isset($_POST["status"]))
            $status = 1;
			
			/*if (isset($_POST["header"]))
                $header = 1;
            if (isset($_POST["footer"]))
                $footer = 1;
			if (isset($_POST["content"]))
                $content = 1;
*/
        if (!isset($_POST['type']) || trim($_POST['type']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Select  Type ";
        } elseif (!isset($_POST['heading']) || trim($_POST['heading']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter Name/heading";
        }
		
		
        if (isset($_POST['date']) || trim($_POST['date']) != '') {
			
			//echo "error==>"."iiii"; exit; 
            $date=$_POST['date'];
			//echo "error==>".$date; exit; 
        }else{ $date=date('Y-m-d');}

        if ($flgEr != TRUE) {
            $aryData = array('status'=>$status,
						'heading'=>$_POST['heading'],
						'type'=>POST('type'),
						'location'=>POST('location'),
						'platform'=>POST('platform'),
						'url'=>$_POST['url'],
						'date'=> $date,
						
            );

            if (!isset($_POST['type']) || trim($_POST['type']) == 1 ) {
            	$flgEr = TRUE;
				if(isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"]))
				{
					$lfilename = basename($_FILES['lpimg']['name']);
					$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
					if(in_array($lext,array('jpeg','jpg','gif','png')))
					{
						$lnewfile=md5(microtime()).".".$lext;
						if(move_uploaded_file($_FILES['lpimg']['tmp_name'],"../uploads/ads/".$lnewfile))
						{
							$aryData['file']=$lnewfile;	
						}
					} 
					else {  echo "error==>Please Enter jpeg,jpg,gif,png image"; exit; }
				 }
				 
			}
	
	        elseif (!isset($_POST['type']) || trim($_POST['type']) == 2 ) {
            	$flgEr = TRUE;
				if(isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"]))
				{
					
					$lfilename = basename($_FILES['lpimg']['name']);
					$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
					if(in_array($lext,array('swf')))
					{
						
						$lnewfile=md5(microtime()).".".$lext;
						if(move_uploaded_file($_FILES['lpimg']['tmp_name'],"../uploads/ads/".$lnewfile))
						{
							$aryData['file']=$lnewfile;	
						}
						
						
					} 
					else {  echo "error==>Please Enter swf";  exit;  }
				 }
				 
			}
			
			else{
				 $flgEr = TRUE;
				 echo "error==>Please select ads type"; exit;
			}
			
            /*if($_POST['location']==1){
				$sqlArray = array(1,1);
				$con = $db->getRow("select * from " . ADS . " where location=? and status=?", $sqlArray);
				if(isset($_POST['location']) && is_array($con) && count($con) >0 ){
					 $flgEr = TRUE;
					echo "error==>Header already Exist"; exit;
				}
			}elseif($_POST['location']==2){
				$sqlArrayy = array(2,1);
				$conn = $db->getRow("select * from " . ADS . " where location=? and status=?" , $sqlArrayy);
				if(isset($_POST['location']) && count($conn)>0){
					 $flgEr = TRUE;
					echo "error==>Footer already Exist"; exit;		 
				}
			}*/
			
			$flgIn = $db->insertAry(ADS, $aryData); 
            if (!is_null($flgIn)) {
                $_SESSION['msg'] = 'Saved Successfully';
                unset($_SESSION['form']);
                echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod, "cid" => $_POST['cid'])));
            } else {
				 $flgEr = TRUE;
                echo "error==>" . $db->em();
            }
        }
    } elseif ($pgAct == "edit" && isset($_POST['id']) && trim($_POST['id']) != '') {
        $sqlArray = array($_POST['id']);
		
		   /* $header = 0;
            $footer = 0;
            $content = 0;*/
            $status = 0;
        if (isset($_POST["status"]))
            $status = 1;
			
			/*if (isset($_POST["header"]))
                $header = 1;
            if (isset($_POST["footer"]))
                $footer = 1;
			if (isset($_POST["content"]))
                $content = 1;
		*/
       
        if (!isset($_POST['type']) || trim($_POST['type']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Select Type ";
        } elseif (!isset($_POST['heading']) || trim($_POST['heading']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter Name/heading";
        }

        if ($flgEr != TRUE) {
            $socailData = json_encode($_POST["social"]);
            $aryData = array('status'=>$status,
			             
						/* 'header'=>$header,
						 'footer'=>$footer,
						 'content'=>$content, */
						'heading'=>$_POST['heading'],
						'type'=>POST('type'),
						'location'=>POST('location'),
						'platform'=>POST('platform'),
						'url'=>$_POST['url'],
						'date'=> date('Y-m-d'),
						
            );

            if (!isset($_POST['type']) || trim($_POST['type']) == 1 ) {
            $flgEr = TRUE;
           
       
		if(isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"]))
		{
			
			$lfilename = basename($_FILES['lpimg']['name']);
			$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
			if(in_array($lext,array('jpeg','jpg','gif','png')))
			{
				
				$lnewfile=md5(microtime()).".".$lext;
				if(move_uploaded_file($_FILES['lpimg']['tmp_name'],"../uploads/ads/".$lnewfile))
				{
					$aryData['file']=$lnewfile;	
				}
				
				
			} 
			else {  echo "error==>Please Enter jpeg,jpg,gif,png image"; }
		 }
		 
    }
	
	        if (!isset($_POST['type']) || trim($_POST['type']) == 2 ) {
            $flgEr = TRUE;
           
       
		if(isset($_FILES["lpimg"]["name"]) && !empty($_FILES["lpimg"]["name"]))
		{
			
			$lfilename = basename($_FILES['lpimg']['name']);
			$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
			if(in_array($lext,array('swf')))
			{
				
				$lnewfile=md5(microtime()).".".$lext;
				if(move_uploaded_file($_FILES['lpimg']['tmp_name'],"../uploads/ads/".$lnewfile))
				{
					$aryData['file']=$lnewfile;	
				}
				
				
			} 
			else {  echo "error==>Please Enter swf"; }
		 }
		 
    }
			//if( $flgEr!= TRUE){
				$sqlArray = array($_POST['id']);
	
				$flgUp = $db->updateAry(ADS, $aryData, "where id=?", $sqlArray);
				if (!is_null($flgUp)) {
					$_SESSION['msg'] = 'Saved Successfully';
					unset($_SESSION['form']);
					echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod, "cid" => $_POST['cid'])));
				} else {
					echo "error==>" . $db->em();
				}
			//}else{
				//echo "error==>some errors" . $db->em();
			//}
        }
    }
} elseif ($pgAct == "delete" && isset($_GET['id']) && trim($_GET['id']) != '') {
    $sqlArray = array($_GET['id']);
   
    $res = $db->delete("delete from " . ADS . " where id=?", $sqlArray);
    if (!is_null($res)) {
        $_SESSION['msg'] = 'Deleted Successfully';
        @unlink("../uploads/ads/" . $details["file"]);
        redirect(URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod, "cid" => $_POST['cid'])));
    } else {
        array_push($alert_err, $db->em());
    }
} elseif ($pgAct == "checkdelete") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $sqlArray = array($ids);
            
            @unlink("../uploads/ads/" . $details["file"]);
            $res = $db->delete(ADS, "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkinactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(ADS, array("status" => 0), "where id='" . $ids . "'");
        }
    }
} elseif ($pgAct == "checkactive") {
    if (is_array($_POST["ids"]) && count($_POST["ids"]) > 0) {
        foreach ($_POST["ids"] as $ids) {
            $res = $db->updateAry(ADS, array("status" => 1), "where id='" . $ids . "'");
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
                            <button type="button" onclick="window.location = '<?php echo URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod, "action" => "add", "cid" => $_GET['cid'])); ?>'" class="btn btn-default btn-gradient dropdown-toggle" data-toggle="dropdown"> <span class="glyphicons glyphicons-circle_plus padding-right-sm"></span> Add new </button>
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
            $aryForm = $db->getRow("SELECT * FROM " . ADS . " WHERE id=?", $sqlUserEditArray);
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
                    <input type="text" name="heading" id="heading" value="<?php echo unPOST($aryForm["heading"]); ?>" class="form-control" />
                  </div>
                </div>	
                
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Type </label>
                  <div class="col-lg-10">
                    <select name="type" class="form-control" id="type"  >
                    <option value="0"  selected>Select</option>
                    <option value="1" <?php if($aryForm['type']=='1') echo 'selected'?>>Image</option>
                    <option value="2" <?php if($aryForm['type']=='2') echo 'selected'?>>Flash</option>
                    
                    </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="linkname"> Image/File </label>
                  <div class="col-lg-10">
                    <input type="file" name="lpimg" class="form-control" />
					<?php
					  if($_GET['action']=="edit" && $aryForm['file']!='')
					  {
						
						 ?>
						  <br/>
                          
                          <?php if($aryForm['type']==1){ ?>
						  <img src="../uploads/ads/<?php echo $aryForm['file'];?>" width="100" />            <?php }elseif($aryForm['type']==2) {?>
                          <object width="400" height="50" data="../uploads/ads/<?php echo $aryForm['file'];?>"></object> <?php }?>
						   
						  <?php
					  }
					  ?>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Platform </label>
                  <div class="col-lg-10">
                    <select name="platform" class="form-control" id="platform"  >
                    <option value="0" class="form-control" selected >Select</option>
                    <option value="1" <?php if($aryForm['platform']=='1') echo 'selected'?>>Web</option>
                    <option value="2" <?php if($aryForm['platform']=='2') echo 'selected'?>>Mobile</option>
                    <option value="3" <?php if($aryForm['platform']=='3') echo 'selected'?>>Both</option>
                    </select>
                  </div>
                </div>
                    
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Location </label>                 
                  <div class="col-lg-10">
                    <select name="location" class="form-control" id="platform"  >
                
                   <?php 
				   $sqlUserEditArray = array(1);
				   $content_type=$db->getRows("select * from ".CONTENT_TYPE." where status =? ",$sqlUserEditArray); 
				  // echo "error==>"."hhhhh"; print_r($content_type); echo $db->lq();echo $db->em(); exit;
				   ?> 
                    <option value="0" class="form-control" selected >Select</option>
                    <option value="1" <?php if($aryForm['location']=='1') echo 'selected'?>>Header</option>
                    <option value="2" <?php if($aryForm['location']=='2') echo 'selected'?>>Footer</option>
                    <!--<option value="3" <?php //if($aryForm['location']=='3') echo 'selected'?>>Left</option>-->
                    <option value="4" <?php if($aryForm['location']=='4') echo 'selected'?>>Right</option>
                    
                    <option value="9" <?php if($aryForm['location']=='9') echo 'selected'?>>Content_HeadOne</option>
                    <option value="10" <?php if($aryForm['location']=='10') echo 'selected'?>>Content_HeadTwo</option>
                    <option value="11" <?php if($aryForm['location']=='11') echo 'selected'?>>Below_Contenthead</option>
                    <option value="12" <?php if($aryForm['location']=='12') echo 'selected'?>>Ads</option>
                    <option value="13" <?php if($aryForm['location']=='13') echo 'selected'?>>News</option>
                    
                    
                    <option value="14" <?php if($aryForm['location']=='14') echo 'selected'?>>WELCOME</option>
                    
                    
                    <?php foreach($content_type as $con){?>
                    <option value="<?php echo $con['id']; ?>" <?php if ($con['id'] == $aryForm['location']) echo 'selected' ; ?> ><?php echo $con['heading']; ?></option>
                    
                    <?php }?>
                    
                    </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Url </label>
                  <div class="col-lg-10">
                    <input type="text" name="url" id="url" value="<?php echo unPOST($aryForm["url"]); ?>" class="form-control" />
                  </div>
                </div>
                
                 <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Date </label>
                  <div class="col-lg-10">
                    <input type="date" name="date" id="date" value="<?php echo unPOST($aryForm['date']); ?>" class="form-control" />
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
                        <table data-action="viewAll" data-extra="<?php
                        $extraAry = "";
                        if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {
                            $extraAry = "&status=" . $_REQUEST["status"];
                        }
                        if (isset($_REQUEST["cid"]) && $_REQUEST["cid"] != "") {
                            $extraAry = "&cid=" . $_REQUEST["cid"];
                        }
                        if ($extraAry != "")
                            echo "data=yes" . $extraAry;
                        ?>" data-page="<?php echo $pgMod ?>" data-table="<?php echo $pgTable ?>" class="table table-widget table-striped" id="mssresulttable" data-export="0,4,5">
                        
                           
                            <thead>
                                <tr>
                                    <th><input onchange="checkAll('checkbox')" id="checkbox" class="row-checkbox" value="<?php echo $iList['id'] ?>" type="checkbox"></th>
                                    <th class="first"><a href="#" title="linkname">Type</a></th>
                                    <th class="first"><a href="#" title="linkname">Heading</a></th>
                                    <th class="first"><a href="#" title="linkname">Location</a></th>
                                    <th class="first"><a href="#" title="linkname">Platform</a></th>
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