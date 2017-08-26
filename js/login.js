$$(".open-login-ms").on('click',function(e){
	//alert("yes")
	var langs=$$(this).attr("mydata-lang")
	console.log(langs)
	setLanguage(langs);
	mainView.router.reloadPage("login.html");
})