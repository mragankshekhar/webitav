$$(".open-login-ms").on('click',function(e){
    //alert("yes")
    var langs=$$(this).attr("mydata-lang")
    console.log(langs)
    setLanguage(langs);
    mainView.router.reloadPage("login.html");
})
const messaging = firebase.messaging();
messaging.requestPermission()
  .then(function(){
	  return messaging.getToken();
  })
  .then(function(token){
    if (token) {
        setReg_id(token);
    } else {
      // Show permission request.
      console.log('No Instance ID token available. Request permission to generate one.');
      // Show permission UI.
    }
  })
  .catch(function(e){
	  console.log(e+' no permission')
  })
myApp.onPageInit('*', function (page) {
    $$(".facebook-button").on("click",function(){
        var provider = new firebase.auth.FacebookAuthProvider();
        //provider.addScope('email');
        //firebase.auth().languageCode = 'fr_FR';
        provider.setCustomParameters({
            'display': 'popup'
        });
        firebase.auth().signInWithPopup(provider).then(function(result) {
          // This gives you a Facebook Access Token. You can use it to access the Facebook API.
          var token = result.credential.accessToken;
          // The signed-in user info.
          var user = result.user;
          SocialUserChecker(user.uid,user.displayName,user.email,user.photoURL,token,"facebook");
          // ...
        }).catch(function(error) {
          // Handle Errors here.
          var errorCode = error.code;
          var errorMessage = error.message;
          // The email of the user's account used.
          var email = error.email;
          // The firebase.auth.AuthCredential type that was used.
          var credential = error.credential;
          console.log(errorMessage);
          // ...
        });
    });
    $$(".google-button").on("click",function(){
        var provider = new firebase.auth.GoogleAuthProvider();
        //provider.addScope('https://www.googleapis.com/auth/contacts.readonly');
        //firebase.auth().languageCode = 'pt';
        provider.setCustomParameters({
            'login_hint': 'user@example.com'
          });
          firebase.auth().signInWithPopup(provider).then(function(result) {
            // This gives you a Google Access Token. You can use it to access the Google API.
            var token = result.credential.accessToken;
            // The signed-in user info.
            var user = result.user;
            SocialUserChecker(user.uid,user.displayName,user.email,user.photoURL,token,"google");
            // ...
          }).catch(function(error) {
            // Handle Errors here.
            var errorCode = error.code;
            var errorMessage = error.message;
            // The email of the user's account used.
            var email = error.email;
            // The firebase.auth.AuthCredential type that was used.
            var credential = error.credential;
            console.log(errorMessage);
            // ...
          });
    });
});
function SocialUserChecker(uid,fullname,email,avatar,token,type){
    var lat=$(".lat").val();var lang=$(".lang").val();var long=$(".long").val();
    $.post("api/login.php",{type:"check_social_login",lat:lat,longi:long,lang:lang,id:uid,name:fullname,email:email,social:type,avatar:avatar,token:token}, function(data){
         window.location='./';
    });
}