<?php

ob_start();
include_once("config.php");
$loginData = array("is_online" => 0,
    "last_login" => date("Y-m-d H:i:s"),);
$db->updateAry(SITE_USER, $loginData, " where id='" . $_SESSION["user"]["uid"] . "'");
$historyData = array('f_ip' => $_SERVER['REMOTE_ADDR'],
    'f_browser' => $yourbrowser,
    'userid' => $ids['id'],
    'uname' => $_POST["uid"],
    'email' => $ids["email"],
    'status' => 'logout'
);
$db->insertAry(LOGIN_HISTORY, $historyData);
unset($_COOKIE["userid"]);
setcookie('userid', null, - 1);
unset($_SESSION["user"]["uid"]);
session_destroy();
session_regenerate_id();
header("Location:" . URL_ROOT);
ob_flush();
?>