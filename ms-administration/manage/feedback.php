<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$pgMod = "feedback";
$pgAct = "view";
$pgTable = FEEDBACK;
$pgHeading = "feedback";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {
    include_once("../../config.php");
    $pgTable = FEEDBACK;
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
    if (isset($_REQUEST["date"]) && $_REQUEST["date"] != "") {
        $daterange = explode("-", $_REQUEST["date"]);
        $from = date("Y-m-d", strtotime($daterange[0]));
        $to = date("Y-m-d", strtotime($daterange[1]));
        array_push($sqlArray, $from);
        array_push($sqlArray, $to);
        $whereAry[] = " (date between ? and ?)";
    }
    if (isset($_REQUEST["q"]) && $_REQUEST["q"] != "") {
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
		array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        
        $whereAry[] = " (name like ? or email like ? or comments like ? ) ";
    }
  
    if (is_array($whereAry) && count($whereAry) > 0)
        $where = " WHERE " . implode(" AND ", $whereAry);

    $pcount = $db->getVal("select count(id) from  " . FEEDBACK . " $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;

     $contentDetail = $db->getRows("select id,name,date,email,comments,status from " . FEEDBACK . " $where order by id DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
	 
	//echo "error==>test";print_r($contentDetail); exit; 
	 
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
            $aryPgAct["cid"] = $_REQUEST['cid'];
			
            $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"1\")' class='status_" . $iList['id'] . "'><small class='label btn-danger'>Inactive</small></div>";
            if ($iList["status"] == 1) {
                $status = "<div onclick='updateStatus(\"" . $pgTable . "\", \"" . $iList['id'] . "\", \"status\", \"0\")' class='status_" . $iList['id'] . "'><small class='label btn-green'>Active</small></div>";
            }
			
            $checkbox = "<input class='checkbox row-checkbox' name='check[]' value='" . $iList['id'] . "' type='checkbox'>";
            $button = "";
            $button .= "<span class='sort-handler btn btn-info btn-sm'><i class='fa fa-arrows'></i></span>";
            $button .= "<div class='btn-group'>
                    <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'> Action <span class='caret'></span> </button>
                    <ul class='dropdown-menu' role='menu'>";
            //edit
            if ($_SESSION["admin"]["adminroll"] == 1 || ($permission == "a" || $permission == "w")) {
                $aryPgAct["action"] = "viewcomment";
                $button .= "<li><a onclick='loc(\"" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "\")'><i class='fa fa-edit'></i> ViewComment</a></li>";
            }

            //delete
           
            $button .= "</ul>
                  </div>";
            $i++;
            //$aryPgAct["action"] = "submenu";
            $aryData[] = array(
                $checkbox,
				
              /*  "<a href='" . URL_ADMIN_HOME . getQueryString($aryPgAct) . "'>" . $type. "</a>",*/
                $iList["name"],
				$iList["email"],
				$iList["comments"],
				$iList["date"],
                $status,
                //$button,
            );
        }
        $ProDetail["Result"] = $aryData;
    }
    echo json_encode($ProDetail);
    exit;
    //-----------------------------------------------------------------------------------------
} 
elseif ($pgAct == "view" || $pgAct == "viewcomment") {
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo ucwords($pgAct . " " . $pgHeading); ?> </div>
   
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
    if ( $pgAct == "view") 
	
     {

       
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
                                    <th class="first"><a href="#" title="linkname">Name</a></th>
                                    <th class="first"><a href="#" title="linkname">Email</a></th>
                                    <th class="first"><a href="#" title="linkname">Comments</a></th>
                                     <th><a href="#" title="linkname">Date</a></th>

                                    <th><a href="#" title="status">Status</a></th>

                                    <!--<th class="last" >Actions</th>-->
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