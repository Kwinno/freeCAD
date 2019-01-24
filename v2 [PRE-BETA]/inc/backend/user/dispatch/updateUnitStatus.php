<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (isset($_SESSION['on_duty']) && $_SESSION['on_duty'] === "Dispatch") {
  $unit = strip_tags($_GET['unit']);
  $status = strip_tags($_GET['status']);

  $sql = "UPDATE on_duty SET status=? WHERE id=?";
  $stmt= $pdo->prepare($sql);
  $stmt->execute([$status, $unit]);

}
