<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (!isset($_SESSION['on_duty'])) {
	header('Location: ../../../../' . $url['leo'] . '?v=nosession');
	exit();
}

// Page PHP
$stmt = $pdo->prepare("SELECT * FROM servers WHERE id=:server_id");
$stmt->bindValue(':server_id', $_SESSION['server']);
$stmt->execute();
$pb_row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pb_row['priority'] === 1) {
    echo '1';
} else {
    echo '0';
}