<?php
session_name('hydrid');
session_start();
require 'inc/connect.php';
require 'inc/config.php';
require 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'Civilian';
require_once('inc/page-top.php');
?>

<body>
    <?php require_once('inc/top-nav.php'); ?>

    <!-- CONTENT START -->
    <div class="wrapper m-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                <h4 class="page-title"><?php echo $page['name']; ?></h4>
                </div>
            </div>
            <!-- CONTENT HERE -->
    </div>
    <!-- CONTENT END -->
    <?php require_once('inc/copyright.php'); ?>
    <?php require_once('inc/page-bottom.php'); ?>
