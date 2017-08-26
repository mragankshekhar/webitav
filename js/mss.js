var geocoder = new google.maps.Geocoder();
if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
} 
function errorFunction(err){
	console.log("geo code rror: from meta.php "+err);
}
function successFunction(position) {
	lat = position.coords.latitude;
	long = position.coords.longitude;
	console.log(lat+"-"+long);
	setLatLang(lat,long);
}
myApp.onPageInit('*', function (page) {
    var progressbar     = $('#progressbar');
    	var statustxt       = $('#progresstext');
    	var submitbutton    = $("#submit");
    	var myform          = $(".form_ajax");
    	var title			= document.title;
    	var progressDiv     = $("#progressDiv");
    	var completed       = '0%';
    	$(myform).ajaxForm({
    		beforeSend: function() { //brfore sending form
				myApp.showPreloader('Loading...')
    			//submitbutton.attr('disabled', ''); // disable upload button
    			//progressDiv.show();
    			//document.title = " Processing Please wait...";
    			//progressbar.width(completed);
    			//showToast(" Processing Please wait...","info")
    		},
    		uploadProgress: function(event, position, total, percentComplete) { //on progress
				myApp.showPreloader('Loading '+percentComplete + '% completed')
    			//$("#progress").width(percentComplete + '%');//update progressbar percent complete
    			//$(".statustxt").html(percentComplete + '% completed'); //update status text
    			//addNotice(percentComplete+ "% Completed...", "fa fa-refresh", "");
    			//document.title = percentComplete+ "% Completed..."
    			//submitbutton.html(percentComplete+' % Processing...');
    		},
    		complete: function(response) { // on complete
				myApp.hidePreloader();
    			//x$.gritter.removeAll();
    			//document.title = title;
    			//submitbutton.removeAttr('disabled'); //enable submit button
    			//progressDiv.slideUp(); // hide progressbar
    			console.log(response.responseText);
    			var data=$.parseJSON(response.responseText);
    			if(data.status=="success"){
    				if(data.type=="alert"){
    					showToast(data.message,"success")
    				}
    				else if(data.type=="image"){
    					showToast(data.message,"success");
    					$("#"+data.imgid).attr("src",data.imgurl);
    				}
    				else if(data.type=="div"){
    					$("#"+data.divid).html(data.message);
    				}
    				else if(data.type=="url"){
    					showToast(data.message,"success");
    					setInterval(function(){ window.location=data.url; }, 2000);
    				}else if(data.type=="popup"){
    					showToast(data.message,"success");
    				}else{
    					showToast(data.message,"success");
    				}
    				//resetbutton.click();
    			}
    			else if(data.status=="error"){
    				showToast(data.message,"error");
    			}
    		}
    	});
        $$(".ms-lang").on('click',function(){
           mainView.router.reloadPage();
       })
       $$('img.lazy').trigger('lazy');
       ImportJs("language/"+lang+".js");
	   setLanguage(lang);
       if(page.name=="profile"){
           ImportJs("js/profile.js");
       }else if(page.name=="about"){
            getPageDetail('Aboutus');
       }else if(page.name=="service"){
            getPageDetail('Services');
       }else if(page.name=="faq"){
            getPageDetail('faq');
       }else if(page.name=="setting"){

       }else if(page.name=="feedback"){

       }else if(page.name=="privacy_policy"){
            getPageDetail('Privacypolicy');
       }else if(page.name=="terms_of_uses"){
            getPageDetail('TermsofUses');
       }else if(page.name=="index"){
		  ImportJs("js/index.js");
       }
 });
function getAutocomplete(id){
	var input = document.getElementById(id);
	var autocomplete = new google.maps.places.Autocomplete(input);
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var place = autocomplete.getPlace();
		console.log(place)
		var prefix=$("#"+id).attr("data-prefix");
		if($("#"+prefix+"_lat").length>0 && $("#"+prefix+"_long").length>0){
			$('#'+prefix+'_lat').val(place.geometry.location.lat());
			$('#'+prefix+'_long').val(place.geometry.location.lng());
			if($("#from_lat").val()!="" && $("#from_long").val()!="" && $("#to_lat").val()!="" && $("#to_long").val()!=""){
				GetRoute($("#from_lat").val(),$("#from_long").val(),$("#to_lat").val(),$("#to_long").val())
			}
		}
	});
	
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function listCookies() {
    //console.log("mragank\n"+document.cookie);
}
function setLatLang(lat_p,long_p){
   lat=lat_p;long=long_p;
   $$(".lat").val(lat);
   $$(".long").val(long);
	$.getJSON("api/common.php",{"type":"update-latlong","long":long,"lat":lat},function(data){
	   
    })
}

function setLanguage(language){
   $$(".lang").val(language);
   lang=language;
   $.getJSON("api/common.php",{"type":"update-language","lang_my":lang},function(data){
	   
   })
}
function ImportJs(jsfile){
   var imported = document.createElement('script');
   imported.src = jsfile;
   document.head.appendChild(imported);
	//console.log(jsfile)
}
function showToast(msg,type){
	myApp.addNotification({
        message: msg,
    });
}
function GetRoute(lat1, long1, lat2, long2) {
	/*var fromLocation = new google.maps.LatLng(lat1, long1);
	var mapOptions = {
		zoom: 7,
		center: fromLocation
	};
	map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);
	directionsDisplay.setMap(map);
	directionsDisplay.setPanel(document.getElementById('dvPanel'));*/

	//*********DIRECTIONS AND ROUTE**********************//
	source = document.getElementById("autocomplete_from").value;
	destination = document.getElementById("autocomplete_to").value;

	/*var request = {
		origin: source,
		destination: destination,
		travelMode: google.maps.TravelMode.DRIVING
	};
	directionsService.route(request, function (response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
		}
	});*/

	//*********DISTANCE AND DURATION**********************//
	var service = new google.maps.DistanceMatrixService();
	service.getDistanceMatrix({
		origins: [source],
		destinations: [destination],
		travelMode: google.maps.TravelMode.DRIVING,
		unitSystem: google.maps.UnitSystem.METRIC,
		avoidHighways: false,
		avoidTolls: false
	}, function (response, status) {
		if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
			var distance = response.rows[0].elements[0].distance.text;
			var duration = response.rows[0].elements[0].duration.text;
			$("#disstance").html("Distance: " + distance + " Duration:" + duration).show();;
		} else {
			alert("Unable to find the distance via road.");
		}
	});
}