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

require('classes/lib/password.php');
?>
<html>
    <?php
        $page_name = $LANG['login'];
        include('includes/header.php')
    ?>
    <head>
    <script type="text/javascript">
    $(document).ready(function() {
    $('#userLogin').ajaxForm(function(error) { 
        error = JSON.parse(error);
        if (error['msg'] === "") {
            toastr.success('Logged in... Redirecting', 'System:', {timeOut: 10000})
            window.location.href = "<?php echo $url['index']; ?>?logged=in"; 
        } else {
            toastr.error(error['msg'], 'System:', {timeOut: 10000})
        }
    });
   });
   </script>
    </head>
    <body>
		<?php 
        if (isset($_GET['account']) && strip_tags($_GET['account']) === 'banned') {
            throwError('Your account has been banned from accessing this Panel. If you have any further questions, Please make a ban appeal.');
        }
        ?>
        <div class="container">
            <div class="main">
                <img src="assets/imgs/los_santos.png" class="main-logo" draggable="false"/>
                <div class="main-header">
                    <?php echo $siteSettings['name']; ?> <?php echo $LANG['login']; ?>
                </div>
                <?php print($message); ?>
                <form id="userLogin" action="functions/user/auth/userLogin.php" method="post">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" maxlength="36" data-lpignore="true" required />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" data-lpignore="true" required />
                    </div>
                    <div class="form-group">
                        <input class="btn btn-block btn-primary" type="submit" value="<?php echo $LANG['login']; ?>">
                    </div>
                    <text><?php echo $LANG['needaccount']; ?> <a href="<?php print($url['register']) ?>"><?php echo $LANG['register']; ?></a></text>
                    <?php echo $ftter; ?>
                </form>
            </div>
        </div>
        <?php include('includes/js.php'); ?>
    </body>
</html>
