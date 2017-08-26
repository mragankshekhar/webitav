<?php
include("../config.php");
$ch = strtolower($_REQUEST["type"]);
if($ch== "update-language") {
	$lang=$_REQUEST["lang_my"];
	$_SESSION['lang'] = $lang;
	setcookie('lang', $lang, time() + (3600 * 24 * 30));
	echo "langSet---".$lang;
}elseif($ch== "update-latlong") {
	$lat=$_REQUEST["lat_my"];
	$long=$_REQUEST["long_my"];
	$_SESSION['lat'] = $lang;
	$_SESSION['long'] = $lang;
	setcookie('long', $long, time() + (3600 * 24 * 30));
	setcookie('lat', $lat, time() + (3600 * 24 * 30));
	echo "langSet---".$long."---".$lat;
}