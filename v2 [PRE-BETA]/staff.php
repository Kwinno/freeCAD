<?php
session_name('hydrid');
session_start();
require 'inc/connect.php';
require 'inc/config.php';
require 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'Staff Panel';
require_once('inc/page-top.php');

if (staff_access && staff_editUsers) {
  if (isset($_POST['editUserBtn'])) {
    $updateUsername    = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $updateEmail       = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $updateUsergroup   = !empty($_POST['usergroup']) ? trim($_POST['usergroup']) : null;

    $updateUsername    = strip_tags($updateUsername);
    $updateEmail       = strip_tags($updateEmail);
    $updateUsergroup   = strip_tags($updateUsergroup);
    
    $sql = "UPDATE users SET username=?, email=?, usergroup=? WHERE user_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$updateUsername, $updateEmail, $updateUsergroup, $_SESSION['editing_user_id']]);
    header('Location: staff.php?m=edit-user&user-id='.$_SESSION['editing_user_id']);
    exit();
  } elseif (isset($_POST['banUserBtn'])) {
    $banReason    = !empty($_POST['reason']) ? trim($_POST['reason']) : null;
    $banReason    = strip_tags($banReason);
    
    $sql = "UPDATE users SET usergroup=?, ban_reason=? WHERE user_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Banned', $banReason, $_SESSION['editing_user_id']]);
    header('Location: staff.php?m=edit-user&user-id='.$_SESSION['editing_user_id']);
    exit();
  } elseif (isset($_POST['unbanUserBtn'])) {
    $sql = "UPDATE users SET usergroup=?, ban_reason=? WHERE user_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['User', NULL, $_SESSION['editing_user_id']]);
    header('Location: staff.php?m=edit-user&user-id='.$_SESSION['editing_user_id']);
    exit();
  }
}

$view = strip_tags($_GET['m']);
?>
<script src="assets/js/pages/staff.js"></script>
<script type="text/javascript">
$(document).ready(function () {
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
            <?php if (staff_access): ?>
            <?php switch($view):
			         case "settings": ?>
               <?php 
               if (!staff_siteSettings) {
                exit('<div class="alert alert-danger" role="alert"><strong>You do not have permission to access this page.</strong></div>');
               }
               ?>
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
                          echo 'On';
                        } elseif ($settings['dark_mode'] === "false") {
                          echo 'Off';
                        }?>
                        </option>
                        <option value="true">On</option>
                        <option value="false">Off</option>
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
                        <option value="true">Yes</option>
                        <option value="false">No</option>
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
                    <button class="btn btn-success btn-block" onClick="disableClick()" type="submit">Update</button>
                  </div>
                </form>
              </div>
            </div>
            <?php break; ?>

            <?php case "pending-users":?>
              <?php 
               if (!staff_approveUsers) {
                exit('<div class="alert alert-danger" role="alert"><strong>You do not have permission to access this page.</strong></div>');
               }
              ?>
              <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Pending Users</h4>
                        <div id="getPendingUsers"></div>
                    </div>
                </div>
              </div>
            <?php break; ?>

            <?php case "users":?>
              <?php 
               if (!staff_viewUsers) {
                exit('<div class="alert alert-danger" role="alert"><strong>You do not have permission to access this page.</strong></div>');
               }
              ?>
              <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">All Users</h4>
                        <table id="datatable" class="table table-borderless">
                          <thead>
                          <tr>
                              <th>User ID</th>
                              <th>Username</th>
                              <th>Email</th>
                              <th>Usergroup</th>
                              <th>Join Date</th>
                              <th>Actions</th>
                          </tr>
                          </thead>


                          <tbody>
                          <?php 
                          $sql             = "SELECT * FROM users";
                          $stmt            = $pdo->prepare($sql);
                          $stmt->execute();
                          $usersRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

                          foreach ($usersRow as $user) {
                            echo '
                            <tr>
                              <td>'. $user['user_id'] .'</td>
                              <td>'. $user['username'] .'</td>
                              <td>'. $user['email'] .'</td>
                              <td>'. $user['usergroup'] .'</td>
                              <td>'. $user['join_date'] .'</td>
                              <td><a href="staff.php?m=edit-user&user-id='. $user['user_id'] .'"><input type="button" class="btn btn-sm btn-success btn-block" value="Edit"></a></td>
                          </tr>
                            ';
                          }
                          ?>
                        </table>
                    </div>
                </div>
              </div>
            <?php break; ?>

            <?php case "edit-user": ?>
              <?php 
               if (!staff_editUsers) {
                exit('<div class="alert alert-danger" role="alert"><strong>You do not have permission to access this page.</strong></div>');
               }
              ?>
                <?php 
                  if (isset($_GET['user-id']) && strip_tags($_GET['user-id'])) {
                    $id   = $_GET['user-id'];
                    $sql  = "SELECT * FROM users WHERE user_id = :user_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id);
                    $stmt->execute();
                    $userDB = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($userDB === false) {
                        header('Location: ' . $url['staff'] . '?m=users');
                        exit();
                    } else {
                        $editing_user['user_id'] = $userDB['user_id'];
                        $_SESSION['editing_user_id'] = $editing_user['user_id'];
                        $editing_user['username'] = $userDB['username'];
                        $editing_user['email'] = $userDB['email'];
                        $editing_user['usergroup'] = $userDB['usergroup'];
                        $editing_user['join_date'] = $userDB['join_date'];
                        $editing_user['join_ip'] = $userDB['join_ip'];
                        $editing_user['steam_id'] = $userDB['steam_id'];
                        $editing_user['avatar'] = $userDB['avatar'];

                        if ($editing_user['usergroup'] === "Banned") {
                          $editing_user['isBanned'] = true;
                        } else {
                          $editing_user['isBanned'] = false;
                        }
                    }
                }
                ?>
                <div class="row">
                  <div class="col-12">
                    <?php if($editing_user['isBanned']): ?>
                      <div class="alert alert-danger" role="alert">
                        <strong>THIS USER IS BANNED. YOU CAN NOT EDIT THIS USER UNLESS THEY ARE UNBANNED.</strong>
                      </div>
                    <?php endif; ?>
                    </div>
                    <div class="col-6">
                        <div class="bg-picture card-box">
                        <h4 class="m-t-0 header-title">Edit User</h4>
                            <div class="profile-info-name">
                                <img src="<?php echo $editing_user['avatar']; ?>" class="img-thumbnail" alt="profile-image">
                                <div class="profile-info-detail">
                                    <form method="POST">
                                      <div class="form-group">
                                        <div class="col-12">
                                          <label for="username">Username</label>
                                          <input class="form-control" type="text" required="" id="username" name="username" value="<?php echo $editing_user['username']; ?>" placeholder="Username">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-12">
                                          <label for="email">Email</label>
                                          <input class="form-control" type="email" required="" id="email" name="email" value="<?php echo $editing_user['email']; ?>" placeholder="Email">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-12">
                                          <label for="usergroup">Usergroup</label>
                                          <select class="custom-select my-1 mr-sm-2" id="usergroup" name="usergroup">
                                            <option selected value="<?php echo $editing_user['usergroup']; ?>"><?php echo $editing_user['usergroup']; ?> (Current)</option>
                                            <option value="Unverified">Unverified</option>
                                            <option value="User">User</option>
                                            <option value="Moderator">Moderator</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Super Admin">Super Admin</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="form-group text-center">
                                        <div class="col-12">
                                            <?php if($editing_user['isBanned']): ?>
                                            <button class="btn btn-success btn-bordred btn-block waves-effect waves-light" disabled>Edit User</button>
                                            <?php else: ?>
                                            <button class="btn btn-success btn-bordred btn-block waves-effect waves-light" type="submit" name="editUserBtn">Edit User</button>
                                            <?php endif; ?>
                                        </div>
                                      </div>
                                    </form>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-picture card-box">
                        <h4 class="m-t-0 header-title">Ban Manager</h4>
                        <form method="POST">
                          <?php if($editing_user['isBanned']): ?>
                          <div class="form-group text-center">
                            <div class="col-12">
                                <button class="btn btn-danger btn-bordred btn-block waves-effect waves-light" type="submit" name="unbanUserBtn">Unban User</button>
                            </div>
                          </div>
                          <?php else: ?>
                          <div class="form-group">
                            <div class="col-12">
                              <label for="reason">Reason</label>
                              <input class="form-control" type="text" required="" id="reason" name="reason" placeholder="Reason">
                            </div>
                          </div>
                          <div class="form-group text-center">
                            <div class="col-12">
                                <button class="btn btn-danger btn-bordred btn-block waves-effect waves-light" type="submit" name="banUserBtn">Ban User</button>
                            </div>
                          </div>
                          <?php endif; ?>
                        </form>
                        <div class="clearfix"></div>
                      </div>
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
