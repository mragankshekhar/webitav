<?php
if($_POST){
	$db->updateAry(SETTINGS,array("value"=>POST($_POST["custom"]))," where field='custom-css'");
	echo "success==>".(URL_ADMIN_HOME.getQueryString(array("page_id"=>"customize")));
}
?>
<!-- Customizer CSS -->
<link rel="stylesheet" type="text/css" href="css/customizer.css">


    <div id="skin-menu" class="container">
      <div class="row">
        <div class="col-md-10 col-lg-8 center-column">
          <div class="panel panel-visible">
            <div class="panel-heading">
              <div class="panel-title"> <i class="fa fa-ticket"></i> Stardom Theme Editor </div>
              <div class="panel-btns pull-right margin-left">
                <button type="button" data-toggle="modal" data-target="#skinModal" class="skin-modal-open btn btn-info btn-gradient margin-right-sm"> <span class="glyphicons glyphicons-cogwheel"></span> Save CSS </button>
                <a href="?page_id=customize" class="btn btn-danger btn-gradient">Reset</a> </div>
            </div>
            <div class="panel-menu">
              <a data-toggle="tab" href="#tab1" class="btn btn-default btn-gradient btn-states margin-left margin-right-sm active">Header</a>
              <a data-toggle="tab" href="#tab2" class="btn btn-default btn-gradient btn-states margin-right-sm">SideMenu</a>
              <a data-toggle="tab" href="#tab3" class="btn btn-default btn-gradient btn-states margin-right-sm">Breadcrumbs</a>
              <a data-toggle="tab" href="#tab4" class="btn btn-default btn-gradient btn-states margin-right-sm">Portlets</a>
              <a data-toggle="tab" href="#tab5" class="btn btn-default btn-gradient btn-states margin-right-sm">Body</a>
            </div>
            <div class="panel-body">
              <div class="tab-content padding-left-lg border-none">
                <div id="tab1" class="tab-pane active">
                  <div class="panel-group accordion accordion-alt" id="accordion1">
                    <div class="panel panel-visible">
                      <div class="panel-heading"> <a class="accordion-toggle-icon" data-toggle="collapse" data-parent="#accordion1" href="#header1">
                        <div class="accordion-toggle-icon"> <i class="fa fa-minus-square-o"></i> <i class="fa fa-plus-square-o"></i> </div>
                        Modify <b>Header <span class="text-primary">Color</span></b> </a> </div>
                      <div id="header1" class="panel-collapse in">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="checkbox-inline">
                                  <input class="checkbox" type="checkbox" data-modify='{"selector":".skin-set-1", "style": "gradient"}'>
                                  Switch to <b>Gradient</b> </label>
                                <label class="checkbox-inline">
                                  <input class="checkbox" type="checkbox" data-modify='{"selector":".navbar", "style": 1}'>
                                  Remove <b>Box-Shadow</b> (recommended) </label>
                                <button type="button" class=" hidden btn btn-default btn-gradient margin-left btn-sm" data-modify='{"selector":".navbar", "style": "border-bottom-color", "popover": "border"}' data-toggle="popover"> Border Color</button>
                              </div>
                              <hr class="short alt" />
                              <div class="skin-items skin-set-1" data-modify='{"selector":".navbar", "style": "background-color"}'>
                                <div class="btn-blue"></div>
                                <div class="btn-blue2"></div>
                                <div class="btn-blue3"></div>
                                <div class="btn-blue4"></div>
                                <div class="btn-blue5"></div>
                                <div class="btn-blue6"></div>
                                <div class="btn-blue7"></div>
                                <div class="btn-green"></div>
                                <div class="btn-green2"></div>
                                <div class="btn-green3"></div>
                                <div class="btn-green4"></div>
                                <div class="btn-green5"></div>
                                <div class="btn-purple"></div>
                                <div class="btn-purple2"></div>
                                <div class="btn-purple3"></div>
                                <div class="btn-purple3"></div>
                                <div class="btn-red"></div>
                                <div class="btn-red2"></div>
                                <div class="btn-red3"></div>
                                <div class="btn-red4"></div>
                                <div class="btn-orange"></div>
                                <div class="btn-orange2"></div>
                                <div class="btn-yellow"></div>
                                <div class="btn-yellow2"></div>
                                <div class="btn-creme"></div>
                                <div class="btn-creme2"></div>
                                <div class="btn-brown"></div>
                                <div class="btn-brown2"></div>
                                <div class="btn-brown3"></div>
                                <div class="btn-dark5"></div>
                                <div class="btn-dark4"></div>
                                <div class="btn-dark3"></div>
                                <div class="btn-dark2"></div>
                                <div class="btn-dark"></div>
                                <div class="btn-light7"></div>
                                <div class="btn-light6"></div>
                                <div class="btn-light5"></div>
                                <div class="btn-light4"></div>
                                <div class="btn-light3"></div>
                                <div class="btn-light2"></div>
                                <div class="btn-light"></div>
                              </div>
                              <hr class="short alt" />
                              <h6> Header Button Options</h6>
                              <div class="form-group">
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".header-btns button.btn", "style": "background-color", "popover": "gradient"}' data-toggle="popover"> <i class="fa fa-check-square-o"></i> Button Color </button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".header-btns button.btn", "style": "border-color", "popover": "border"}' data-toggle="popover"> <i class="fa fa-arrows-h"></i> Border Color </button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".header-btns button.btn", "style": "color", "popover": "text-shadow"}' data-toggle="popover"> <i class="fa fa-font"></i> Text Color </button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".header-btns button.btn span", "style": "color"}' data-toggle="popover"> <i class="fa fa-rocket"></i> Icon Color </button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".header-btns button.btn:hover", "style": "color"}' data-toggle="popover" data-help-text="Change will only be visible in CSS"> <i class="fa fa-hand-o-up"></i> Hover </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="tab2" class="tab-pane">
                  <div class="panel-group accordion accordion-alt" id="accordion2">
                    <div class="panel panel-visible">
                      <div class="panel-heading"> <a class="accordion-toggle-icon" data-toggle="collapse" data-parent="#accordion2" href="#sidemenu1">
                        <div class="accordion-toggle-icon"> <i class="fa fa-minus-square-o"></i> <i class="fa fa-plus-square-o"></i> </div>
                        Modify <b>SideMenu <span class="text-primary">Styles</span></b> </a> </div>
                      <div id="sidemenu1" class="panel-collapse in">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios" data-modify='{"selector":"#sidebar", "style": "sidebar-default"}' checked="">
                                  Style 1 - <b>Gradient</b> </label>
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios" data-modify='{"selector":"#sidebar", "style": "sidebar-alt-white"}'>
                                  Style 2 - <b>Flat White</b> </label>
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios" data-modify='{"selector":"#sidebar", "style": "sidebar-alt-grey"}'>
                                  Style 3 - <b>Flat Grey</b> </label>
                              </div>
                              <hr class="short alt" />
                              <ul class="nav well-tabs well-tabs-inverse margin-bottom-lg">
                                <li class="active"><a href="#search-form" data-toggle="tab">Search Bar</a></li>
                                <li><a href="#search-button" data-toggle="tab">Toggle Button</a></li>
                              </ul>
                              <div class="tab-content border-none padding-none">
                                <div class="tab-pane active" id="search-form">
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"#sidebar-search", "style": "background-color", "popover": "gradient"}' data-toggle="popover"> <i class="fa fa-check-square-o"></i> Background</button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"#sidebar-search form input.search-bar", "style": "background-color", "popover": "gradient"}' data-toggle="popover"> <i class="fa fa-check-square-o"></i> Form Background </button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"#sidebar-search form input.search-bar", "style": "border-color", "popover": "border"}' data-toggle="popover"> <i class="fa fa-arrows-h"></i> Form Border </button>
                                  <br>
                                  <br>
                                  <p class="small"><b>Note:</b> Search Form Placeholder text still requires many psuedo classes and crossbrowser fixes. <br>
                                    As a result it must be styled manually in Stardoms theme.css</p>
                                </div>
                                <div class="tab-pane" id="search-button">
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".sidebar-toggle", "style": "background-color", "popover": "gradient"}' data-toggle="popover"> <i class="fa fa-check-square-o"></i> Button Color </button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".sidebar-toggle", "style": "border-color", "popover": "border"}' data-toggle="popover"> <i class="fa fa-arrows-h"></i> Border Color </button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".sidebar-toggle", "style": "color"}' data-toggle="popover"> <i class="fa fa-font"></i> Icon Color </button>
                                </div>
                              </div>
                              <hr class="short alt" />
                              <ul class="nav well-tabs well-tabs-inverse margin-bottom-lg">
                                <li class="active"><a href="#menu-icons" data-toggle="tab">Menu Icons</a></li>
                                <li><a href="#menu-text" data-toggle="tab">Menu Text</a></li>
                                <li><a href="#menu-caret" data-toggle="tab">Menu Caret</a></li>
                              </ul>
                              <div class="tab-content border-none padding-none">
                                <div class="tab-pane active" id="menu-icons">
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav > li > a .glyphicons", "style": "color", "popover": "icon-border"}' data-toggle="popover"><i class="fa fa-rocket"></i> Icons</button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav ul.sub-nav li .glyphicons", "style": "color"}' data-toggle="popover"><i class="fa fa-rocket"></i> Sub-Icons</button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav li.active > a .glyphicons", "style": "color"}' data-toggle="popover"><i class="fa fa-rocket text-red"></i> Active Icons</button>
                                </div>
                                <div class="tab-pane" id="menu-caret">
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav > li > a span.caret", "style": "color"}' data-toggle="popover"><i class="fa fa-caret-right"></i> Caret</button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav > li.active > a span.caret", "style": "color"}' data-toggle="popover"><i class="fa fa-caret-right text-red"></i> Active Caret</button>
                                </div>
                                <div class="tab-pane" id="menu-text">
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav .sidebar-title", "style": "color"}' data-toggle="popover"><i class="fa fa-font"></i> Text</button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav ul.sub-nav li a", "style": "color"}' data-toggle="popover"><i class="fa fa-font"></i> Sub-Text</button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav > li.active .sidebar-title", "style": "color"}' data-toggle="popover"><i class="fa fa-font text-red"></i>ctive Text</button>
                                  <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"ul.sidebar-nav ul.sub-nav > li.active a", "style": "color"}' data-toggle="popover"><i class="fa fa-font text-red"></i>ctive Sub-Text</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="tab3" class="tab-pane">
                  <div class="panel-group accordion accordion-alt" id="accordion3">
                    <div class="panel panel-visible">
                      <div class="panel-heading"> <a class="accordion-toggle-icon" data-toggle="collapse" data-parent="#accordion3" href="#breadcrumb1">
                        <div class="accordion-toggle-icon"> <i class="fa fa-minus-square-o"></i> <i class="fa fa-plus-square-o"></i> </div>
                        Modify <b>Breadcrumbs <span class="text-primary">Options</span></b> </a> </div>
                      <div id="breadcrumb1" class="panel-collapse in">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-12">
                              <h6> Font Size </h6>
                              <div class="form-group row">
                                <div class="col-md-6">
                                  <select class="form-control" data-modify='{"selector":".breadcrumb", "style": "font-size"}'>
                                    <option>11px</option>
                                    <option>12px</option>
                                    <option>13px</option>
                                    <option selected>14px</option>
                                    <option>16px</option>
                                    <option>18px</option>
                                  </select>
                                </div>
                              </div>
                              <h6> Font Weight </h6>
                              <div class="form-group row">
                                <div class="col-md-6">
                                  <select class="form-control" data-modify='{"selector":".breadcrumb", "style": "font-weight"}'>
                                    <option value="400" selected>Thin</option>
                                    <option value="600">Normal</option>
                                    <option value="800">Bold</option>
                                  </select>
                                </div>
                              </div>
                              <hr class="alt short" />
                              <h6 class="margin-bottom margin-top-lg"> Backgrounds </h6>
                              <div class="form-group">
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"#topbar", "style": "background", "popover": "breadcrumb-patterns"}' data-toggle="popover"> <i class="fa fa-puzzle-piece"></i> Patterns </button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"#topbar", "style": "background-color", "popover": "breadcrumb-bg"}' data-toggle="popover"> <i class="fa fa-flask"></i> Colors </button>
                              </div>
                              <h6 class="margin-bottom margin-top-lg"> Colors </h6>
                              <div class="form-group">
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".breadcrumb li", "style": "color"}' data-toggle="popover"> <i class="fa fa-font"></i> Text</button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".breadcrumb li a", "style": "color"}' data-toggle="popover"> <i class="fa fa-font"></i> Link Text </button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".breadcrumb li a i.fa", "style": "color"}' data-toggle="popover"> <i class="fa fa-home"></i> Icon Color </button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":"#topbar", "style": "border-bottom-color"}' data-toggle="popover"> <i class="fa fa-arrows-h"></i> Border Color </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="tab4" class="tab-pane">
                  <div class="panel-group accordion accordion-alt" id="accordion4">
                    <div class="panel panel-visible">
                      <div class="panel-heading"> <a class="accordion-toggle-icon" data-toggle="collapse" data-parent="#accordion4" href="#portlet1">
                        <div class="accordion-toggle-icon"> <i class="fa fa-minus-square-o"></i> <i class="fa fa-plus-square-o"></i> </div>
                        Modify <b>Portlet <span class="text-primary">Header</span></b> </a> </div>
                      <div id="portlet1" class="panel-collapse in">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group row">
                                <div class="col-md-6">
                                  <h6>Header Title Size </h6>
                                  <select class="form-control" data-modify='{"selector":".panel-title", "style": "font-size"}'>
                                    <option>12px</option>
                                    <option>13px</option>
                                    <option selected>14px</option>
                                    <option>15px</option>
                                    <option>16px</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <div class="col-md-6">
                                  <h6> Header Icon Size </h6>
                                  <select class="form-control" data-modify='{"selector":".panel-title .fa", "style": "font-size"}'>
                                    <option>13px</option>
                                    <option>15px</option>
                                    <option selected>17px</option>
                                    <option>20px</option>
                                    <option>22px</option>
                                  </select>
                                </div>
                              </div>
                              <h6 class="margin-top-lg"> Header Colors</h6>
                              <button type="button" class="btn btn-default btn-gradient margin-right" data-modify='{"selector":".panel-heading", "style": "background-color", "popover": "gradient"}' data-toggle="popover"> <i class="fa fa-flask"></i> Background </button>
                              <button type="button" class="btn btn-default btn-gradient margin-right" data-modify='{"selector":".panel-title .fa", "style": "color"}' data-toggle="popover"> <i class="fa fa-rocket"></i> Icon Color </button>
                              <button type="button" class="btn btn-default btn-gradient margin-right" data-modify='{"selector":".panel-title", "style": "color", "popover": "text-shadow"}' data-toggle="popover"> <i class="fa fa-font"></i> Text Color </button>
                              <hr class="alt" />
                              <h6> Body Box-Shadow</h6>
                              <div class="form-group">
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios3" data-modify='{"selector":".panel", "style": 6}' checked="">
                                  Black </label>
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios3" data-modify='{"selector":".panel", "style": 7}'>
                                  White </label>
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios3" data-modify='{"selector":".panel", "style": 1}'>
                                  None </label>
                              </div>
                              <h6> Body Colors</h6>
                              <div class="form-group">
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".panel", "style": "background-color", "popover": "gradient"}' data-toggle="popover"> <i class="fa fa-flask"></i> Background </button>
                                <button type="button" class="btn btn-default btn-gradient margin-right-sm" data-modify='{"selector":".panel", "style": "border-color", "popover": "border"}' data-toggle="popover"> <i class="fa fa-arrows-h"></i> Border Color </button>
                              </div>
                              <div class="form-group" hidden>
                                <h6> Header Box-Shadow</h6>
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios2" data-modify='{"selector":".panel-heading", "style": 6}'>
                                  Black </label>
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios2" data-modify='{"selector":".panel-heading", "style": 7}' checked="">
                                  White </label>
                                <label class="radio-inline margin-bottom">
                                  <input class="radio" type="radio" name="optionsRadios2" data-modify='{"selector":".panel-heading", "style": 1}'>
                                  None </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="tab5" class="tab-pane">
                  <div class="panel-group accordion accordion-alt" id="accordion5">
                    <div class="panel panel-visible">
                      <div class="panel-heading"> <a class="accordion-toggle-icon" data-toggle="collapse" data-parent="#accordion5" href="#body1">
                        <div class="accordion-toggle-icon"> <i class="fa fa-minus-square-o"></i> <i class="fa fa-plus-square-o"></i> </div>
                        Modify <b>Body <span class="text-primary">Background</span></b> </a> </div>
                      <div id="body1" class="panel-collapse in">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="skin-items skin-set-2" data-modify='{"selector":"#content", "style": "background-color"}'>
                                <div class="btn-blue"></div>
                                <div class="btn-blue2"></div>
                                <div class="btn-blue3"></div>
                                <div class="btn-blue4"></div>
                                <div class="btn-blue5"></div>
                                <div class="btn-blue6"></div>
                                <div class="btn-blue7"></div>
                                <div class="btn-green"></div>
                                <div class="btn-green2"></div>
                                <div class="btn-green3"></div>
                                <div class="btn-green4"></div>
                                <div class="btn-green5"></div>
                                <div class="btn-purple"></div>
                                <div class="btn-purple2"></div>
                                <div class="btn-purple3"></div>
                                <div class="btn-purple3"></div>
                                <div class="btn-red"></div>
                                <div class="btn-red2"></div>
                                <div class="btn-red3"></div>
                                <div class="btn-red4"></div>
                                <div class="btn-orange"></div>
                                <div class="btn-orange2"></div>
                                <div class="btn-yellow"></div>
                                <div class="btn-yellow2"></div>
                                <div class="btn-creme"></div>
                                <div class="btn-creme2"></div>
                                <div class="btn-brown"></div>
                                <div class="btn-brown2"></div>
                                <div class="btn-brown3"></div>
                                <div class="btn-dark5"></div>
                                <div class="btn-dark4"></div>
                                <div class="btn-dark3"></div>
                                <div class="btn-dark2"></div>
                                <div class="btn-dark"></div>
                                <div class="btn-light7"></div>
                                <div class="btn-light6"></div>
                                <div class="btn-light5"></div>
                                <div class="btn-light4"></div>
                                <div class="btn-light3"></div>
                                <div class="btn-light2"></div>
                                <div class="btn-light"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
<!-- End: Main -->

<div class="modal fade" id="skinModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="skinGenerate" action="" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Your Custom CSS</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-info"> To <b>implement</b> these changes Click on <strong>Save Changes</strong> Button</div>
          <div class="well">
            <textarea name="custom"><?php echo unPOST($LinksDetails["custom-css"]); ?></textarea>
          </div>
        </div>
        <div class="modal-footer"> 
        <a href="#" class="btn btn-primary btn-gradient" id="copy"><i class="fa fa-rocket"></i> Select All</a>
        <button type="submit" class="btn btn-success btn-gradient" id="download"><i class="fa fa-download"></i> Save Changes</a> </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript" src="js/customizer.js"></script> 
<script type="text/javascript">
jQuery(document).ready(function() {

	// Init Theme Core 	  
	Core.init();
	Customizer.init();
	
	$('#copy').click(function(e) {
		e.preventDefault();
	   $('#skinGenerate textarea').select();
	});

});
</script>