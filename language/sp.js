$(function () {
    // Lets be professional, shall we?
    "use strict";

    // Some variables for later
    var dictionary, set_lang;
    //Spanish

    var dictionary={
            "LOGIN_FORM": "Forma de la conexión",
            "LOGIN": "Conexión",
            "SIGNUP": "Inscripción",
            "FORGOT": "Olvidó",
            "BACK": "Parte posterior",
            "REMEMBER": "Recuerde",
            "LOGIN_WITH": "Conexión con",
            "FACEBOOK": "Facebook",
            "GOOGLE": "Google",
		
			"CHECKNETWORK":"controle por favor su red",
		

            "SEND": "Envíe",
            "CHANGE_LANGUAGE": "Cambie el lenguaje",
            "OR": "O",
            "CREATE_NEW_PROFILE": "Cree un nuevo perfil",

            //Registration
            "ALREADY_HAVE_LOGINID":"Alrady tiene identificación de la conexión",
            "USERNAME": "Nombre de utilizador",
            "PASSWORD": "Palabra de paso",
            "RE_PASSWORD": "Escriba de nuevo la palabra de paso a máquina",
            "FULLNAME": "Nombre completo",
            "EMAIL": "Email",
             "REGISTER_NOW": "Registro ahora",

            //user menu
            "SERVICE": "Servicio",
            "ABOUT": "Sobre",
            "FAQ": "FAQ",
            "SETTING": "Configuración",
            "FEEDBACK": "Feedback",
            "PRIVACY_POLICY": "Política de aislamiento",
            "TERMS_OF_USES": "Términos de aplicaciones",
            "SHARE": "Parte",
            "EXIT": "Salida",

            //Profile
            "GENERAL_INFORMATION": "General Information",
            "PHONE": "Teléfono",
            "GENDER": "Género",
            "MALE": "Varón",
            "FEMALE": "Hembra",
            "DOB": "Fecha de nacimiento",
            "PROFILE": "Perfil",
            "CHANGE_LANGUAGE": "Cambie el lenguaje",
            "DELETE_ACCOUNT": "Cuenta de la cancelación",
            "LOGOUT": "Fin de comunicación",
			"DISCOVER": "Descubra",
			"MYTRIP": "Mi viaje",
			"ME": "Yo",

            //heading
            "MY_PROFILE":"Mi perfil",
            "HEADING_SERVICE": "Servicio",
            "HEADING_FAQ": "FAQ",
            "HEADING_SETTING": "Configuración",
            "HEADING_FEEDBACK": "Feedback",
            "HEADING_PRIVACY_POLICY": "Política de aislamiento",
            "HEADING_TERMS_OF_USES": "Términos de aplicaciones",
            
    }

     set_lang = function (dictionary) {
         $("[data-lang]").text(function () {
             var key = $(this).data("lang");
             if (dictionary.hasOwnProperty(key)) {
                 return dictionary[key];
             }
         });
          $("[data-placeholder_lang]").attr("placeholder",function(){
               var key = $(this).data("placeholder_lang");
               if (dictionary.hasOwnProperty(key)) {
                   return dictionary[key];
               }
           })
     };
     set_lang(dictionary);
});