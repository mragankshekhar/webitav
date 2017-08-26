<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<title><?php echo $LinksDetails["general_meta_title"];if(isset($_GET["page_id"]))echo " | ".$_GET["page_id"] ?></title>
<meta name="keywords" content="<?php echo $LinksDetails["general_meta_tags"] ?>" />
<meta name="description" content="<?php echo $LinksDetails["general_meta_desc"] ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
var URL_ROOT='<?php echo URL_ROOT ?>';
var PATH_ADMIN='<?php echo PATH_ADMIN ?>';
</script>
<!-- Font CSS  -->
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700">

<!-- Core CSS  -->
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>fonts/glyphicons_pro/glyphicons.min.css">

<!-- Plugin CSS -->
<link type="text/css" rel="stylesheet" href="<?php echo PATH_ADMIN ?>css/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/gritter/css/jquery.gritter.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/datatables/css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="vendor/plugins/datatables/extras/TableTools/media/css/TableTools.css"><!-- Datatables TableTools Addon-->
<link rel="stylesheet" type="text/css" href="vendor/editors/xeditable/css/bootstrap-editable.css">
<link rel="stylesheet" type="text/css" href="vendor/plugins/chosen/chosen.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/animate.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/chosen/chosen.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/colorpicker/colorpicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/datepicker/datepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/daterange/daterangepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/formswitch/css/bootstrap-switch.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>vendor/plugins/tags/tagmanager.css">
<!-- Theme CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/theme.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/pages.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/plugins.css">
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/responsive.css">

<!-- Boxed-Layout CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/boxed.css">

<!-- Demonstration CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/demo.css">

<!-- Your Custom CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>css/custom.css">

<!-- Favicon -->
<link rel="shortcut icon" href="<?php echo PATH_ADMIN ?>img/favicon.ico">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<?php if(isset($LinksDetails["script"]) && $LinksDetails["script"]!="")echo $LinksDetails["script"] ?>
