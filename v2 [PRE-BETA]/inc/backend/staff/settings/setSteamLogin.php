<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

if (staff_access && staff_siteSettings) {
  if (isset($_GET['q'])) {
    $q = strip_tags($_GET['q']);
    $error = array();

    $stmt              = $pdo->prepare("UPDATE `settings` SET `steam_required`=:q");
    $stmt->bindValue(':q', $q);
    $result = $stmt->execute();
    logAction('Changed Website Setting: Steam Login', $user['username']);

    if ($settings['discord_alerts'] === 'true') {
    discordAlert('**Panel Settings Changed**
	  Steam Login Settings have been updated by '. $user['username'] .'
      - **Hydrid CAD System**');
    }
  } else {
    $error['msg'] = "System Error";
    echo json_encode($error);
    exit();
  }
} else {
  logAction('Attempted To Change Website Setting: Steam Login', $user['username']);
  $error['msg'] = "No Permissions";
  echo json_encode($error);
  exit();
}
