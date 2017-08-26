<?php
include_once("../config.php");
if (isset($_GET["login"])) {
    if (trim($_POST["uid"]) == "") {
        echo "error==>Please enter admin name";
    } elseif ($_POST["pass"] == "") {
        echo "error==>Please enter admin password";
    } elseif (trim($_POST["uid"]) == $LinksDetails["admin_uname"] && trim($_POST["pass"]) == $LinksDetails["admin_pswd"]) {
        $_SESSION["admin"]["uid"] = 0;
        $_SESSION["admin"]["uname"] = "Admin";
        $_SESSION["admin"]["adminroll"] = 1;
        echo "success==>" . PATH_ADMIN;
    } else {
        $sqlUserArray = array($_POST["uid"], md5($_POST["pass"]));
        $ids = $db->getRow("select * from  " . SITE_USER . " where (username =?) and pass=? and status=1", $sqlUserArray);
        //echo $db->getLastQuery();exit;
        if (is_array($ids) && count($ids) > 0) {
            $_SESSION["admin"]["uid"] = $ids['id'];
            $_SESSION["admin"]["uname"] = $_POST["uid"];
            $_SESSION["admin"]["adminroll"] = $ids["role"];
            $historyData = array('f_ip' => $_SERVER['REMOTE_ADDR'],
                'f_browser' => $yourbrowser,
                'userid' => $ids['id'],
                'uname' => $_POST["uid"],
                'email' => $ids["email"]
            );
            $db->updateAry(SITE_USER, array("is_online" => 1), " where id='" . $_SESSION["user"]["uid"] . "'");
            $db->insertAry(LOGIN_HISTORY, $historyData);
            echo "success==>" . PATH_ADMIN;
        } else {
            echo "error==>invalid admin is or password";
        }
    }
} else {
    if (isset($_SESSION["admin"]))
        redirect(PATH_ADMIN);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <!-- Meta, title, CSS, favicons, etc. -->
            <meta charset="utf-8">
            <title>ms-administration</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <!-- Font CSS  -->
            <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700">

            <!-- Core CSS  -->
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>fonts/glyphicons_pro/glyphicons.min.css">

            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>vendor/plugins/gritter/css/jquery.gritter.css">

            <!-- Theme CSS -->
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/theme.css">
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/pages.css">
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/plugins.css">
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/responsive.css">

            <!-- Boxed-Layout CSS -->
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/boxed.css">

            <!-- Demonstration CSS -->
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/demo.css">

            <!-- Your Custom CSS -->
            <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN; ?>css/custom.css">

            <!-- Favicon -->
            <link rel="shortcut icon" href="<?php echo PATH_ADMIN; ?>img/favicon.ico">

            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
              <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
            <![endif]-->
            <style><?php echo unPOST($LinksDetails["custom-css"]); ?></style>
        </head>

        <body class="login-page" style="background-image:url(../uploads/media/bg1.jpg); background-size:cover">

            <!-- Start: Main -->
            <div id="main">
                <div class="container">
                    <br>
                    <br>
                    <br><br>
                    <div class="navbar-logo" style="margin: 0 auto;display: block;width: 390px; text-align:center;"><img style="max-height: 100px;" src="<?php echo $LinksDetails["logo"] ?>" /></div>
                    <br>

                    <div class="row">
                        <div class="panel">

                            <div class="panel-heading">
                                <div class="panel-title"> <span class="glyphicon glyphicon-lock"></span> Login </div>
                            </div>
                            <form class="cmxform form_ajax" id="altForm" method="post" action="<?php echo SELF ?>?login=login">
                                <div class="panel-body">

                                    <div class="form-group">
                                        <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span> </span>
                                            <input type="text" name="uid" class="form-control phone" maxlength="100" autocomplete="off" placeholder="User Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span> </span>
                                            <input type="password" name="pass" class="form-control product" maxlength="10" autocomplete="off" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="progress progress-striped active"  id="progressDiv" style="display:none">
                                        <div class="progress-bar"  role="progressbar" aria-valuemin="0" id="progress" aria-valuemax="100" style="width:100%">
                                            <span class="sr-only" id="progresstext">100% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer"> <span class="panel-title-sm pull-left hide" style="padding-top: 7px;"><a> Forgotten Password?</a></span>
                                    <div class="form-group margin-bottom-none">
                                        <input class="btn btn-primary pull-right" type="submit" id="submit" value="Login" />
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: Main -->

            <!-- Core Javascript - via CDN -->
            <script src="<?php echo PATH_ADMIN; ?>js/jquery.min.js"></script>
            <script> var PATH_ADMIN = '<?php echo PATH_ADMIN; ?>';</script>
            <script src="<?php echo PATH_ADMIN; ?>js/jquery.form.js"></script>
            <script src="<?php echo PATH_ADMIN; ?>js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="<?php echo PATH_ADMIN; ?>vendor/bootstrap/holder.js"></script>
            <script type="text/javascript" src="<?php echo PATH_ADMIN; ?>vendor/plugins/gritter/js/jquery.gritter.min.js"></script>
            <script src="<?php echo PATH_ADMIN; ?>js/bootstrap.min.js"></script> <!-- Theme Javascript -->
            <script type="text/javascript" src="<?php echo PATH_ADMIN; ?>js/uniform.min.js"></script>
            <script type="text/javascript" src="<?php echo PATH_ADMIN; ?>js/main.js"></script>
            <script type="text/javascript" src="<?php echo PATH_ADMIN; ?>js/custom.js"></script>
            <script type="text/javascript">

                jQuery(document).ready(function () {
                    // Init Theme Core
                    Core.init();

                });

            </script>
        </body>
    </html>
    <?php
}
?>
