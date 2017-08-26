<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class mss extends upload {

    private $theme; //tempalte name
    private $error_log = false;

    function __construct($themename) {
        $this->assignTemplate($themename);
    }

    public function error_log() {
        return $this->error_log;
    }

    public function theme() {
        return $this->theme;
    }

    public function assignTemplate($themename) {
        $this->theme = $themename;
    }

    public function set_error($error) {
        $this->error_log = $error;
    }

    function setReporting() {
        if ($this->error_log == true) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', PATH_ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
        }
    }
	public function addVisit($status=false){
		if($status==true){
			$myFile = "uploads/visitor/log.txt";
			$myVisitFile = "uploads/visitor/visit.txt";
			$output = array();$mVisit=0;
			$message = array("ip" => $_SERVER["REMOTE_ADDR"], "reffer_from" => $_SERVER['HTTP_REFERER'], "udate" => date("Y-m-d H:i:s"));
			if (file_exists($myFile)) {
			   $output = json_decode(file_get_contents($myFile), true);
			   $mVisit = file_get_contents($myVisitFile);
			   $output[] = $message;
			} else {	
			   $output[] = $message;
			}
			$mVisit=$mVisit+10;
			$fh = fopen($myFile, 'w');
			$fv = fopen($myVisitFile, 'w');
			fwrite($fv, $mVisit);
			fwrite($fh, json_encode($output) . "\n");
			fclose($fh);fclose($fv);
			
			return $mVisit;
		}
	}
    public function input($para = array()) {
        $type = "text";
        $values = "";
        if (isset($para["type"]) && $para["type"] != "") {
            $type = $para["type"];
            unset($para["type"]);
        }
        $extraData = array();
        foreach ($para as $kay => $val) {
            if ($type == "textarea" && $kay == "value") {
                $values = $val;
            } else {
                $extraData[] = $kay . "='" . $val . "'";
            }
        }
        if ($type == "textarea") {
            $input = "<textarea " . implode(" ", $extraData) . ">" . $values . "</textarea>";
        } else {
            $input = "<input type='" . $type . "' " . implode(" ", $extraData) . ">";
        }
        return $input;
    }

    public function button($para = array()) {
        $value = "Submit";
        if (isset($para["value"]) && $para["value"] != "") {
            $value = $para["value"];
            unset($para["value"]);
        }
        $extraData = array();
        foreach ($para as $kay => $val) {
            $extraData[] = $kay . "='" . $val . "'";
        }
        $button = "<button " . implode(" ", $extraData) . ">" . $value . "</button>";
        return $button;
    }

    public function startForm($para = array()) {
        $method = "post";
        if (isset($para["type"]) && $para["method"] != "") {
            $method = $para["method"];
            unset($para["method"]);
        }
        $extraData = array();
        foreach ($para as $kay => $val) {
            $extraData[] = $kay . "='" . ($kay == 'action' ? URL_ROOT . "ajax/" . $val : $val) . "'";
        }
        $form = "<form method='" . $method . "' " . implode(" ", $extraData) . ">";
        return $form;
    }

    public function endForm($para = array()) {
        return "</form>";
    }

}
