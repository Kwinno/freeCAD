<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (!isset($_SESSION['character_full_name'])) {
	header('Location: ../../../../' . $url['civilian'] . '?v=nosession');
	exit();
}

// Page PHP
$sql             = "SELECT * FROM weapons WHERE wpn_owner=:character_id";
$stmt            = $pdo->prepare($sql);
$stmt->bindValue(':character_id', $_SESSION['character_id']);
$stmt->execute();
$firearmDBcall = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($firearmDBcall)) {
	echo '
      You have no Weapons.
      ';
}
else {
	echo '
      <table class="table table-borderless">
          <thead>
            <tr>
                <th>Type</th>
                <th>Serial</th>
                <th>Status</th>
            </tr>
          </thead>
            <tbody>
              ';
	foreach($firearmDBcall as $firearm) {
		echo '
                      <tr>
                          <td>' . $firearm['wpn_type'] . '</td>
                          <td>' . $firearm['wpn_serial'] . '</td>
                          <td>' . $firearm['wpn_rpstatus'] . '</td>
                      </tr>
                      ';
	}

	echo '
            </tbody>
      </table>';
}
