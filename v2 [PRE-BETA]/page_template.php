<?php
session_name('hydrid');
session_start();
include 'inc/connect.php';
include 'inc/config.php';
include 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'Civilian';
?>
<?php include 'inc/page-top.php'; ?>
<body>
    <?php include 'inc/top-nav.php'; ?>

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
    <?php include 'inc/copyright.php'; ?>
    <?php include 'inc/page-bottom.php'; ?>
