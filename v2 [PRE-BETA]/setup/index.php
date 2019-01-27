<?php
session_name('hydrid');
session_start();
require_once '../inc/connect.php';
require_once '../inc/config.php';
require_once '../inc/backend/user/auth/userIsLoggedIn.php';
$page['name'] = 'Complete Setup';

if (staff_siteSettings) {
  $sql_rootCheck             = "SELECT * FROM users WHERE root = ?";
  $stmt_rootCheck            = $pdo->prepare($sql_rootCheck);
  $stmt_rootCheck->execute(['true']);
  $user_rootCheck = $stmt_rootCheck->fetch(PDO::FETCH_ASSOC);

  if ($user_rootCheck === false) {
    if (isset($_POST['submit'])) {
      $pass                   = !empty($_POST['password']) ? trim($_POST['password']) : null;
      $pass      	            = strip_tags($pass);
      $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
      $sql          = "INSERT INTO users (username, email, password, usergroup, join_date, join_ip, root) VALUES (:username, :email, :password, :usergroup, :join_date, :join_ip, :root)";
      $stmt         = $pdo->prepare($sql);
      $stmt->bindValue(':username', 'root');
      $stmt->bindValue(':email', 'system');
      $stmt->bindValue(':password', $passwordHash);
      $stmt->bindValue(':usergroup', 'Super Admin');
      $stmt->bindValue(':join_date', 'system');
      $stmt->bindValue(':join_ip', 'system');
      $stmt->bindValue(':root', 'true');
      $result = $stmt->execute();

      header('Location: ../register.php?setup=complete');
      exit();
    }
  } else {
    header('Location: ../index.php');
  	exit();
  }
} else {
  header('Location: ../index.php');
  exit();
}

?>
<!DOCTYPE html>
<html>
  <head>
        <meta charset="utf-8" />
        <title><?php echo $page['name']. ' - HydridSystems' ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="Computer Aided Dispatch (CAD) System and Mobile Data Terminal (MDT) for GTA V Roleplaying." name="description" />
        <meta content="HydridSystems" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
        <script src="//code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="../assets/js/modernizr.min.js"></script>    </head>
    <body>
        <div class="wrapper-page">
              <div class="text-center">
                  <a href="#" class="logo"><span>HydridSystems</span></a>
              </div>
        	   <div class="m-t-40 card-box">
                <div class="text-center">
                    <h4 class="text-uppercase font-bold mb-0">Complete Setup <br /> Password for ROOT account</h4>
                </div>
                <div class="p-20">
                    <form autocomplete="off" class="form-horizontal m-t-20" method="POST">
                      <div class="alert alert-danger" role="alert">
                        Notice: Hydrid Staff do not recommend using this as your Regular Account. This account is created for Security reasons such as someone hacking your panel, etc. This account can NOT have its password reset.
                      </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="">Root Password</label>
                                <input autocomplete="off" class="form-control" type="password" required="" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12">
                                <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" name="submit" type="submit">Finish Setup</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- jQuery  -->
        <script src="../assets/js/popper.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="../assets/js/waves.js"></script>
        <script src="../assets/js/jquery.slimscroll.js"></script>
        <script src="../assets/plugins/jquery-knob/jquery.knob.js"></script>
	</body>
</html>
