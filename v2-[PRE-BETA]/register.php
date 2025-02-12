<?php
require_once 'inc/connect.php';
require_once 'inc/config.php';
$page['name'] = 'Register';
?>
<?php include 'inc/page-top.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#userRegister').ajaxForm(function(error) {
            console.log(error);
            if (error['msg'] == "") {
                toastr.success('Account Created! You will be redirected to the Login page shortly.', 'System', {
                    timeOut: 10000
                })
                window.location.href = "<?php echo $url['login']; ?>";
            } else {
                toastr.error(error['msg'], 'System', {
                    timeOut: 10000
                })
            }
        });
    });
</script>

<body>
    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class="m-t-40 card-box-auth">
          <div class="text-center">
              <a href="https://discord.gg/NeRrWZC" class="logo"><span>freeCAD</span></a>
          </div>
            <div class="text-center">
                <h4 class="text-uppercase font-bold mb-0">Register</h4>
            </div>
            <div class="p-20">
                <form class="form-horizontal m-t-20" id="userRegister" action="inc/backend/user/auth/userRegister.php" method="POST">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="email" required="" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" required="" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-30">
                        <div class="col-xs-12">
                            <button class="btn btn-dark btn-bordred btn-block waves-effect waves-light" type="submit">Register</button>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <p class="text-muted">Already have an account? <a href="<?php echo $url['login']; ?>" class="text-primary m-l-5"><b>Login</b></a></p>
                        <p class="text-muted">Powered by <strong><a href="https://discord.gg/NeRrWZC">freeCAD</a></strong></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'inc/page-bottom.php'; ?>
    <!-- this community uses freeCAD. freeCAD is a free and open-source CAD/MDT system. Find our discord here: https://discord.gg/NeRrWZC -->
