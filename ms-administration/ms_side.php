<aside id="sidebar">

    <div id="sidebar-search">

        <form role="search">

            <input type="text" id="searchmenu_here" class="search-bar form-control" placeholder="Search">

            <i class="fa fa-search field-icon-right"></i>

            <button type="submit" class="btn btn-default hidden"></button>

        </form>

        <div class="sidebar-toggle"> <span class="glyphicon glyphicon-resize-horizontal"></span> </div>

    </div>

    <div id="sidebar-menu">

        <ul class="nav sidebar-nav">

            <li <?php if (!isset($_GET["page_id"])) { ?>class="active" <?php } ?>> <a href="<?php echo PATH_ADMIN ?>"><span class="glyphicons glyphicons-star"></span><span class="sidebar-title">Dashboard</span></a> </li>

            <?php
            $admenu = $db->getRows("select * from " . MENUS . " where is_root=0 and status=1 order by lorder");
            //print_r($admenu);
            if (is_array($admenu) && count($admenu) > 0) {

                foreach ($admenu as $value) {

                    $allsubmenyArry = array();
                    $sqlMenuArray = array($value['mid']);
                    $submenu = $db->getRows("select * from " . MENUS . " where is_root=? and status=1 order by lorder", $sqlMenuArray);

                    $hasSubMenu = "no";

                    if (is_array($submenu) && count($submenu) > 0) {

                        foreach ($submenu as $sub) {

                            $allsubmenyArry[] = $sub['name'];

                            if ("n" != checkPermission($sub['name'], $_SESSION["admin"]["adminroll"])) {

                                $hasSubMenu = "yes";
                            }
                        }
                    }

                    if ($hasSubMenu == "yes") {
                        ?>

                        <li> <a class="accordion-toggle <?php if (in_array($_GET["page_id"], $allsubmenyArry)) echo "menu-open"; ?>" href="#cms"><span class="<?php echo $value['icon'] ?>"></span><span class="sidebar-title"><?php echo $value['header'] ?></span><span class="caret"></span></a>

                            <ul id="cms" class="nav sub-nav">

                                <?php
                                foreach ($submenu as $sub) {

                                    //echo $sub['name']."-".checkPermission($sub['name'],$_SESSION["admin"]["adminroll"]);

                                    if ("n" != checkPermission($sub['name'], $_SESSION["admin"]["adminroll"])) {
                                        ?>

                                        <li <?php if ($_GET["page_id"] == $sub['name']) { ?>class="active" <?php } ?>><a href="./?page_id=<?php echo $sub['name'] ?>"><span class="<?php echo $sub['icon'] ?>"></span><?php echo $sub['header'] ?></a></li><?php
                                    }
                                }
                                ?>

                            </ul>

                        </li>
                        
                        
                        
                        
                        
                        
                        
                        

                        <?php
                    } else {

                        //echo $value['name'] . "-" . checkPermission($value['name'], $_SESSION["admin"]["adminroll"]);

                        if ("n" != checkPermission($value['name'], $_SESSION["admin"]["adminroll"])) {
                            ?>

                            <li <?php if ($_GET["page_id"] == $value['name']) { ?>class="active" <?php } ?>> <a href="?page_id=<?php echo $value['name'] ?>"><span class="<?php echo $value['icon'] ?>"></span><span class="sidebar-title"><?php echo $value['header'] ?></span></a> </li>

                            <?php
                        }
                    }
                }
				
            }
            ?>

        </ul>

    </div>

</aside>
<?php
