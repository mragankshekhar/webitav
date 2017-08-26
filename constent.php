<?php
/* * ***********language connect************ */
//print_r($_SESSION);exit;
$lang = '';$_SESSION['lang_set']=0;
if (isset($_GET['lang']) && $_GET['lang'] != "") {
    $lang = $_GET['lang'];
} else if (isset($_SESSION['lang']) && $_SESSION['lang'] != "") {
    $lang = $_SESSION['lang'];
	$_SESSION['lang_set']=1;
} else if (isset($_COOKIE['lang']) && $_COOKIE['lang'] != "") {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'en';
}
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (3600 * 24 * 30));
$LinksDetails = fetchSetting();
require_once(PATH_ROOT . DS . "language" . DS . $lang . ".php");
/* * ***********language connect************ */
/* * ***********currency connect************ */
$cur = "";
if (isset($_GET["cur"]) && $_GET["cur"] != "") {
    $cur = $_GET["cur"];
} elseif (isset($_SESSION["cur"]) && $_SESSION["cur"] != "") {
    $cur = $_SESSION["cur"];
} else if (isset($_COOKIE['cur']) && $_COOKIE['cur'] != "") {
    $cur = $_COOKIE['cur'];
} else {
    $cur = $LinksDetails["currency_code"];
}
$_SESSION["cur"] = $cur;
setcookie('cur', $cur, time() + (3600 * 24 * 30));
/* * ***********currency connect************ */
/* * ***********userid and password connect************ */
$username = $password = ""; $myDetail=array();
if (isset($_SESSION["user"]["uid"]) && $_SESSION["user"]["uid"] != "") {
	$myDetail=$user->myDetail($_SESSION["user"]["uid"]);
} else if (isset($_COOKIE['userid']) && $_COOKIE['userid'] != "") {
    $myDetail=$user->myDetail($_COOKIE['userid']);
	$_SESSION["user"]["uid"]=$_COOKIE['userid'];
} 
/* * ***********userid and password connect************ */
/* * ***********currency connect************ */
$lat = $long = "";
if (isset($_GET["lat"]) && $_GET["lat"] != "") {
    $lat = $_GET["lat"];$long = $_GET["long"];
} elseif (isset($_SESSION["lat"]) && $_SESSION["lat"] != "") {
    $lat = $_GET["lat"];$long = $_GET["long"];
} else if (isset($_COOKIE['lat']) && $_COOKIE['lat'] != "") {
   $lat = $_GET["lat"];$long = $_GET["long"];
}
$_SESSION["lat"] = $lat;$_SESSION["long"] = $long;
setcookie('lat', $lat, time() + (3600 * 24 * 30));setcookie('long', $long, time() + (3600 * 24 * 30));
/* * ***********currency connect************ */
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('PAGE_PER_NO', 6);
define("URL_IMAGES", URL_ROOT . "images/");

define("PATH_ADMIN", URL_ROOT . $varAdminFolder . "/");
//echo PATH_ADMIN;exit;
define("ADMIN_JS", PATH_ADMIN . "js/");
define("ADMIN_CSS", PATH_ADMIN . "css/");
define("URL_ADMIN_IMGAGES", URL_ROOT . "assets/");

/* * ***********mss connect************ */
define('API_KEY', 'mss123456789demo');
//echo @file_get_contents("http://mssinfotech.com/?action=".$_SERVER["HTTP_HOST"]);
/* * ***********mss config for email and connect************ */
//*********** Constent for define image ****************//
define("IMG", URL_ROOT . "function/timthumb.php?src=");
//************ Constent for define image *********//
if (isset($LinksDetails["mail_Username"]) && $LinksDetails["mail_Username"] != "") {
    define('MAIL_USERNAME', $LinksDetails["mail_Username"]);
}
if (isset($LinksDetails["mail_Password"]) && $LinksDetails["mail_Password"] != "") {
    define('MAIL_PASSWORD', $LinksDetails["mail_Password"]);
}
if (isset($LinksDetails["mail_sender_name"]) && $LinksDetails["mail_sender_name"] != "") {
    define('MAIL_SENDER_NAME', $LinksDetails["mail_sender_name"]);
}
if (isset($LinksDetails["mail_sender_email"]) && $LinksDetails["mail_sender_email"] != "") {
    define('MAIL_SENDER_EMAIL', $LinksDetails["mail_sender_email"]);
}
if (isset($LinksDetails["mail_SMTPSecure"]) && $LinksDetails["mail_SMTPSecure"] != "") {
    define('MAIL_SMTPSECURE', $LinksDetails["mail_SMTPSecure"]);
}
if (isset($LinksDetails["mail_Host"]) && $LinksDetails["mail_Host"] != "") {
    define('MAIL_HOST', $LinksDetails["mail_Host"]);
}
if (isset($LinksDetails["mail_Port"]) && $LinksDetails["mail_Port"] != "") {
    define('MAIL_PORT', $LinksDetails["mail_Port"]);
}

define("URL_ADMIN", URL_ROOT . $varAdminFolder . "/");
define("URL_ADMIN_HOME", URL_ADMIN . "index.php");
define("URL_ADMIN_CSS", URL_ADMIN . "css/");
define("URL_ADMIN_JS", URL_ADMIN . "js/");


define("URL_ADMIN_IMG", URL_ADMIN . "images/");
define("SELF", basename($_SERVER['PHP_SELF']));
define("PAGE_NAME", strtolower(basename($_SERVER['REQUEST_URI'])));
define("PATH_UPLOAD", PATH_ROOT . DS . "uploads" . DS);
define("PATH_MEDIA", PATH_UPLOAD . DS . "media" . DS);
define("PATH_UPLOAD_PHOTO", PATH_UPLOAD . "images" . DS);
define("ROOT", URL_ROOT . "templates/" . $mss->theme() . "/");
