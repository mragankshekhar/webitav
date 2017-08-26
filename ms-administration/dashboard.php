	
      <div class="row">
      
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-12"> </div>
            <div class="col-md-12">
              <div class="panel">
                <div class="panel-heading">
                  <div class="panel-title"> <i class="fa fa-bar-chart-o"></i> Status </div>
                </div>
                <div class="panel-body">
                
                       <div class="col-md-4">
                         <form name="" action="ajax.php" method="post" class="form_ajax">
                         
                         <input name="type" type="hidden" value="inactive_news" />
                			<input class="submit btn btn-blue btn-block" type="submit" value="Inactive Previous News  " />
                         </form> 
                       </div>
                       
                       <div class="col-md-4">
                          <input class="submit btn btn-blue btn-block" type="submit" value="Notify" onClick="pushNotification('Test from demo admin to user wjo recieve meg','http://www.mssinfotech.in/whispersinthecorridors/uploads/media/logo.GIF','http://www.mssinfotech.com/')" />
					  </div> 
                      
	                   <div class="col-md-4">
                          <input class="submit btn btn-blue btn-block" type="submit" value="facebook Notify" onClick="postToFeed('Test from demo admin to user wjo recieve meg','Message from demo','http://www.mssinfotech.com/','http://www.mssinfotech.in/whispersinthecorridors/uploads/media/logo.GIF','whispersinthecorridors')" />
					  </div>
                       
                       <div class="">&nbsp;</div>
                       <div class="row">
                       <div class="col-md-6">
                         <form name="" action="ajax.php" method="post" class="form_ajax">
                        <div class="col-md-6"> <input name="type" type="hidden" value="update_date" />
                         <input name="date" type="date" class="form-control input-sm ac_input" value="<?php echo $LinksDetails['update_date'];?>" /> </div>
                		<div class="col-md-6">  <input class="submit btn btn-blue btn-block" type="submit" value="update_date" /></div>
                         </form> 
                       </div> 
                       
                       <div class="col-md-6">
                         <form name="" action="ajax.php" method="post" class="form_ajax">
                         <div class="col-md-6">
                         <input name="type" type="hidden" value="update_time" />
                         
                         <input name="time" class="form-control input-sm ac_input" type="time" value="<?php echo $LinksDetails['update_time'];?>"  />
                         </div>
                		  <div class="col-md-6">
                          
                          <input class="submit btn btn-blue btn-block" type="submit" value="update_time" /></div>
                         </form> 
                       </div>
                       </div>
                       
                       <div class="">&nbsp;</div>
                       <div class="row">
                       <div class="col-md-12">
                         <form name="" action="ajax.php" method="post" class="form_ajax">
                        <div class="col-md-6"> <input name="type" type="hidden" value="total_view" />
                         <input name="view" type="text" class="form-control input-sm ac_input" value="<?php echo $LinksDetails['total_view'];?>" /> </div>
                		<div class="col-md-6">  <input class="submit btn btn-blue btn-block" type="submit" value="submit" /></div>
                         </form> 
                       </div> 
                       
                       
                       </div>
                       
                       
                       
                </div>
                
                
                <!--<div class="panel-footer">
                  <div class="row">
                    <div class="col-xs-4 col-sm-3 text-center">
                      <div class="chart-btn">
                        <div class="chart-text">
                          <div class="chart-title" id="hit">0</div>
                          <div class="chart-subtitle">Hits</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-4 col-sm-3 text-center">
                      <div class="chart-btn">
                        <div class="chart-text">
                          <div class="chart-title" id="visit">0</div>
                          <div class="chart-subtitle">Visits</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-4 col-sm-3 text-center">
                      <div class="chart-btn">
                        <div class="chart-text">
                          <div class="chart-title"><?php 
						  	$sqlUserEditArray = array(0);
                            echo $pages=(int)$db->getVal("select count(id) from ".CMS." where status!=?",$sqlUserEditArray);
						  
						  //echo $pages=(int)$db->getVal("select count(id) from ".CMS." where status!=0"); ?></div>
                          <div class="chart-subtitle" id="pages">Pages</div>
                        </div>
                      </div>
                    </div>
                    <div class="hidden-xs col-sm-3 text-center">
                      <div class="chart-btn">
                        <div class="chart-text">
                          <div class="chart-title">
						  <?php 
						  $sqlUserEditArray = array(0);
                            echo $pages=(int)$db->getVal("select count(userid) from ".REGISTER_USER." where status!=?",$sqlUserEditArray);
						  
						 // echo $nuser=(int)$db->getVal("select count(userid) from ".REGISTER_USER." where status!=0"); ?></div>
                          <div class="chart-subtitle">Users</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>-->
              </div>
            </div>
            
          </div>
        </div>
        
        
        <div class="col-md-4" style="float:right;">
          <div class="row">
            <div class="row">
            <div class="col-md-12">
              <div class="panel profile-panel">
                <div class="panel-heading panel-visible">
                  <div class="panel-title"> <span class="glyphicon glyphicon-user"></span> Admin Profile -</div>
                  <span class="panel-title-sm pull-left text-success" style="color: #7ec35d; padding-left: 7px; padding-top: 2px;"> Online</span>
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-xs-5" id="profile-avatar"> <img src="<?php echo $LinksDetails["logo"] ?>" class="img-responsive" width="150" height="112" alt="avatar"> </div>
                    <div class="col-xs-7">
                      <div class="profile-data"> <span class="profile-name"> <b class="text-primary"><?php echo $LinksDetails["admin_fname"] ?></b> - <b>Away</b></span> <span class="profile-email"> <?php echo $LinksDetails["admin_email"] ?> </span>
                        <ul class="profile-info list-unstyled">
                          <li><i class="fa fa-phone"></i> <?php echo $LinksDetails["telephone"] ?></li>
                          <li><i class="fa fa-skype"></i> <?php echo $LinksDetails["site_name"] ?></li>
                          <li><i class="fa fa-globe"></i> <?php echo $LinksDetails["address"] ?></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
                
              </div>
              
           
            </div>
          </div>
          </div>
        </div>
      </div>
      
      
<?php
$visit="";
$itemval=array();
$count=0;
for($i=01; $i<=30; $i++){
	$date=date('Y')."-".date('m')."-".$i;
	$sqlUserEditArray = array($date);
                            
							$result1 = (int)$db->getVal("SELECT SUM(no) FROM ".NO_VISIT." WHERE DATE(ndate) = ?",$sqlUserEditArray);
	
	//$result1 = $db->getVal("SELECT SUM(no) FROM ".NO_VISIT." WHERE DATE(ndate) = '".date('Y')."-".date('m')."-".$i."'");
	if($result1!="")
	{
		$itemval[$i]=$result1;
		$count+=$result1;
	}else{
		$itemval[$i]=0;
	}
}
$allvisit=implode(",",$itemval);
$allvisitTotal=$count;
$visit="";
$count=0;
$UniqueoutPut=array();;
for($i=1; $i<=30; $i++){
	$date=date('Y')."-".date('m')."-".$i;
	$sqlUserEditArray = array($date);
                            
							$result1 = (int)$db->getVal("select count(no) from ".NO_VISIT." WHERE  DATE(ndate) = ? group by ip",$sqlUserEditArray);
	  
	//$result1 = $db->getVal("select count(no) from ".NO_VISIT." WHERE DATE(ndate) = '".date('Y')."-".date('m')."-".$i."' group by ip");
	if($result1!="")
	//if(is_array($result1) && count($result1)>0)
	{
		$j=0;
		$UniqueoutPut[$i]=$result1;	
		$count+=$result1;
	
	}else{
		$UniqueoutPut[$i]=0;
	}
}
$uvisit=implode(",",$UniqueoutPut);
$uvisitTotal=$count;
?>
<script>
$(document).ready(function(e) {
	var runFlotCharts = function () {
	var Colors = ['#5bc0de', '#428bca', '#5cb85c', '#d9534f', '#f0ad4e', '#7857ca', '#efcf1d'];
	var randNum = function () {return (Math.floor(Math.floor((Math.random() * 5) + 1) + 5));}
	function dataCreate(num, dev) {
		var dataPlots = [];
		if(dev=="allvisit"){
			$("#hit").html('<?php echo $allvisitTotal; ?>');
			for (var i = 0; i < num; i++) {
				newdata='<?php echo $allvisit; ?>';
				var mydata=newdata.split(",");
				var newmydata="0";
				if(mydata[i]!="")newmydata=mydata[i];
				dataPlots.push([(i + 1), (newmydata)]);
			}
		}else{
			$("#visit").html('<?php echo $uvisitTotal; ?>');
			for (var i = 0; i < num; i++) {
				newdata='<?php echo $uvisit; ?>';
				var mydata=newdata.split(",");
				var newmydata="0";
				if(mydata[i]!="")newmydata=mydata[i];
				dataPlots.push([(i + 1), (newmydata)]);
			}
		}
		//alert(dataPlots);
		return (dataPlots);
	}
	// Check if element exist and draw auto updating chart
	if ($(".chart-line").length) {
            var options = {
                grid: {
                    show: true,
                    aboveData: true,
                    color: "#3f3f3f",
                    labelMargin: 5,
                    axisMargin: 0,
                    borderWidth: 0,
                    borderColor: null,
                    minBorderMargin: 5,
                    clickable: true,
                    hoverable: true,
                    autoHighlight: true,
                    mouseActiveRadius: 20
                },
                series: {
                    lines: {
                        show: true,
                        fill: 0.5,
                        lineWidth: 2,
                        steps: false
                    },
                    points: {
                        show: false
                    }
                },
                yaxis: {
                    min: 0
                },
                xaxis: {
                    ticks: 11,
                    tickDecimals: 0
                },
                colors: Colors,
                shadowSize: 1,
                tooltip: true,
                //activate tooltip
                tooltipOpts: {
                    content: "%s : %y.0",
                    shifts: {
                        x: -30,
                        y: -50
                    },
                    defaultTheme: false
                }
            };
            $.plot($(".chart-line"), [{
                label: "Total Visits",
                data: dataCreate(30, "allvisit"),
                lines: {
                    fillColor: "#f3faff"
                }
            }, {
                label: "Unique Visits",
                data: dataCreate(30, "uniquevisit"),
                lines: {
                    fillColor: "#fff8f7"
                }
            }], options);
        }
  } 
  runFlotCharts();
});
</script>