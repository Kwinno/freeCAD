<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

if (staff_access && staff_siteSettings) {
    $id = strip_tags($_GET['id']);
    $stmt              = $pdo->prepare("UPDATE `users` SET `usergroup`='User' WHERE `user_id`=:id");
    $stmt->bindValue(':id', $id);
    $result = $stmt->execute();
} else {
  $error['msg'] = "No Permissions";
  echo json_encode($error);
  exit();
}
