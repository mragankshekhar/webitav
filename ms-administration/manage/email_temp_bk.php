<?php 
$pgMod="email_template"; 
$pgAct="view";
if(isset($_REQUEST['action']) && trim($_REQUEST['action'])!='')
	$pgAct=strtolower($_REQUEST['action']);	

if($_POST){
	if(isset($_POST['id']) && $_POST['id']!='')
		$_GET["id"]=strtolower($_POST['id']);
	if($pgAct=="edit"){
		$UpdateAry=array("subject"=>POST("subject"),
						"msg_for"=>POST("msg_for"),
						"from_email"=>POST("from_email"),
						 "msg"=>POST("msg"));
		$new=$db->updateAry(MAILMSG,$UpdateAry,"where id=".$_GET["id"]);
		$_SESSION['msg']='Saved Successfully';
		echo "success==>".(URL_ADMIN_HOME.getQueryString(array("page_id"=>$pgMod)));
	}elseif($pgAct=="add"){
		$addAry=array("subject"=>POST("subject"),
						"msg_for"=>POST("msg_for"),
						"from_email"=>POST("from_email"),
						 "msg"=>POST("msg"));
		$new=$db->insertAry(MAILMSG,$addAry);
		$_SESSION['msg']='update Successfully';
		echo "success==>".(URL_ADMIN_HOME.getQueryString(array("page_id"=>$pgMod)));
	}}
elseif($pgAct=="delete" && isset($_GET['id']) && trim($_GET['id'])!='')
{
		$res=$db->delete(MAILMSG,"where id='".$_GET['id']."'");
		if(!is_null($res)) { $_SESSION['msg']='Deleted Successfully'; }
		else { array_push($alert_err,$db->getErMsg()); }
		redirect(URL_ADMIN_HOME.getQueryString(array("page_id"=>$pgMod)));
}
else{
?>
<div class="row">
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-heading">
              <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo ucwords($pgAct); ?> Email Template </div>
              <div class="messenger-header-actions pull-right hide">
                    <button type="button" onclick="window.location='<?php echo URL_ADMIN_HOME.getQueryString(array("page_id"=>$pgMod,"action"=>"add")); ?>'" class="btn btn-default btn-gradient dropdown-toggle" data-toggle="dropdown"> <span class="glyphicons glyphicons-circle_plus padding-right-sm"></span> Add new </button>
              </div>
            </div>
            <div class="panel-body">
		<?php
		if(isset($_SESSION["msg"])){
		?>
		<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<strong>Well done!</strong> <?php echo $_SESSION["msg"]; ?>. </div>
		<?php unset($_SESSION["msg"]);
		}
		if($pgAct=="add" || ($pgAct=="edit" && isset($_GET['id']) && trim($_GET['id']) !=''))
		{
			if($pgAct=="edit" && !isset($_SESSION['form']))
			{
				$sqlMailMsgArray=array($_GET["id"]);
				$aryForm=$db->getRow("select * from ".MAILMSG." where id=?",$sqlMailMsgArray);
			}
			if(isset($_SESSION['form']))
			{
				$aryForm=$_SESSION['form'];
				unset($_SESSION['form']);
			}
			$aryFrmAct=array("page_id"=>$pgMod, "action"=>$pgAct);
			if($pgAct=="edit") $aryFrmAct['id']=$_GET['id'];
			?>
			<form  class="form-horizontal" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>">
      		<input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
            <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
			<div class="form-group">
                  <label class="col-lg-2 control-label" for="msg_for"> Message For </label>
                  <div class="col-lg-10">
                    <input type="text" name="msg_for" id="msg_for" value="<?php echo unPOST($aryForm['msg_for']); ?>" class="form-control" <?php if($pgact=="edit"){ ?>readonly="readonly" <?php } ?> />
                  </div>
           </div>
        	<div class="form-group">
                  <label class="col-lg-2 control-label" for="from_email"> Message From </label>
                  <div class="col-lg-10">
                    <input type="text" name="from_email" id="from_email" value="<?php echo unPOST($aryForm['from_email']); ?>" class="form-control" />
                  </div>
            </div>
        	<div class="form-group">
                  <label class="col-lg-2 control-label" for="subject"> Subject </label>
                  <div class="col-lg-10">
                    <input type="text" name="subject" id="subject" value="<?php echo unPOST($aryForm['subject']); ?>" class="form-control" />
                  </div>
            </div>
        
 		<div class="form-group">
                  <label class="col-lg-2 control-label" for="msg"> Body </label>
                  <div class="col-lg-10">
                    
                    <textarea class="form-control ckeditor" rows="10" name="msg"><?php echo unPOST($aryForm['msg']); ?></textarea>
                  </div>
                </div>
        
		<div class="form-group">
                  <label class="col-lg-2 control-label" for="submit">&nbsp; </label>
                  <div class="col-lg-10">
                    <input class="submit btn btn-blue" type="submit" value="Submit" />
                  </div>
                </div>
    <pre><strong>Short code</strong> <br />
    [MESSAGE]=`message body`<br />
    [ADMIN]=`admin name`<br />
    [LOGIN]=`Login Address` <br />
    [SITE]=`Site name` <br />
	[SUBJECT]=`Default subject` <br />
	[DATE]= `Current Date`
    </pre>
</form>
<?php }else{ 

?> 	
    <?php
    $aryPgAct=array("page_id"=>$pgMod,"action"=>"add");
	$sqlLimit="SELECT * FROM ".MAILMSG;
	$aryList=$db->getRows($sqlLimit);
	if(is_array($aryList))
	{
	  if(count($aryList)>0)
	   { 
	     ?> 
		 <table  class="table table-striped table-bordered table-hover" id="datatable">
       	   <thead>
            <tr>
            	<th><a href="#" title="linkname">Sr. No.</a></th>
            	<th><a href="#" title="linkname">Email For</a></th>
                <th><a href="#" title="linkname">Subject</a></th>
                <th><a href="#" title="linkname">Status</a></th>
                <th class="last">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
			$i=0;
			foreach($aryList as $iList)
			{
				$aryPgAct["id"]=$iList['id'];
			 ?>
             <tr>
             <td><?php $i++; echo $i; ?></td>
              <td><?php echo $iList['msg_for']; ?></td>
              <td><?php echo $iList['subject']; ?></td>
             <td nowrap="nowrap" width="10%" ><?php if($iList["status"]==0){ ?>
                    <span class="label btn-red2 margin-right-sm">Inactive</span>
                    <?php }else{ ?>
                    <span class="label btn-green">Active</span>
                    <?php } ?></td>
               <td class="last">
            <div class="btn-group">
                   <?php
				 	$aryPgAct['action']="edit";
				 ?>
                        <button onClick="loc('<?php echo URL_ADMIN_HOME.getQueryString($aryPgAct); ?>')" type="button" class="btn btn-info btn-gradient"> <span class="glyphicons glyphicons-edit"></span> </button>
                        <?php
					 $aryPgAct['action']="delete";
					 ?>
                        <button type="button" onClick="del('<?php echo URL_ADMIN_HOME.getQueryString($aryPgAct); ?>')" class="btn btn-danger btn-gradient"> <span class="glyphicons glyphicons-delete"></span> </button>
                        
                      </div>
                      </td>
               </tr>
               <?php
			}
			   ?>
            </tbody>
         </table>
	  <?php
	   }
	    else
		{
		  echo '<div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>Notice !</strong> Sorry No record Found. </div>';
		}
	}
	else
	{
		echo '<div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>error !</strong> '.$db->getErMsg().'. </div>';
	}
	  ?>
            
        
<?php } ?>
</div></div></div></div>
<?php } ?>