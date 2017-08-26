<?php
include_once("../config.php");
$_SESSION["DIRECTORY"] = DIRECTORY;
$aryForm = array(); //array to hold form data
//print_r($_SESSION);
if (!isset($_SESSION["admin"])) {
    //redirect(PATH_ADMIN."login.php");
    header("Location:" . PATH_ADMIN . "login.php");
} else {
    if ($_POST) {
        if (!isset($_GET["page_id"])) {
            include("dashboard.php");
        } else {
            if (file_exists("manage/" . $_GET["page_id"] . ".php"))
                include("manage/" . $_GET["page_id"] . ".php");
            else
                echo "manage/" . $_GET["page_id"] . ".php";
        }
    }else {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php include("ms_meta.php") ?>
                <style><?php if (isset($LinksDetails["custom-css"])) echo unPOST($LinksDetails["custom-css"]); ?></style>
                <?php include("ms_js.php") ?>
            </head>
            <body class="dashboard" background="<?php echo $LinksDetails["admin_background"] ?>">
                <!-- Start: Header -->
                <?php include("ms_header.php") ?>
                <!-- End: Header -->
                <!-- Start: Main -->
                <div id="main">
                    <!-- Start: Sidebar -->
                    <?php include("ms_side.php"); ?>
                    <!-- End: Sidebar -->
                    <!-- Start: Content -->
                    <section id="content">
                        <div id="topbar">
                            <ol class="breadcrumb">
                                <li><a href="<?php echo PATH_ADMIN ?>"><span class="glyphicon glyphicon-home"></span></a></li>
                                <li><a href="<?php echo PATH_ADMIN ?>">Home</a></li>
                                <li class="active"><?php
                                    if (isset($_GET["page_id"]))
                                        echo $_GET["page_id"];
                                    else
                                        echo "Dashboard";
                                    ?></li>
                            </ol>
        <?php if (!isset($_GET["page_id"])) { ?>
                                <div class="pull-right" style="width:254px;background-color: #fff;border: 1px solid #ccc;margin-top: 8px;margin-right: 7px;">
                                    <form role="search" style="margin:2px;" action="">
                                        <input type="hidden" name="page_id" value="admin_user" />
                                        <input type="hidden" name="action" value="view" />
                                        <div class="input-group">
                                            <input type="text" id="suggest13" class="form-control input-sm" autocomplete="off" placeholder="Search" name="q" style="height:27px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default" type="submit" style="height:27px;"><i class="glyphicon glyphicon-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
        <?php } ?>
                        </div>
                        <div class="container">
                            <?php
                            if (!isset($_GET["page_id"])) {
                                include("dashboard.php");
                            } else {
                                if (file_exists("manage/" . $_GET["page_id"] . ".php")) {
                                    include("manage/" . $_GET["page_id"] . ".php");
                                } else {
                                    echo "manage/" . $_GET["page_id"] . ".php";
                                }
                            }
                            ?>
                        </div>
                    </section>
                    <!-- End: Content -->
                </div>

            </body>
        </html>

        <?php
    }
}
?>