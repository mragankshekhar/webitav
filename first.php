<?php
include("config.php");
?>
<!DOCTYPE html>
<html>

   <head>
      <?php
		include("include/mss_meta.php");
		?>
		<link rel = "stylesheet" href = "css/login.css" />
		<script>
	   var lang = '<?php echo $lang ?>';
	   var lat = '<?php echo $lat ?>';
	   var long = '<?php echo $long ?>';	   
	   </script>
   </head>

   <body class="theme-green">
      <div class = "views">
         <div class = "view view-main">
         
            <div class = "navbar"  style="display:none">
               <div class = "navbar-inner">
                  <div class = "center">Language</div>
               </div>
            </div>
            
            <div class = "pages">
               <div data-page = "index" class = "page navbar-fixed">
                  <div class = "page-content" style="background-image: url(img/iphone-2.jpg); background-size: 100% 100%">
                     <div class = "content-block">
						 <p class="row center"><img src="img/logo.png" class="ms-logo"></p>
                       <div class="list-block">
                           <input type="hidden" class="reg_id">
                           <input type="hidden" class="lang">
                           <input type="hidden" class="lat">
                           <input type="hidden" class="long">
						  <ul>
							<li>
							  <a href="#" mydata-lang="sp" class="item-link item-content open-login-ms">
								<div class="item-media"><i class="f7-icons">check_round</i></div>
								<div class="item-inner">
								  <div class="item-title"> &nbsp; Español</div>
								</div>
							  </a>
							</li>
							<li>
							  <a href="#" mydata-lang="en" class="item-link item-content open-login-ms">
								<div class="item-media"><i class="f7-icons">check_round</i></div>
								<div class="item-inner">
								  <div class="item-title"> &nbsp; English</div>
								</div>
							  </a>
							</li>
							<li>
							  <a href="#" mydata-lang="gr" class="item-link item-content open-login-ms">
								<div class="item-media"><i class="f7-icons">check_round</i></div>
								<div class="item-inner">
								  <div class="item-title"> &nbsp; Deutsch</div>
								</div>
							  </a>
							</li>
						  </ul>
						</div>
                     </div>
                  </div>
               </div>
            </div>
            
         </div>
      </div>
      <?php
		include("include/mss_footer.php");
		?>
      <script type="text/javascript" src="js/login.js"></script>
   </body>

</html>