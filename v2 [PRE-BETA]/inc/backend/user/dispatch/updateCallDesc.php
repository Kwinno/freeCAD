<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (isset($_SESSION['on_duty']) && $_SESSION['on_duty'] === "Dispatch") {
  $newCallDesc = htmlspecialchars($_GET['desc']);

  $sql = "UPDATE 911calls SET call_description=? WHERE call_id=?";
  $stmt= $pdo->prepare($sql);
  $stmt->execute([$newCallDesc, $_SESSION['viewingCallID']]);
}
