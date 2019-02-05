<?php
session_name('hydrid');
session_start();
require_once 'inc/connect.php';

require_once 'inc/config.php';

require_once 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'Home';
?>
<?php include 'inc/page-top.php'; ?>

<body>
    <?php include 'inc/top-nav.php'; ?>
    <?php
        if (isset($_GET['notify']) && strip_tags($_GET['notify']) === 'steam-linked') {
            clientNotify('success', 'Your Steam Account Has Been Linked.');
        }
        $stats['users'] = null;
        $stats['staff'] = null;
        $stats['civ'] = null;
        $stats['ems'] = null;

        $stats['users'] = $pdo->query('select count(*) from users')->fetchColumn();
        $stats['staff'] = $pdo->query('select count(*) from users WHERE usergroup <> "1" AND usergroup <> "2" AND usergroup <> "3"')->fetchColumn();
        $stats['civ'] = $pdo->query('select count(*) from characters')->fetchColumn();
        $stats['ems'] = $pdo->query('select count(*) from identities')->fetchColumn();
        ?>
    <!-- CONTENT START -->
    <div class="wrapper m-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h4 class="page-title"><?php echo $page['name']; ?></h4>
                </div>
            </div>
            <div class="alert alert-warning" role="alert">
                <strong>Warning: </strong> This is a PRE-BETA release. We do not support public use of this version, as modules are missing and bugs may be present. Report any bugs on our Discord.
            </div>
            <div class="row">
                <div class="col col-xs-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Total Users</h4>
                        <h2 class="p-t-10 mb-0"><?php echo $stats['users']; ?></h2>
                    </div>
                </div>
                <div class="col col-xs-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Total Staff</h4>
                        <h2 class="p-t-10 mb-0"><?php echo $stats['staff']; ?></h2>
                    </div>
                </div>
                <div class="col col-xs-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Total Civilians</h4>
                        <h2 class="p-t-10 mb-0"><?php echo $stats['civ']; ?></h2>
                    </div>
                </div>
                <div class="col col-xs-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Total Emergency Services</h4>
                        <h2 class="p-t-10 mb-0"><?php echo $stats['ems']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Welcome To The New HydridSystems CAD/MDT</h4>
                        <p>In this version, the development team has taken great view from the issues in our original release, along with feedback from the community! We welcome any feedback to given on our <a href="https://discord.gg/NeRrWZC">Discord</a>. You can Navigate through the panel using the Navigation bar at the top! <i>This is a BETA version. Please report any bugs to Community Staff, or Hydrid Staff.</i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT END -->
    <?php include 'inc/copyright.php'; ?>
    <?php include 'inc/page-bottom.php'; ?>
