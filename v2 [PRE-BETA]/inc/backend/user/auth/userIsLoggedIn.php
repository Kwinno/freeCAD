<?php

// Check if the user is actually logged in

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {

	// The user isn't logged in, so we will redirect them

	header('Location: ' . $url['login'] . '?error=access');
	exit();
}
else {

	// The user is logged in, so we will grab the data required
	// Get User Data

	$user_id = $_SESSION['user_id'];
	$sql             = "SELECT * FROM users WHERE user_id = :user_id";
	$stmt            = $pdo->prepare($sql);
	$stmt->bindValue(':user_id', $user_id);
	$stmt->execute();
	$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($userRow === false) {
		header('Location: logout.php');
		exit();
	}

	// Define variables

	$user['username'] = $userRow['username'];
	$user['email'] = $userRow['email'];
	$user['usergroup'] = $userRow['usergroup'];
	$user['ip'] = $userRow['join_ip'];
	$user['join_date'] = $userRow['join_date'];
	$user['avatar'] = $userRow['avatar'];

	if ($settings['steam_required'] === "true") {
		$user['steam_id'] = $userRow['steam_id'];
	}

	// Default Values

	$perms['banned'] = false;
	$perms['panel_access'] = false;
	$perms['staff_approveUsers'] = false;
	$perms['staff_access'] = false;
	$perms['staff_viewUsers'] = false;
	$perms['staff_editUsers'] = false;
	$perms['staff_siteSettings'] = false;

	// Define User Permissions

	switch ($user['usergroup']) {
	case "Banned":
		$perms['banned'] = true;
		break;

	case "User":
		$perms['panel_access'] = true;
		break;

	case "Moderator":
		$perms['panel_access'] = true;
		$perms['staff_approveUsers'] = true;
		$perms['staff_access'] = true;
		$perms['staff_viewUsers'] = true;
		break;

	case "Admin":
		$perms['panel_access'] = true;
		$perms['staff_approveUsers'] = true;
		$perms['staff_access'] = true;
		$perms['staff_viewUsers'] = true;
		$perms['staff_editUsers'] = true;
		break;

	case "Super Admin":
		$perms['panel_access'] = true;
		$perms['staff_approveUsers'] = true;
		$perms['staff_access'] = true;
		$perms['staff_viewUsers'] = true;
		$perms['staff_editUsers'] = true;
		$perms['staff_siteSettings'] = true;
		break;
	}

	// Define Values

	define("banned", $perms['banned']);
	define("panel_access", $perms['panel_access']);
	define("staff_approveUsers", $perms['staff_approveUsers']);
	define("staff_access", $perms['staff_access']);
	define("staff_viewUsers", $perms['staff_viewUsers']);
	define("staff_editUsers", $perms['staff_editUsers']);
	define("staff_siteSettings", $perms['staff_siteSettings']);

	// Check If Banned

	if (banned) {
		session_destroy();
		header('Location: ' . $url['login'] . '?error=banned');
		exit();
	}

	if (!strpos($_SERVER['REQUEST_URI'], "steam-required") !== false){
		if ($settings['steam_required'] === "true") {
			if (empty($user['steam_id'])) {
				header('Location: ' . $url['steam-required'] . '');
				exit();
			}
		}
	}
}
