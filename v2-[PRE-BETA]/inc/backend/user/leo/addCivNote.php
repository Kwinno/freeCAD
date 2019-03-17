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

$newNote['name']       	    = !empty($_POST['civilian']) ? trim($_POST['civilian']) : null;
$newNote['name']             = strip_tags($_POST['civilian']);
$newNote['note']       	    = !empty($_POST['note']) ? trim($_POST['note']) : null;
$newNote['note']             = strip_tags($_POST['note']);
$error = array();

$sql_getCharName             = "SELECT * FROM characters WHERE character_id = ?";
$stmt_getCharName            = $pdo->prepare($sql_getCharName);
$stmt_getCharName->execute([$newNote['name']]);
$realChar = $stmt_getCharName->fetch(PDO::FETCH_ASSOC);

$sql          = "INSERT INTO civ_notes (issued_on, signed_by, note, person, person_id) VALUES (
  :issued_on,
  :signed_by,
  :note,
  :person,
  :person_id
  )";
$stmt         = $pdo->prepare($sql);
$stmt->bindValue(':issued_on', $us_date . ' ' . $time);
$stmt->bindValue(':signed_by', $_SESSION['identity_name']);
$stmt->bindValue(':note', $newNote['note']);
$stmt->bindValue(':person', $realChar['first_name'] . ' ' . $realChar['last_name']);
$stmt->bindValue(':person_id', $newNote['name']);
$result = $stmt->execute();
if ($result) {
	// if ($settings['discord_alerts'] === 'true') {
	// discordAlert('**New Warrant**
	// '.$realChar['first_name'] . ' ' . $realChar['last_name'].' is now WANTED.
	// Warrant added by '.$_SESSION['identity_name'].'
	// Note: '.$newNote['note'].'
	// 	- **freeCAD System**');
	// }
  $error['msg'] = "";
	echo json_encode($error);
	exit();
}
