<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function autoloader($class_name) {
    $directories = array(
        'models'
    );
    $fileNameFormates = array(
        'class.%s.php'
    );
    foreach ($directories as $directory) {
        foreach ($fileNameFormates as $fileNameFormate) {
            $path = PATH_ROOT . DS . $directory . DS . sprintf($fileNameFormate, $class_name);
            //echo $path . "<br/>";
            if (file_exists($path)) {
                include_once $path;
            }
        }
    }
}

spl_autoload_register('autoloader');
