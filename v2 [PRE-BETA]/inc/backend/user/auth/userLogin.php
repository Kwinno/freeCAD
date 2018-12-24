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
		if ($user['usergroup'] === "Unverified") {
			if ($settings['account_validation'] == "Yes") {
				$error['msg'] = "Your account is pending Validation from an Admin.";
				echo json_encode($error);
				exit();
			} else {
				$sql2               = "UPDATE `users` SET `usergroup`='User' WHERE `user_id`=:user_id";
				$stmt2              = $pdo->prepare($sql2);
				$stmt2->bindValue(':user_id', $user['user_id']);
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
		$error['msg'] = "Your password is invalid. Please try again.";
		echo json_encode($error);
		exit();
	}
}
