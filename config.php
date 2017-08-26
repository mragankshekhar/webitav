<?php
ob_start();
@session_start();
$varAdminFolder = "ms-administration";
//https://p3nwvpweb149.shr.prod.phx3.secureserver.net:8443/login_up.php3
if ($_SERVER["SERVER_NAME"] === "mssinfotech.in" || $_SERVER["SERVER_NAME"] === "www.mssinfotech.in") {
    $mysql_user = 'mssinifn_itav'; //'ms2fun';
    $password = "123@qwe";
    $database_host = "localhost";
    $database = "mssinifn_itav";
    define("DIRECTORY", "/itav/");
    define("URL_ROOT", "http://www.mssinfotech.in" . DIRECTORY);
} else if ($_SERVER["SERVER_NAME"] === "mssinfotech.co.uk" || $_SERVER["SERVER_NAME"] === "www.mssinfotech.co.uk") {
    $mysql_user = 'mssinfot_itav'; //'ms2fun';
    $password = "123@qwe";
    $database_host = "localhost";
    $database = "mssinfot_itav";
    define("DIRECTORY", "/itav/");
    define("URL_ROOT", "https://www.mssinfotech.co.uk" . DIRECTORY);
} else {
    $mysql_user = 'root'; //'ms2fun';
    $password = ''; //"123@qwe";
    $database_host = "localhost";
    $database = "payFee";
    define("DIRECTORY", "/MVP/api/");
    define("URL_ROOT", "http://localhost" . DIRECTORY);
}
//print_r($_SESSION);exit;
$table_prefix = "mss_";
define("TABLE_PREFIX", $table_prefix);
define("DS", DIRECTORY_SEPARATOR);
define("PATH_ROOT", dirname(__FILE__));
define("PATH_LIB", PATH_ROOT . DS . "function" . DS);
include_once PATH_ROOT . DS . 'autoloader.php';

ini_set( "short_open_tag", 1 );
$db = new MySqlDb($database_host, $mysql_user, $password, $database);
$user = new user();
/* * ************************-----setup my framework start-------********************** */
$theme = "version";
$mss = new mss($theme);
$mss->set_error(false);
$mss->setReporting();
$visit=$mss->addVisit(false);
/* * ************************-----setup my framework start-------********************** */
require_once(PATH_LIB . 'mailer/PHPMailerAutoload.php');
require_once(PATH_LIB . "table.php");
require_once(PATH_LIB . "functions.php");
include("constent.php");
$includedFile = "";
$pageData = $data = $alert_err = array();
