<?php
session_name('hydrid');
session_start();
require 'inc/connect.php';
require 'inc/config.php';
require 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'Staff Panel';
require_once('inc/page-top.php');

if (staff_access && staff_siteSettings) {
  //any page php is executed here
}

$view = strip_tags($_GET['m']);
?>
<head>
<script src="assets/js/pages/staff.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("input[type='submit']").attr("disabled", false);

    $("form").submit(function(){
      $("input[type='submit']").attr("disabled", true);
      setTimeout(function(){ $("input[type='submit']").attr("disabled", false); }, 30000);
      return true;
    })
    $('#updateSiteName').ajaxForm(function (error) {
        error = JSON.parse(error);
        if (error['msg'] === "") {
            toastr.success('Site Name Updated', 'System:', {timeOut: 10000})
        } else {
            toastr.error(error['msg'], 'System:', {timeOut: 10000})
        }
    });
});
</script>
</head>
<body>
    <?php require_once('inc/top-nav.php'); ?>

    <!-- CONTENT START -->
    <div class="wrapper m-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                <h4 class="page-title"><?php echo $page['name']; ?></h4>
                </div>
            </div>
            <!-- CONTENT HERE -->
            <?php if (staff_access && staff_siteSettings): ?>
            <?php switch($view):
			         case "settings": ?>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label for="IdentityVerification">Identity Verification</label>
                      <select class="form-control" id="IdentityVerification" onchange="setIdentityVerification(this.value)">
                        <option selected="true" disabled="disabled"><?php if ($settings['identity_validation'] === "no") {
                          echo 'No';
                        } else {
                          echo 'Yes';
                        } ?></option>
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="SignUpVerification">Account Verification</label>
                      <select class="form-control" id="SignUpVerification" onchange="setAccountVerification(this.value)">
                        <option selected="true" disabled="disabled"><?php if ($settings['account_validation'] === "no") {
                          echo 'No';
                        } else {
                          echo 'Yes';
                        } ?></option>
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="darkmode">Dark Mode</label>
                      <select class="form-control" id="darkmode" onchange="setDarkTheme(this.value)">
                        <option selected="true" disabled="disabled"><?php
                        if ($settings['dark_mode'] === "true") {
                          echo 'Yes';
                        } elseif ($settings['dark_mode'] === "false") {
                          echo 'No';
                        }?>
                        </option>
                        <option value="true">True</option>
                        <option value="false">False</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="darkmode">Require Steam Login</label>
                      <select class="form-control" id="steam_login" onchange="setSteamLogin(this.value)">
                        <option selected="true" disabled="disabled"><?php
                        if ($settings['steam_required'] === "true") {
                          echo 'Yes';
                        } elseif ($settings['steam_required'] === "false") {
                          echo 'No';
                        }
                        ?>
                        </option>
                        <option value="true">True</option>
                        <option value="false">False</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="darkmode">Civ Side Warrants</label>
                      <select class="form-control" id="steam_login" onchange="setCivSideWarrants(this.value)">
                        <option selected="true" disabled="disabled"><?php
                        if ($settings['civ_side_warrants'] === "true") {
                          echo 'Yes';
                        } elseif ($settings['civ_side_warrants'] === "false") {
                          echo 'No';
                        }
                        ?>
                        </option>
                        <option value="true">True</option>
                        <option value="false">False</option>
                      </select>
                    </div>
                  </div>
            </div>
            <div class="row">
              <div class="col">
                <form id="updateSiteName" action="inc/backend/staff/settings/setSiteName.php" method="post">
                  <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input class="form-control" type="text" required="" name="site_name" value="<?php echo $settings['name']; ?>" placeholder="<?php echo $settings['name']; ?>">
                    <button class="btn btn-success btn-block" type="submit">Update</button>
                  </div>
                </form>
              </div>
            </div>
            <?php break; ?>

            <?php case "pending-users":?>
              <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Pending Users</h4>
                        <div id="getPendingUsers"></div>
                    </div>
                </div>
            </div>
            <?php break; ?>
            
            <?php endswitch; ?>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">
                <strong>You do not have permission to access this page.</strong>
              </div>
            <?php endif; ?>
    </div>
    <!-- CONTENT END -->
    <?php require_once('inc/copyright.php'); ?>
    <?php require_once('inc/page-bottom.php'); ?>
