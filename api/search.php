<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("../config.php");
$ch = strtolower($_REQUEST["type"]);
//echo $ch;
if ($ch == "search-where-going") {
    $status = array();
    $status["status"] = "success";
    $status["type"] = "loadPage";
    $status["loadPage"] = "SearchResult.html";
    $status["message"] = "Please wait processing";
    echo json_encode($status);
}