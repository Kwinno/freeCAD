<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

$username       	    = !empty($_POST['username']) ? trim($_POST['username']) : null;
$passwordAttempt        = !empty($_POST['password']) ? trim($_POST['password']) : null;

$username      			= strip_tags($username);
$passwordAttempt      	= strip_tags($passwordAttempt);

$error = array();

$sql             = "SELECT * FROM users WHERE username = :username";
$stmt            = $pdo->prepare($sql);
$stmt->bindValue(':username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user === false) {
	$error['msg'] = "That account couldn't be found in our Database.";
	echo json_encode($error);
	exit();
} else {
	$validPassword = password_verify($passwordAttempt, $user['password']);
	if ($validPassword) {
		if ($user['usergroup'] === "") {
			$error['msg'] = "An error occured while creating your account. Please contact Staff.";
			echo json_encode($error);
			exit();
		} elseif ($user['usergroup'] === "Unverified") {
			if ($settings['account_validation'] == "Yes") {
				$error['msg'] = "Your account is pending Validation from an Admin.";
				echo json_encode($error);
				exit();
			} else {
				$default_usergroup = "User";
				$sql2               = "UPDATE `users` SET `usergroup`=:usergroup WHERE `user_id`=:user_id";
				$stmt2              = $pdo->prepare($sql2);
				$stmt2->bindValue(':user_id', $user['user_id']);
				$stmt2->bindValue(':usergroup', $default_usergroup);
				$updateUserGroup = $stmt2->execute();
			}
		}

		$_SESSION['user_id']   = $user['user_id'];
		$_SESSION['logged_in'] = time();

		//Successful login
		$error['msg'] = "";
		echo json_encode($error);
		exit();
	} else {
		// Just a place holder for now until I actually make it proper.
		// $sql_la               = "UPDATE `users` SET `failed_logins`= `failed_logins` + 1 WHERE `user_id`=:user_id";
		// $stmt_la              = $pdo->prepare($sql_la);
		// $stmt_la->bindValue(':user_id', $user['user_id']);
		// $updateFailedLogins = $stmt_la->execute();
		$error['msg'] = "Your password is invalid. Please try again.";
		echo json_encode($error);
		exit();
	}
}
