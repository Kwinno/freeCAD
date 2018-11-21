<?php
/**
    Hydrid CAD/MDT - Computer Aided Dispatch / Mobile Data Terminal for use in GTA V Role-playing Communities.
    Copyright (C) 2018 - Hydrid Development Team

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
**/
require('includes/connect.php');
include('includes/config.php');
session_start();
?>
<!DOCTYPE html>
<html>
<?php
$page_name = $LANG['welcome'];
include('includes/header.php')
?>
   <body>
      <div class="container">
         <div class="main">
            <img src="assets/imgs/california.png" class="main-logo" draggable="false"/><br />
            <text>
              <strong><?php echo $LANG['welcomethanks']; ?></strong><br />
              <?php 
                if ($siteSettings['join_validation'] === "yes"){
                  echo $LANG['welcomevalidate'];
                } else {
                  echo $LANG['welcomenonvalidate'];
                }
              ?>
            </text>
            <a href="<?php print($url['login']) ?>"><button class="btn btn-block btn-primary" style="margin-top: 10px;"><?php echo $LANG['login']; ?></button></a>
            <?php echo $ftter; ?>
         </div>
      </div>
      <?php include('includes/js.php'); ?>
   </body>
</html>
