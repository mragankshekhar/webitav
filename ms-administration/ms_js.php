<!-- End: Main -->

<!-- Core Javascript - via CDN -->
<script src="<?php echo PATH_ADMIN ?>js/jquery.min.js"></script>
<script src="<?php echo PATH_ADMIN ?>js/jquery-ui.min.js"></script>
<?php if (!isset($_GET["page_id"])) { ?>
    <script>
        var userAgent = navigator.userAgent.toLowerCase();

        // Figure out what browser is being used
        jQuery.browser = {
            version: (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
            safari: /webkit/.test(userAgent),
            opera: /opera/.test(userAgent),
            msie: /msie/.test(userAgent) && !/opera/.test(userAgent),
            mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent)
        };
    </script>

    <script type='text/javascript' src='<?php echo PATH_ADMIN ?>libs/autocomplete/jquery.autocomplete.pack.js'></script>
    <link rel="stylesheet" type="text/css" href="<?php echo PATH_ADMIN ?>libs/autocomplete/jquery.autocomplete.css" />
    <script type='text/javascript' src='<?php echo PATH_ADMIN ?>ajax.php?type=Lists'></script>

    <script>
        $().ready(function () {
            if ($("#suggest13").length > 0) {
                $("#suggest13").autocomplete(emails, {
                    minChars: 1,
                    width: 250,
                    matchContains: "word",
                    autoFill: false,
                    multiple: false,
                    formatItem: function (row, i, max) {
                        return row.title + "<br /><" + row.reffrence_id + "> " + row.status;
                    },
                    formatMatch: function (row, i, max) {
                        return row.reffrence_id + " " + row.title + " " + row.tender_id;
                    },
                    formatResult: function (row) {
                        return row.reffrence_id;
                    },
                });
            }
        });
    </script>
<?php } ?>
<script> var PATH_ADMIN = '<?php echo PATH_ADMIN; ?>';</script>
<script src="<?php echo PATH_ADMIN; ?>js/jquery.form.js"></script>
<script src="<?php echo PATH_ADMIN ?>js/bootstrap.min.js"></script> <!-- Plugins - Via CDN -->
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>libs/flot/0.8.1/jquery.flot.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN; ?>vendor/plugins/gritter/js/jquery.gritter.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/globalize/globalize.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/daterange/moment.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/daterange/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/colorpicker/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/formswitch/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/jquerymask/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/tags/tagmanager.js"></script>
<!-- Plugins -->
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>js/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/jqueryflot/jquery.flot.resize.min.js"></script><!-- Flot Charts Addon -->
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>vendor/plugins/datatables/js/datatables.js"></script><!-- Datatable Bootstrap Addon -->
<!-- Theme Javascript -->
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>js/uniform.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>js/main.js"></script>
<!--<script type="text/javascript" src="<?php echo PATH_ADMIN ?>js/plugins.js"></script>-->
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>js/jquery.rowsorter.js"></script>
<script type="text/javascript" src="<?php echo PATH_ADMIN ?>js/custom.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

        // Init Theme Core
        Core.init();


        // Init Sparkline Plugin
    });
</script>
<script src="<?php echo URL_ROOT ?>editor/scripts/innovaeditor.js" type="text/javascript"></script>
<script src="<?php echo URL_ROOT ?>editor/scripts/innovamanager.js" type="text/javascript"></script>

<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js" type="text/javascript"></script>
<script src="<?php echo URL_ROOT ?>editor/scripts/common/webfont.js" type="text/javascript"></script>
<script src="<?php echo URL_ROOT ?>editor/config.js" type="text/javascript"></script>