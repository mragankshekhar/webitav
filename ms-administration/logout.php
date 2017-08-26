<?php
include_once("../config.php");
unset($_SESSION);
session_destroy() ;
session_regenerate_id();
redirect(PATH_ADMIN);
?>