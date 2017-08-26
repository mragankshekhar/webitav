<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$pgMod = "report";
$pgAct = "view";
$pgTable = RECORD;
$pgHeading = "Report";

if (isset($_REQUEST['action']) && trim($_REQUEST['action']) != '')
    $pgAct = strtolower($_REQUEST['action']);

if ($pgAct == "viewall") {
    include_once("../../config.php");
	
    $pgTable = RECORD;
    $dataAry = array();
    $sqlArray = array();
    $i = 0;
    $table = "";
    $select = "";
    $where = "";
    $whereAry = array();

    if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {
        array_push($sqlArray, $_REQUEST["status"]);
        $whereAry[] = " ads_id= ? ";
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
		array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
        array_push($sqlArray, '%' . $_REQUEST["q"] . '%');
		
        
        $whereAry[] = " (udate like ? or url like ? or ip like ? or browser like ? ) ";
    }
   /* if (isset($_REQUEST["cid"]) && $_REQUEST["cid"] != "") {
        array_push($sqlArray, $_REQUEST["cid"]);
        $whereAry[] = " (c_type = ?) ";
    }*/
    if (is_array($whereAry) && count($whereAry) > 0)
        $where = " WHERE " . implode(" AND ", $whereAry);

    $pcount = $db->getVal("select count(id) from  " . RECORD . " $where", $sqlArray);

    $startV = $_REQUEST['startV'];
    $endV = $_REQUEST['endV'];
    $ProDetail["totPost"] = $pcount;
  //echo "sss"; exit;
     $contentDetail = $db->getRows("select id,udate,url,ads_id,ip,browser from " . RECORD . " $where order by id DESC " . ($endV == 'All' ? "" : "limit $startV, $endV"), $sqlArray);
   //echo "error==>"."<pre>";print_r($contentDetail); echo $db->lq(); exit;
    $ProDetail["query"] = $db->lq() . $db->em() . json_encode($sqlArray);
    $ProDetail["ncount"] = count($contentDetail);
    $ProDetail["tcolumn"] = 7;
    if (is_array($contentDetail) && count($contentDetail) > 0) {
        $aryData = array();
        $i = 0;
        foreach ($contentDetail as $iList) {
            $button = "";
            $aryPgAct["id"] = $iList['id'];
            $aryPgAct["page_id"] = $pgMod;
			
			        /*    $checkbox = "<input class='checkbox row-checkbox' name='check[]' value='" . $iList['id'] . "' type='checkbox'>";
           
            $button .= "</ul>
                  </div>";*/
            $i++;
            //$aryPgAct["action"] = "submenu";
            $aryData[] = array(
               
                $iList["udate"],
				$iList["url"],
				$iList["ip"],
				$iList["browser"],
				
            );
        }
        $ProDetail["Result"] = $aryData;
    }
    echo json_encode($ProDetail);
    exit;
    //-----------------------------------------------------------------------------------------
} elseif ($pgAct == "view" ) {
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
	//strat view
    
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
                                    
                                    

                                    <label for="status" class="col-lg-2 control-label">Url.</label>

                                    <div class="col-md-4">

                                        <select id="status" name="status" class="form-control margin-top-none searchFilter">
                                            <option value="">Select</option>
                                           <?php 
										
	 
										   $urlrecord =$db->getRows("select url,id from ".ADS." group by id ");
										   //echo "error==>";print_r($urlrecord); exit;
										   foreach($urlrecord as $record){
										   ?>
                                            <option value="<?php echo $record['id'] ; ?>"><?php echo $record['url'] ; ?></option>
                                           <?php }?> 
                                            

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
                        $extraAry = "";
                        if (isset($_REQUEST["status"]) && $_REQUEST["status"] != "") {
                            $extraAry = "&status=" . $_REQUEST["status"];
                        }
                        if ($extraAry != "")
                            echo "data=yes" . $extraAry;
                        ?>" data-page="<?php echo $pgMod ?>" data-table="<?php echo $pgTable ?>" class="table table-widget table-striped" id="mssresulttable" data-export="0">
                           
                            <thead>
                                <tr>
                                   
                                    <th class="first"><a href="#" title="linkname">Udate</a></th>
                                    <th class="first"><a href="#" title="linkname">url</a></th>
                                    <th><a href="#" title="linkname">ip</a></th>

                                    <th><a href="#" title="status">browser</a></th>

                                </tr>
                            </thead>
                            <tbody id="resultBody">
                            </tbody>
                        </table>
                        <div class="paginationData"></div>
                        <?php
                    
					
	//end view				
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>