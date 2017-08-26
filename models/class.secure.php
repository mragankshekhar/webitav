<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author mssinfotech
 */
class secure {

//put your code here
    function encrypt_decrypt($action, $string, $secret_key, $secret_iv) {
        $output = false;

        $encrypt_method = "AES-256-CBC";

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    function password($pass) {
        $salt = "CrazyassLongSALTThatMakesYourUsersPasswordVeryLong123!!312567__asdSdas";
        return hash('sha256', $salt . $pass);
    }

}
