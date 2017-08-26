<?php
$pgMod="media";
$pgAct="view";
$strFileName='';
if(isset($_REQUEST['action']) && trim($_REQUEST['action'])!='')
	$pgAct=strtolower($_REQUEST['action']);	

if($_POST)
{
	
	if(isset($_POST['id']) && $_POST['id']!='')
		$_GET["id"]=strtolower($_POST['id']);
	
	$flgEr=FALSE;
	$ext='jpeg';

	if($flgEr!=TRUE && $pgAct=="add" && count($alert_err)==0)
	{
		
	

if(isset($_FILES["avatar"]["name"]) && !empty($_FILES["avatar"]["name"]))
		{
			//echo "error==>aaaaahelloee";print_r($_POST);print_r($_FILES);exit;	
			$lfilename = basename($_FILES['avatar']['name']);
			$lext = strtolower(substr($lfilename, strrpos($lfilename, '.')+1));
			if(!in_array($lext,array('exe','php')))
			{
				if(move_uploaded_file($_FILES['avatar']['tmp_name'],"../uploads/media/".$_FILES['avatar']['name']))
				{
					$_SESSION['msg']='Saved Successfully';
					echo "success==>".(URL_ADMIN_HOME.getQueryString(array("page_id"=>$pgMod)));
					//echo "success==>" . (URL_ADMIN_HOME . getQueryString(array("page_id" => $pgMod)));
				}
			}
			else
			{
				echo "error==>Invalid file extension you can not uplaod $lext file extension";
			}	
		}
		else
		{
			echo "error==>".$db->getErMsg();
		}			
	}
	else{
		$error="";
		foreach($alert_err as $err)
			$error.=$err."<br />";
		echo "error==>:".$error.$pgAct;
	}
}
elseif($pgAct=="delete"){
	unlink("../uploads/media/".$_GET["url"]);
	redirect(URL_ADMIN_HOME.getQueryString(array("page_id"=>$pgMod)));
}
else{
	?>
    	<div class="row">
        <div class="col-md-12">
          <div class="panel">
            <div class="panel-heading">
              <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo ucwords($pgAct); ?> File </div>
              <div class="messenger-header-actions pull-right">
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
			$aryFrmAct=array("page_id"=>$pgMod, "action"=>$pgAct);
			
			if($pgAct=="edit") $aryFrmAct['id']=$_GET['id'];
		?>
				   
			  <form  class="form-horizontal form_ajax" role="form" id="signupForm" method="post" action="?page_id=<?php echo $pgMod; ?>" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>" />
              <input type="hidden" name="action" value="<?php echo $_GET["action"] ?>" />
			    <div class="form-group">
                  <label class="col-lg-2 control-label" for="lorder"> Select File</label>
                  <div class="col-lg-10">
                    <input type="file" name="avatar" id="avatar" class="form-control" />
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
else
{
	$dir	= '../uploads/media';
	$aryList = scandir($dir);
	if(is_array($aryList) && count($aryList)>0)
	{
	   
	     ?> 
         
		<table  class="table table-striped table-bordered table-hover" id="datatable">
       	   <thead>
            <tr>
            	<td></td>
                <th><a href="#" title="Image">File URL</a></th>
                <th class="last">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
			
			foreach($aryList as $iList)
			{
				$fileT=explode(".",$iList);
				//$img[0] for name $img[1] for extention 
				if($fileT[1]!=""){
				$aryPgAct['url']=$iList;
				$aryPgAct['page_id']=$pgMod;
			 ?>
             <tr>
             <td><img style="height:30px; width:30px" src="<?php echo href("image.php","file_name=".$iList."&path=media"); ?>" /></td>
             <td ><?php echo URL_ROOT."uploads/media/".$iList; ?></td>
              <td class="last">
             <div class="btn-group">
                   <?php
				 	$aryPgAct['action']="delete";
				 ?>
                        <button onClick="del('<?php echo URL_ADMIN_HOME.getQueryString($aryPgAct); ?>')" type="button" class="btn btn-info btn-gradient"> <span class="glyphicons glyphicons-delete"></span> </button>
                       
                      </div>            	
                 </td>
             </tr>
              <?php			
			  }
			}
			?>
            </tbody>
         </table>
	  <?php
	   
	}
	else
	{ echo '<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Notice !</strong> Sorry No record Found. </div>';
	}
}
?></div></div></div></div>
<?php
if(isset($_GET['id']) && $_GET['id']!="" && $action='edit')
	$aryPgAct=array("page_id"=>$pgMod,"action"=>"edit","id"=>$_GET['id']);
else
	$aryPgAct=array("page_id"=>$pgMod,"action"=>"add");
}
?>
