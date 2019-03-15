<?php
    session_name('hydrid');
    session_start();
    require '../../../connect.php';
    require '../../../config.php';
    require '../../../backend/user/auth/userIsLoggedIn.php';

    // Makes sure the person actually has a character set
    if (!isset($_SESSION['identity_name'])) {
      header('Location: ../../../../fire.php?v=nosession');
      exit();
    }

    // Page PHP
    $status = strip_tags($_GET['status']);
    if ($status === "Off-Duty") {
      $stmt              = $pdo->prepare("DELETE FROM on_duty WHERE `name`=:name");
      $stmt->bindValue(':name', $_SESSION['identity_name']);
      $result = $stmt->execute();
    } else {
      $stmt2              = $pdo->prepare("UPDATE `on_duty` SET `status`=:status WHERE `name`=:name");
      $stmt2->bindValue(':status', $status);
      $stmt2->bindValue(':name', $_SESSION['identity_name']);
      $result = $stmt2->execute();
      logAction('Started Shift (Fire / EMS) - '.$datetime.'', $_SESSION['identity_name']);
    }
