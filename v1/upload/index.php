<?php

require('includes/connect.php');
include('includes/config.php');

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ' . $url['login'] . '');
    exit();
}

include('includes/isLoggedIn.php');

if (!$perms['panel_access']) {
    session_unset();
    header('Location: ' . $url['login'] . '?unverified=true');
    exit();
}

if (isset($_POST['createIdentityBtn'])) {
    createIdentity(escapestring($_POST['identifier']));
}

if (isset($_GET['identifier']) && strip_tags($_GET['identifier']) === 'created') {
    $message = '<div class="alert alert-success" role="alert">Identifier added!</div>';
} elseif (isset($_GET['identifier']) && strip_tags($_GET['identifier']) === 'taken') {
    $message = '<div class="alert alert-danger" role="alert">That identifier is already taken.</div>';
} elseif (isset($_GET['identifier']) && strip_tags($_GET['identifier']) === 'approval') {
    $message = '<div class="alert alert-info" role="alert">Your identifier has been created, but the community owner has chosen to validate all new identities. Please do not pester staff about getting approved.</div>';
} elseif (isset($_GET['np']) && strip_tags($_GET['np']) === 'dispatch') {
    $message = '<div class="alert alert-danger" role="alert" id="dismiss">You are not assigned to Dispatch.</div>';
} elseif (isset($_GET['np']) && strip_tags($_GET['np']) === 'fire') {
    $message = '<div class="alert alert-danger" role="alert" id="dismiss">You are not assigned to Fire/EMS.</div>';
} elseif (isset($_GET['logged']) && strip_tags($_GET['logged']) === 'in') {
    logAction('Logged In', $user_username);
} elseif (isset($_GET['np']) && strip_tags($_GET['np']) === 'leo') {
    $message = '<div class="alert alert-danger" role="alert" id="dismiss">You are not assigned to LEO.</div>';
}

?>
<html>
<?php
    $page_name = $LANG['home'];
    include('includes/header.php');
?>
<body>
	 <div class="container">
			<div class="main">
				 <img src="assets/imgs/los_santos.png" class="main-logo" draggable="false"/>
				 <div class="main-header">
                    <?php echo $LANG['hello']; ?>, 
                    <?php echo $user_username;
                        if (staff_access) {
                            echo ' <a href="staff.php"><i class="fas fa-fingerprint"></i></a>';
                        }
                    ?>
                    <a href="user-settings.php"><i class="fas fa-cog"></i></a> <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
				</div>
				
                <?php print($message);?>

                <a href="<?php print $url['civ_index']?>" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['civilian'] ?></a>
                <a data-toggle="modal" href="#soim" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['lawenforcement'] ?></a><br-leo>
                <a data-toggle="modal" href="#soimdispatch" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['dispatch'] ?></a><br-leo>
                <a data-toggle="modal" href="#soimfire" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['fireems'] ?></a>
				 <?php echo $ftter; ?>
			</div>
			<div class="modal fade" id="soimdispatch" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><?php echo $LANG['identityoptions']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <a data-toggle="modal" href="#selectIdentifierdispatch" data-dismiss="modal" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['selectid']; ?></a><br-leo>
                                <a data-toggle="modal" href="#createIdentityModal" data-dismiss="modal" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['createid']; ?></a><br-leo>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $LANG['close']; ?></button>
                            </div>
						</div>
				 </div>
			</div>
			<div class="modal fade" id="soim" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><?php echo $LANG['identityoptions']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <a data-toggle="modal" href="#selectIdentifier" data-dismiss="modal" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['selectid']; ?></a><br-leo>
                                <a data-toggle="modal" href="#createIdentityModal" data-dismiss="modal" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['createid']; ?></a><br-leo>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $LANG['close']; ?></button>
                            </div>
						</div>
				 </div>
			</div>
			<div class="modal fade" id="createIdentityModal" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							 <div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel"><?php echo $LANG['createid']; ?></h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
							 </div>
							 <div class="modal-body">
                                <form method="post" action="index.php">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="text" name="identifier" class="form-control" placeholder="EX: [C-01] John Doe" data-lpignore="true" required />
                                        </div>
                                    </div>
							        <div class="modal-footer">
                                        <div class="form-group">
                                            <input class="btn btn-primary" name="createIdentityBtn" type="submit" value="<?php echo $LANG['create']; ?>">
                                        </div>
                                    </div>
							     </form>
							 </div>
						</div>
				 </div>
			</div>
			<div class="modal fade" id="selectIdentifier" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><?php echo $LANG['selectid']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <select class="form-control" name="character_list" onchange="location = this.value;">
                                    <option selected="true" disabled="disabled"><?php echo $LANG['selectid']; ?></option>
                                    <?php
                                        foreach (dbquery('SELECT * FROM identities WHERE user="' . escapestring($user_id) . '" AND status="Active"') as $unit) {
                                            echo '<option value="' . $url['leo_index'] . '?setid=' . $unit['identity_id'] . '">' . $unit['identifier'] . '</option>';
                                        }   
                                    ?>
                                </select>
                            </div>
						</div>
				 </div>
			</div>
			<div class="modal fade" id="selectIdentifierdispatch" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><?php echo $LANG['selectid']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <select class="form-control" name="character_list" onchange="location = this.value;">
                                <option selected="true" disabled="disabled"><?php echo $LANG['selectid']; ?></option>
                                <?php
                                    foreach (dbquery('SELECT * FROM identities WHERE user="' . escapestring($user_id) . '" AND status="Active"') as $unit) {
                                        echo '<option value="' . $url['dispatch_index'] . '?setid=' . $unit['identity_id'] . '">' . $unit['identifier'] . '</option>';
                                    }
                                ?>
                                </select>
                            </div>
						</div>
				 </div>
			</div>
			<div class="modal fade" id="soimfire" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							 <div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel"><?php echo $LANG['identityoptions']; ?></h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
							 </div>
							 <div class="modal-body">
									 <a data-toggle="modal" href="#selectIdentifierfire" data-dismiss="modal" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['selectid']; ?></a><br-leo>
									 <a data-toggle="modal" href="#createIdentityModal" data-dismiss="modal" class="btn btn-primary btn-block btn-sb"><?php echo $LANG['createid']; ?></a><br-leo>
							 </div>
							 <div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $LANG['close']; ?></button>
							 </div>
						</div>
				 </div>
			</div>
			<div class="modal fade" id="selectIdentifierfire" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><?php echo $LANG['selectid']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <select class="form-control" name="character_list" onchange="location = this.value;">
                                    <option selected="true" disabled="disabled"><?php echo $LANG['selectid']; ?></option>
                                    <?php
                                        foreach (dbquery('SELECT * FROM identities WHERE user="' . escapestring($user_id) . '" AND status="Active"') as $unit) {
                                            echo '<option value="' . $url['fire_index'] . '?setid=' . $unit['identity_id'] . '">' . $unit['identifier'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
						</div>
				 </div>
			</div>
	 </div>
	 <?php include 'includes/js.php';?>
</body>
</html>
