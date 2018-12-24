<?php
require 'inc/connect.php';

require 'inc/config.php';

$page['name'] = 'Login';
require_once ('inc/page-top.php');

?>
    <head>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#userLogin').ajaxForm(function (error) {
                    error = JSON.parse(error);
                    if (error['msg'] === "") {
                        toastr.success('Logged in... Redirecting', 'System:', {
                            timeOut: 10000
                        })
                        window.location.href = "<?php echo $url['index']; ?>";
                    } else {
                        toastr.error(error['msg'], 'System:', {
                            timeOut: 10000
                        })
                    }
                });
            });
        </script>
    </head>
    <body>
        <?php
        if (isset($_GET['error']) && strip_tags($_GET['error']) === 'banned') {
            throwError('Your account has been banned from accessing this Panel. If you have any further questions, Please make a ban appeal.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'access') {
            throwError('You must be logged in to access that page.');
        }
        ?>
        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="text-center">
                <a href="<?php echo $url['index']; ?>" class="logo"><span>HydridSystems</span></a>
            </div>
        	<div class="m-t-40 card-box">
                <div class="text-center">
                    <h4 class="text-uppercase font-bold mb-0">Login</h4>
                </div>
                <div class="p-20">
                    <form class="form-horizontal m-t-20" id="userLogin" action="inc/backend/user/auth/userLogin.php" method="POST">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" name="username" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12">
                                <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" type="submit">Log In</button>
                            </div>
                        </div>
                        <div class="col-sm-12 text-center">
                            <p class="text-muted">Don't have an account? <a href="<?php echo $url['register']; ?>" class="text-primary m-l-5"><b>Sign Up</b></a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php require_once('inc/page-bottom.php'); ?>
