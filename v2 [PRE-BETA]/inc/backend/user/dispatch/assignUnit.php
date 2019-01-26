<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (isset($_SESSION['on_duty']) && $_SESSION['on_duty'] === "Dispatch") {
  $unit = strip_tags($_GET['unit']);

  $sql = "INSERT INTO assigned_callunits (call_id, unit_id) VALUES (?,?)";
  $stmt= $pdo->prepare($sql);
  $stmt->execute([$_SESSION['viewingCallID'], $unit]);
}
