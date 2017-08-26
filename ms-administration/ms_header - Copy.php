<header class="navbar navbar-fixed-top">
  <div class="pull-left"> <a class="navbar-brand" href="<?php echo PATH_ADMIN ?>">
    <div class="navbar-logo"><img src="<?php echo $LinksDetails["logo"] ?>" style="max-height: 51.5px;position: absolute;left: 0;top: 0;" /></div>
    </a> </div>
  <div class="pull-right header-btns">
  	
    <div class="btn-group user-menu">
      <a class="btn btn-sm">Welcome <?php echo $_SESSION["admin"]["uname"]."(".ucfirst(strtolower($myDetails["name"])).")" ?></a>
      <a href="logout.php" class="btn btn-sm"> <img src="<?php echo URL_ROOT ?>uploads/avatar/16/16/<?php echo $myDetails["avatar"] ?>"> <b>Logout</b> </a>
    </div>
  </div>
</header>