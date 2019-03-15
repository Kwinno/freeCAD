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

$error = array();

if (isset($_GET['id']) && strip_tags($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM weapons WHERE wpn_id = :wpn_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':wpn_id', $id);
    $stmt->execute();
    $weaponDB = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($weaponDB === false) {
        $error['msg'] = "Firearm Not Found";
        echo json_encode($error);
        exit();
    }
    elseif ($weaponDB['wpn_owner'] != $_SESSION['character_id']) {
        $error['msg'] = "No Permission";
        echo json_encode($error);
        exit();
    }
    else {
        $id = strip_tags($_GET['id']);
        $stmt2 = $pdo->prepare("DELETE FROM weapons WHERE wpn_id =:wpn_id");
        $stmt2->bindValue(':wpn_id', $id);
        $result2 = $stmt2->execute();
        $error['msg'] = "";
        echo json_encode($error);
        exit();
    }
}
else {
    $error['msg'] = "FATAL SYSTEM ERROR";
    echo json_encode($error);
    exit();
}
