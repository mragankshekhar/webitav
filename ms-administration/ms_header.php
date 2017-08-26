<header class="navbar navbar-fixed-top">
    <div class="pull-left"> <a class="navbar-brand" href="<?php echo PATH_ADMIN ?>">
            <div class="navbar-logo">AdminManager</div>
        </a> </div>
    <div class="pull-right header-btns">

        <div class="btn-group user-menu">
            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown"> <span class="glyphicons glyphicons-user"></span> <b><?php echo $LinksDetails["site_name"] ?></b> </button>
            <button type="button" class="btn btn-sm dropdown-toggle padding-none" data-toggle="dropdown"> <img src="<?php echo $LinksDetails["logo"] ?>" alt="user avatar" width="28" height="28" /> </button>
            <ul class="dropdown-menu checkbox-persist" role="menu">
                <li class="menu-arrow">
                    <div class="menu-arrow-up"></div>
                </li>
                <li class="dropdown-header">Your Account <span class="pull-right glyphicons glyphicons-user"></span></li>
                <li>
                    <ul class="dropdown-items">
                        <li>
                            <div class="item-icon"><i class="fa fa-envelope-o"></i> </div>
                            <a class="item-message" href="<?php echo URL_ROOT ?>ms-administration/?page_id=media">Media</a> </li>
                        <li>
                            <div class="item-icon"><i class="fa fa-calendar"></i> </div>
                            <a class="item-message" href="<?php echo URL_ROOT ?>ms-administration/?page_id=calendar">Calendar</a> </li>
                        <li class="border-bottom-none">
                            <div class="item-icon"><i class="fa fa-cog"></i> </div>
                            <a class="item-message" href="<?php echo URL_ROOT ?>ms-administration/?page_id=customize">Theme Settings</a> </li>
                        <li class="padding-none">
                            <div class="dropdown-lockout"><i class="fa fa-external-link"></i> <a target="_blank" href="<?php echo URL_ROOT ?>">View Site</a></div>
                            <div class="dropdown-signout"><i class="fa fa-sign-out"></i> <a href="logout.php">Sign Out</a></div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</header>