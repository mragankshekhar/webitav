<?php
$pgMod = "setting_email";
$pgAct = "view";
$pgTable = SETTINGS;
$pgHeading = "Setting Email";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);
if ($_POST) {
	
	if ($pgAct == "add") {
	 $_SESSION['form'] = $_POST;
    $caption = $_POST['caption'];
    $flgEr = FALSE;
    if ($pgAct == "add") {
        $status = 0;
        if (!isset($_POST['field']) || trim($_POST['field']) == '') {
            $flgEr = TRUE;
            echo "error==>Please Enter field Name";
        }elseif (!isset($_POST['input_type']) || trim($_POST['input_type']) == '') {
            $flgEr = TRUE;
            echo "error==>Please select field Name";
        }

        if ($flgEr != TRUE) {
           $aryData=array(	'field'=>$_POST['field'],
						'input_type'=>$_POST['input_type'],
						'page_type'=>$_POST['page_type'],
						
		);
		if($_POST['language']=='en'){
			if($_POST['input_type']=='text'){
				$aryData['en']=$_POST['txt_val'];
				}elseif($_POST['input_type']=='textarea'){
				$aryData['en']=$_POST['txtarea_val'];
				}else{
					if(isset($_FILES["file_val"]["name"]) && !empty($_FILES["file_val"]["name"]))
					{
						$lfilename = basename($_FILES['file_val']['name']);
						$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
						if(in_array($lext,array('jpeg','jpg','gif','png')))
						{
							$lnewfile=md5(microtime()).".".$lext;
							if(move_uploaded_file($_FILES['file_val']['tmp_name'],PATH_MEDIA.DS.$_FILES['file_val']['name']))
							{
								
							//$aryData['en']=$lnewfile;
								$path=	URL_ROOT."uploads/media/".$_FILES['file_val']['name'];
								$aryData['en']=$path;	
							}
						}
					 }
					 //$aryData['en']='';
					}
			
			}elseif($_POST['language']=='hi'){
			if($_POST['input_type']=='text'){
				$aryData['hi']=$_POST['txt_val'];
				}elseif($_POST['input_type']=='textarea'){
				$aryData['hi']=$_POST['txtarea_val'];
				}else{
					if(isset($_FILES["file_val"]["name"]) && !empty($_FILES["file_val"]["name"]))
					{
						$lfilename = basename($_FILES['file_val']['name']);
						$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
						if(in_array($lext,array('jpeg','jpg','gif','png')))
						{
							$lnewfile=md5(microtime()).".".$lext;
							if(move_uploaded_file($_FILES['file_val']['tmp_name'],PATH_MEDIA.DS.$_FILES['file_val']['name']))
							{
								
							//$aryData['en']=$lnewfile;
								$path=	URL_ROOT."uploads/media/".$_FILES['file_val']['name'];
								$aryData['hi']=$path;	
							}
						}
					 }
					}
			
			}
		
		//echo "error==><pre>";print_r($_REQUEST);print_r($aryData);exit;

   
		$flgIn = $db->insertAry(SETTINGS, $aryData);
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
	
} else{
    $lang="en";$err="";
		if(count($_FILES['file']['name'])>0){
			foreach($_FILES['file']['name'] as $key=>$val)
			{
				$lfilename = basename($_FILES['file']['name'][$key]);
				//echo "yes".$lfilename;exit;
				$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
				if(in_array($lext,array('jpeg','jpg','gif','png')))
				{
					if(move_uploaded_file($_FILES['file']['tmp_name'][$key],PATH_MEDIA.DS.$_FILES['file']['name'][$key]))
					{				
						$_POST[$key]=URL_ROOT."uploads/media/".$_FILES['file']['name'][$key];
					}	
				}else{
					$err.=$_FILES['file']['name'][$key]." is in invalid extension";
				}
			}
		}
		//echo "<pre>";print_r($_POST);print_r($_FILES);exit;
		foreach($_POST as $field=>$value)
		{
			$Array = array($lang => POST($value));
			$flgUp = $db->updateAry(SETTINGS, $Array, "field=?", array($field));
			//$flgUp=$db->update("update ".SETTINGS." set `$lang`='".POST($value)."' where `field`='".$field."'");
		}
		$_SESSION["msg"] = "Updated Successfully " ;
		echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => "setting")));
		
}
} elseif ($pgAct == "delete" && isset($_GET['id']) && trim($_GET['id']) != '') {
	//echo "error==><pre>";print_r($_REQUEST);exit;
    $sqlArray = array($_GET['id']);
    $details = $db->getRow("select username,email,avatar from " . SETTINGS . " where id=?", $sqlArray);
    $res = $db->delete("delete from " . SETTINGS . " where field=?", $sqlArray);
	//echo "error==><pre>".$sqlArray;print_r($_REQUEST);echo $db->lq();exit;
    if (!is_null($res)) {
        $_SESSION['msg'] = 'Deleted Successfully';
        @unlink("../uploads/media/" . $details["avatar"]);
        redirect(URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
    } else {
        array_push($alert_err, $db->em());
    }
} else {
    if (isset($_SESSION['form'])) {
        $aryForm = $_SESSION['form'];
        unset($_SESSION['form']);
    } else {
		$sqlData=array($pgMod);
        $aryFormTemp = $db->getRows("select * from " . SETTINGS . " where page_type= ?",$sqlData);
        if (!is_null($aryFormTemp) && is_array($aryFormTemp) && count($aryFormTemp) > 0) {
            foreach ($aryFormTemp as $iFormTemp) {
                $aryForm[$iFormTemp['field']] = unPOST($iFormTemp['en'])."==>".$iFormTemp["input_type"];
            }
        }
    }
	//echo $db->lq();
    ?>
	
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo ucwords($pgAct); ?> Setting </div>
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
					if ($pgAct == "add" ) {
                        //print_r($_SERVER);
                        
                        if (isset($_SESSION['form'])) {
                            $aryForm = $_SESSION['form'];
                            unset($_SESSION['form']);
                        }
                        echo $db->em();
                        $aryFrmAct = array("page_id" => $pgMod, "action" => $pgAct);
                        
                        ?>
                        <style>
                            .chosen-container{width:100% !important}
                        </style>
                        <script>
                        function field_type(id){
							//alert(id);
							if(id=='textarea'){
								$("#textareavalue").show();
								$("#textvalue").hide();
								$("#filevalue").hide();
								}
								else if(id=='image'){
								$("#filevalue").show();
								$("#textvalue").hide();
								$("#textareavalue").hide();
								}else{
									$("#textvalue").show();
								$("#filevalue").hide();
								$("#textareavalue").hide();
									}
							}
                        </script>
                        <form  class="form-horizontal form_ajax" enctype="multipart/form-data" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>">
              <input type="hidden" name="page_type" value="<?php echo $_GET["page_id"] ?>" />
              <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
              
              <div class="form-group">
                  <label class="col-lg-2 control-label" for="language">Language</label>
                  <div class="col-lg-10">
                    <select id="language" name="language" class="form-control" >
                    <?php
                    $pages=$db->getRows("select * from ".LANGUAGE." where status=1");
                    foreach($pages as $c){
                    ?>
                    <option value="<?php echo $c["code"]; ?>"<?php echo selected($c['code'],$_GET['language']); ?>><?php echo $c["name"]; ?></option>
                    <?php } ?>
                    </select>
                  </div>
                </div>
              
              
              <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Field Name </label>
                  <div class="col-lg-10">
                    <input type="text" name="field" id="field" value="<?php echo unPOST($aryForm['field']); ?>" class="form-control" />
                  </div>
                </div>	
                
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="heading"> Field Type </label>
                  <div class="col-lg-10">
                    <select id="input_type" name="input_type" class="form-control"  onchange="field_type(this.value)">
                   <option value="text">Text Box</option>
                   <option value="textarea">TextArea</option>
                   <option value="image">Image</option>
                    </select>
                  </div>
                </div>	
                
                
                 <div class="form-group" id="textvalue">
                  <label class="col-lg-2 control-label" for="heading"> Value </label>
                  <div class="col-lg-10">
                    <input type="text" name="txt_val" id="txt_val"  class="form-control" />
                  </div>
                </div>	
                 <div class="form-group" style="display:none" id="textareavalue">
                  <label class="col-lg-2 control-label" for="heading"> Value </label>
                  <div class="col-lg-10">
                    <textarea name="txtarea_val" id="txtarea_val"  class="form-control"  ></textarea>
                  </div>
                </div>	
                 <div class="form-group" style="display:none" id="filevalue">
                  <label class="col-lg-2 control-label" for="heading"> Value </label>
                  <div class="col-lg-10">
                    <input type="file" name="file_val" id="file_val"  class="form-control" />
                  </div>
                </div>	
                
                         
                <div class="form-group">
                  <label class="col-lg-2 control-label" for="submit">&nbsp; </label>
                  <div class="col-lg-10">
                    <input class="submit btn btn-blue" type="submit" value="Submit" />
                  </div>
                </div></div>
                </div>
				        
			</form>
                        <?php
                    }else{
                    ?>
                    <form  class="form-horizontal form_ajax" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>" enctype="multipart/form-data">
                        
						
				<!--<div class="form-group">
                  <label class="col-lg-2 control-label" for="language">Language</label>
                  <div class="col-lg-10">
                    <select id="language" name="language" class="form-control" onchange="window.location='?page_id=<?php echo $pgMod; ?>&language='+this.options[this.selectedIndex].value;">
                    <?php
                    $pages=$db->getRows("select * from ".LANGUAGE." where status=1");
                    foreach($pages as $c){
                    ?>
                    <option value="<?php echo $c["code"]; ?>"<?php echo selected($c['code'],$_GET['language']); ?>><?php echo $c["name"]; ?></option>
                    <?php } ?>
                    </select>
                  </div>
                </div>-->
                        
						
						<?php
                        foreach ($aryForm as $key => $value) {
							$newVal=explode("==>",$value);
                            ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="<?php echo $key; ?>"><?php echo ucfirst(str_replace("_", " ", $key)); ?></label>
                                <div class="col-lg-9" >
                                <?php
                                if($newVal[1]=="textarea"){
								?>
                                    <textarea name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control" ><?php echo unPOST($newVal[0]); ?></textarea>
                                <?php
								}elseif($newVal[1]=="text"){
								?>
                                    <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control" value="<?php echo unPOST($newVal[0]); ?>">
                                <?php
								}elseif($newVal[1]=="image"){
								?>
                                    <input type="file" name="file[<?php echo $key; ?>]" id="<?php echo $key; ?>" class="form-control"	>
                                   
                                <?php 
									if($newVal[0]!=""){
										?><img src="<?php echo $newVal[0] ?>"  style=" max-height:100px"/><?php
									}
								}
								$aryPgAct['page_id']="setting";
								$aryPgAct['action']="delete";
								$aryPgAct['id']=$key;
								
								
								?>
                                </div>
                                <a href="javascript:;" onclick="del('<?php echo  URL_ADMIN_HOME . getQueryString($aryPgAct)?>')" style="color:#FFF"><span style="float:right; margin-right:10px !important" class="sort-handler btn btn-danger btn-sm " ><i class="fa fa-times"></i>
                                </span></a>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="submit">&nbsp; </label>
                            <div class="col-lg-10">
                                <input class="submit btn btn-blue" type="submit" value="Submit" />
                            </div>
                        </div>
                    </form>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>