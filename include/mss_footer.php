<script type = "text/javascript" src = "js/framework7.min.js"></script>
  <script>
  (function () {
	if (Framework7.prototype.device.android) {
		  Dom7('head').append(
			  '<link rel="stylesheet" href="css/framework7.material.min.css">' +
			  '<link rel="stylesheet" href="css/framework7.material.colors.min.css">' +
			  '<link rel="stylesheet" href="css/my-app.material.css">'
		  );
	  }
	  else {
		  Dom7('head').append(
			  '<link rel="stylesheet" href="css/framework7.ios.min.css">' +
			  '<link rel="stylesheet" href="css/framework7.ios.colors.min.css">' +
			  '<link rel="stylesheet" href="css/my-app.ios.css">'
		  );
	  }
  })();
var myApp = new Framework7({
	modalTitle:'ITAV',
	fastClicks: false,
	animateNavBackIcon:true,
	
	onAjaxStart: function (xhr) {
        myApp.showIndicator('Please wait...');
    },
    onAjaxComplete: function (xhr) {
        myApp.hideIndicator();
    }
});	  
var $$ = Dom7;
var mainView = myApp.addView('.view-main', {
	dynamicNavbar: true
});
</script>
<script type="text/javascript" src="js/mss.js"></script>