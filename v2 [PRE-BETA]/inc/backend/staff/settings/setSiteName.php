<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

$error = array();

if (staff_access && staff_siteSettings) {
  $site_name = strip_tags($_POST['site_name']);

  $stmt              = $pdo->prepare("UPDATE `settings` SET `site_name`=:q");
  $stmt->bindValue(':q', $site_name);
  $result = $stmt->execute();

  $error['msg'] = "";
  echo json_encode($error);
  exit();
} else {
  $error['msg'] = "You don't have permission.";
  echo json_encode($error);
  exit();
}
