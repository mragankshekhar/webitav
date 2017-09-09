<?php
include("config.php");
if (!isset($_SESSION["user"]["uid"]) || $_SESSION["user"]["uid"] == "") {
    redirect("first.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include("include/mss_meta.php");
        ?>
        <link rel = "stylesheet" href = "css/index.css" />
        <script>
            var myDetail = $.parseJSON('<?php echo json_encode($myDetail); ?>');
            var lang = '<?php echo $lang ?>';
            var lat = '<?php echo $lat ?>';
            var long = '<?php echo $long ?>';
        </script>
    </head>
    <body  class = "theme-green  scroll100">
        <input type="hidden" class="reg_id" name="reg_id">
        <input type="hidden" class="lang" name="lang">
        <input type="hidden" class="lat" name="lat">
        <input type="hidden" class="long" name="long">
        <div class = "statusbar-overlay"></div>
        <div class = "panel-overlay"></div>

        <div class = "panel panel-left panel-cover">
            <div class="content-block1 center">
                <div style="margin-top:5px">
                    <a href="profile.html" class="ms-link close-panel">
                        <img src="img/avatar.png" class="avatar lazy" id="avatar"/><br/>
                        <span class="fullname"></span></a>
                </div>
            </div>
            <div class="list-block">
                <ul>
                    <li><a href="service.html" class="item-link close-panel with-animation">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-customer_service"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="SERVICE">Customer Service</span></div>
                                </div>
                            </div></a></li>
                    <li><a href="about.html" class="item-link close-panel with-animation">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-about"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="ABOUT">About</span> ITAV</div>
                                </div>
                            </div></a></li>

                    <li><a href="faq.html" class="item-link close-panel with-animation">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-faq"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="FAQ">FAQ</span></div>
                                </div>
                            </div></a></li>
                    <li><a href="setting.html" class="item-link close-panel">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-setting"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="SETTING">Setting</span></div>
                                </div>
                            </div></a></li>
                    <li><a href="feedback.html" class="item-link close-panel">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-feedback"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="FEEDBACK">Feedback</span></div>
                                </div>
                            </div></a></li>
                    <li><a href="privedy_policy.html" class="item-link close-panel">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-privecy_policy"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="PRIVACY_POLICY">Privacy policy</span></div>
                                </div>
                            </div></a></li>
                    <li><a href="terms_of_uses.html" class="item-link close-panel">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-terms"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="TERMS_OF_USES">Terms of Use</span></div>
                                </div>
                            </div></a></li>
                    <li><a href="#" onclick="Android.ShareIt('Share this App from here')" class="item-link">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-share"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="SHARE">Share</span> </div>
                                </div>
                            </div></a></li>
                    <li><a href="#" onclick="window.location = 'logout.php'" class="item-link">
                            <div class="item-content">
                                <div class="item-media"><i class="icon icon-nav icon-exit"></i></div>
                                <div class="item-inner">
                                    <div class="item-title"><span data-lang="LOGOUT">Logout</span> </div>
                                </div>
                            </div></a></li>
                </ul>
            </div>
        </div>
        <!-- Right Panel with Cover effect -->
        <div class="panel panel-right panel-cover">
            <div class="content-block">
                <p>Right Panel content here</p>
                <!-- Click on link with "close-panel" class will close panel -->
                <p><a href="#" class="close-panel">Close me</a></p>
                <!-- Click on link with "open-panel" and data-panel="left" attribute will open Left panel -->
                <p><a href="#" data-panel="left" class="open-panel">Open Left Panel</a></p>
            </div>
        </div>

        <div class = "views">
            <div class = "view view-main">
                <div class = "navbar  mss-navbar">
                    <div class = "navbar-inner mss-navbar-inner">
                        <div class = "left sliding">
                            <a href = "#" class = "link icon-only open-panel"><i class = "icon icon-bars"></i></a>
                        </div>
                        <div class="center">
                            <p class="ms-btn-where"><a href="#" style="height: 36px; color: #000;" class="link going-button" data-lang="WHERE_ARE_YOU">Where are you going...</a></p>
                        </div>
                        <div class = "right">
                            <span class="right-top-icon"><i class="icon icon-nav icon-map"></i></span>
                        </div>
                    </div>
                </div>

                <div class = "pages navbar-through toolbar-through">
                    <div data-page = "index" class = "page">
                        <a href="#" data-panel="right" class="open-panel floating-button " style="margin-bottom: 47px;">
                            <i class="f7-icons">chats_fill</i>
                        </a>
                        <div class = "page-content">
                            <div class="index-header">
                                <p class="ms-btn-where"><a href="#" class="link going-button">Where are you going...</a></p>
                                <a href="">+ add destination</a>
                                <div class="buttons-row theme-orange button-row-margin25">
                                    <button href="#" class="button button-fill button-green">Discover</button>
                                    <button href="#" class="button button-fill button-green">Plan</button>
                                </div>
                            </div>

                            <div class = "list-block">
                                <div class="content-block-title">Recently viewed</div>
                                <div class="swiper-container swiper-2">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places1.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places1.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="content-block-title">TOP for You</div>
                                <div class="swiper-container swiper-2">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places1.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places1.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="content-block-title">TOP Event</div>
                                <div class="swiper-container swiper-2">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places1.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="card demo-card-header-pic">
                                                <div style="background-image:url(img/places1.jpg);" valign="bottom" class="card-header color-white no-border">Barbate, Spain</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="content-block-title">Nearest Users</div>
                                <div class="swiper-container swiper-user">
                                    <div class="swiper-wrapper" id="ulist">

                                    </div>
                                </div>
                                <br/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class = "toolbar footer-link">
                    <div class = "toolbar-inner">
                        <a href = "#" class = "link"><i class="f7 f7-icons">search</i><span data-lang="DISCOVER">Discover</span></a>
                        <a href = "#" class = "link"><i class="f7 f7-icons">heart</i><span data-lang="MYTRIP">My Trip</span></a>
                        <a href = "profile.html" class = "link"><i class="f7 f7-icons">person</i><span data-lang="ME">Me</span></a>
                    </div>
                </div>
            </div>
        </div>
        <form method="post" class="form_ajax" action="api/search.php">
            <input type="hidden" name="type" value="search-where-going">
            <div class="modal going-screen" id="going-screen">
                <div class="modal-inner">
                    <div class="modal-title">Exploring with:<br/><small>Who are you travelling with?</small></div>
                    <div class="modal-text">
                        <div class="row center">
                            <input type="hidden" id="travelwith" name="travelwith">
                            <div class="col-25 tablet-25"><span data-cont="single" data-values="1" class="ms-block"><img src="icon/man.png"></span>Single</div>
                            <div class="col-25 tablet-25"><span data-cont="couple" data-values="1" class="ms-block"><img src="icon/couple.png"></span>Couple</div>
                            <div class="col-25 tablet-25"><span data-cont="family" data-values="4" class="ms-block"><img src="icon/family.png"></span>Family</div>
                            <div class="col-25 tablet-25"><span data-cont="group" data-values="6" class="ms-block"><img src="icon/group.png"></span>Group</div>
                        </div>
                        <div style="border-top: 1px solid #ccc; margin-top: 10px; padding-top: 10px; display: none;" class="row item-input" id="personqty">
                            <div class="col-50 tablet-50">No of adult
                                <div class="button-group">
                                    <button type="button" class="gdivs left">-</button>
                                    <input type="number" id="adultno" name="adultno" value="0" class="gdivs">
                                    <button type="button" class="gdivs right">+</button>
                                </div>
                            </div>
                            <div class="col-50 tablet-50">No of chile
                                <div class="button-group">
                                    <button type="button" class="gdivs left">-</button>
                                    <input type="number" id="childno" name="childno" value="0" class="gdivs">
                                    <button type="button" class="gdivs right">+</button>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="button active ms-closepop-first button-fill" style="margin-top: 20px">Next</a>
                    </div>
                </div>
                <div class="modal-buttons">
                    <a href="#" data-popup="#going-screen" class="link close-popup">Close</a>
                </div>
            </div>
            <div class="modal going-screen-next" id="going-screen-next">
                <div class="modal-inner">
                    <div class="modal-title">When to go:<br/><small>When are you travelling?</small></div>
                    <div class="modal-text">
                        Recommended time to go: Spring & Autumn
                        <div class="row nextGoing">
                            <div class="col-50 tablet-50">
                                <div class="item-input">
                                    <input type="text" class="no-fastclick" id="autocomplete_from" data-prefix="from" name="from_location" value="" placeholder="From Location">
                                    <input type="hidden" name="from_lat" id="from_lat">
                                    <input type="hidden" name="from_long" id="from_long">
                                </div>
                            </div>
                            <div class="col-50 tablet-50">
                                <div class="item-input">
                                    <input class="no-fastclick" type="text" id="autocomplete_to" data-prefix="to" name="to_location" value="" placeholder="To Location">
                                    <input type="hidden" name="to_lat" id="to_lat">
                                    <input type="hidden" name="to_long" id="to_long">
                                </div>
                            </div>
                            <div id="disstance" class="col-100 tablet-100" style="display:none"></div>
                        </div>
                        <div class="row nextGoing">
                            <div class="col-100 tablet-100">
                                <div class="item-input" style="margin-top: 15px;">
                                    <input style="width: 100%;" type="text" placeholder="Select date" readonly id="calendar-disabled">
                                </div>
                            </div>
                        </div>
                        <a href="#" class="button button-fill active ms-closepop-second" style="margin-top: 20px">Next</a>
                    </div>
                </div>
                <div class="modal-buttons">
                    <a href="#" data-popup="#going-screen-next" class="link close-popup">Close</a>
                </div>
            </div>
            <div class="modal going-screen-second-next" id="going-screen-second-next">
                <div class="modal-inner">
                    <div class="modal-title">Focus on:<br/><small>What would you like to do most?</small></div>
                    <div class="modal-text">
                        <div class="item-content placecontent">
                            <div class="item-inner">
                                <div class="item-title label">Pace</div>
                                <ul>
                                    <li><a href="#">Slow</a></li>
                                    <li><a href="#">Medium</a></li>
                                    <li><a href="#">Fast</a></li>
                                </ul>
                                <input type="hidden" name="pace" id="pace" value=""/>
                            </div>
                            <div class="item-inner">
                                <div class="item-title label">Budget</div>
                                <ul>
                                    <li><a href="#">Low</a></li>
                                    <li><a href="#">Medium</a></li>
                                    <li><a href="#">High</a></li>
                                </ul>
                                <input type="hidden" name="budget" id="budget" value="Medium"/>
                            </div>
                            <div class="item-inner">
                                <div class="item-title label">Church</div>
                                <div class="item-input">
                                    <div class="range-slider">
                                        <input type="range" min="0" max="100" value="50" step="0.1">
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="button button-fill active ms-closepop-second-next" style="margin-top: 20px">Explore</a>
                        </div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <a href="#" data-popup="#going-screen-second-next" class="link close-popup">Close</a>
                </div>
            </div>
            <input type="submit" id="submitsearchbtn" value="ok" class="hide">
        </form>
        <?php
                include("include/mss_footer.php");
        ?>
        <script type="text/javascript" src="js/index.js"></script>
    </body>
</html>