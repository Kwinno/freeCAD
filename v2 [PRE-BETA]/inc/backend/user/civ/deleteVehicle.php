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

//Rewrite needed