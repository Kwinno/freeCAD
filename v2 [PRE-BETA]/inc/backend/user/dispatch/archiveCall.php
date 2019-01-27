<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (isset($_SESSION['on_duty']) && $_SESSION['on_duty'] === "Dispatch") {
  $sql = "UPDATE 911calls SET call_status=? WHERE call_id=?";
  $stmt= $pdo->prepare($sql);
  $stmt->execute(['Archived', $_SESSION['viewingCallID']]);
}
