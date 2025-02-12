<?php
session_name('hydrid');
session_start();
require_once 'inc/connect.php';

require_once 'inc/config.php';

require_once 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'Dispatch Module';

// Page PHP
$view = strip_tags($_GET['v']);

if (isset($_GET['v']) && strip_tags($_GET['v']) === 'setsession') {
	if (isset($_GET['id']) && strip_tags($_GET['id'])) {
        $id   = $_GET['id'];
        $sql  = "SELECT * FROM identities WHERE identity_id = :identity_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':identity_id', $id);
        $stmt->execute();
        $identityDB = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($identityDB === false) {
					header('Location: '.$url['dispatch'].'?v=nosession&error=identity-not-found');
					exit();
        } else {
            $identity_id             = $identityDB['identity_id'];
            $_SESSION['identity_id'] = $identity_id;

            $identity_name             = $identityDB['name'];
            $_SESSION['identity_name'] = $identity_name;

            $identity_department             = $identityDB['department'];
            $_SESSION['identity_department'] = $identity_department;

            $identity_division             = $identityDB['division'];
            $_SESSION['identity_division'] = $identity_division;

            $identity_supervisor             = $identityDB['supervisor'];
            $_SESSION['identity_supervisor'] = $identity_supervisor;

            $identity_owner             = $identityDB['user'];
            $_SESSION['identity_owner'] = $identity_owner;

            $_SESSION['notepad'] = "";

            $_SESSION['on_duty'] = "Dispatch";

            if ($identity_owner !== $user_id) {
							header('Location: '.$url['dispatch'].'?v=nosession&error=identity-owner');
							exit();
						}

						$stmt2              = $pdo->prepare("DELETE FROM `on_duty` WHERE `name`=:identity_name");
						$stmt2->bindValue(':identity_name', $identity_name);
						$result2 = $stmt2->execute();
						$stmt3              = $pdo->prepare("INSERT INTO on_duty (name, department, status) VALUES (:name, :department, 'On-Duty')");
						$stmt3->bindValue(':name', $identity_name);
						$stmt3->bindValue(':department', $identity_department);
						$result3 = $stmt3->execute();

						header('Location: '.$url['dispatch'].'?v=main');
						exit();
			        }
			    }
}
?>
<?php include 'inc/page-top.php'; ?>
<script src="assets/js/pages/dispatch.js?v=<?php echo $assets_ver ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#createIdentity').ajaxForm(function(error) {
            error = JSON.parse(error);
            if (error['msg'] === "") {
                toastr.success('Identity Created! You can now select it.', 'System:', {
                    timeOut: 10000
                })
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                })
            }
        });
    });
</script>

<body>
    <?php include 'inc/top-nav.php';?>
    <!-- CONTENT START -->
    <div class="wrapper m-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h4 class="page-title">
                        <?php echo $page['name']; ?> <label id="displayAOP"></label>
                    </h4>
                </div>
            </div>
            <!-- CONTENT HERE -->
            <?php switch($view):
			         case "nosession": ?>
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <select class="form-control" id="listIdentitys" onchange="location = this.value;">
                            <option selected="true" disabled="disabled">Loading Identities...</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Identity Creator</h4>
                        <form class="form-horizontal m-t-20" id="createIdentity" action="inc/backend/user/dispatch/createIdentity.php" method="POST">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="name" placeholder="[1A-01] John Doe">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input class="btn btn-success btn-block" type="submit" value="Create Character">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php break; ?>
            <?php case "main": ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    var elem = document.querySelector('.allcallCheckbox'); // referred checkbox class is here
                    var init = new Switchery(elem, {
                        size: 'small'
                    }); // put option after elem attribute

                    $('textarea').keypress(function(event) {
                        if (event.which == 13) {
                            event.preventDefault();
                            this.value = this.value + "\n";
                        }
                    });

                    var signal100 = false;

                    function checkTime(i) {
                        if (i < 10) {
                            i = "0" + i;
                        }
                        return i;
                    }

                    $('#new911call').ajaxForm(function(error) {
                        console.log(error);
                        var error = JSON.parse(error);
                        if (error['msg'] === "") {
                            $("#new911call")[0].reset();
                            $('#new911callModal').modal('hide');
                            toastr.success('Call Added', 'System:', {
                                timeOut: 10000
                            });
                        } else if (error['msg'] === "allCall") {
                            $("#new911call")[0].reset();
                            $('#new911callModal').modal('hide');
                            changeSignal();
                            toastr.success('Call Added', 'System:', {
                                timeOut: 10000
                            });
                        } else {
                            toastr.error(error['msg'], 'System:', {
                                timeOut: 10000
                            });
                        }
                    });

                    $('#newBolo').ajaxForm(function(error) {
                        console.log(error);
                        var error = JSON.parse(error);
                        if (error['msg'] === "") {
                            $("#newBolo")[0].reset();
                            $('#newBoloModel').modal('hide');
                            toastr.success('BOLO Added', 'System:', {
                                timeOut: 10000
                            });
                        } else {
                            toastr.error(error['msg'], 'System:', {
                                timeOut: 10000
                            });
                        }
                    });

                    function startTime() {
                        var today = new Date();
                        var h = today.getHours();
                        var m = today.getMinutes();
                        var s = today.getSeconds();
                        // add a zero in front of numbers<10
                        m = checkTime(m);
                        s = checkTime(s);
                        document.getElementById('getTime').innerHTML = h + ":" + m + ":" + s;
                        t = setTimeout(function() {
                            startTime()
                        }, 500);
                    }

                    startTime();

                    $('#changeAOP').ajaxForm(function(error) {
                        console.log(error);
                        error = JSON.parse(error);
                        if (error['msg'] === "") {
                            $("#changeAOP")[0].reset();
                            toastr.success('New AOP Set - Please allow a minute for changes to display.', 'System:', {
                                timeOut: 10000
                            })
                        } else {
                            toastr.error(error['msg'], 'System:', {
                                timeOut: 10000
                            })
                        }
                    });

                    function getLeoInfo() {
                        (function loadStatus() {
                            $.ajax({
                                url: 'inc/backend/user/leo/getStatus.php',
                                success: function(data) {
                                    $('#getDutyStatus').html(data);
                                },
                                complete: function() {
                                    // Schedule the next request when the current one's complete
                                    setTimeout(loadStatus, 1000);
                                }
                            });
                        })();
                        (function loadAOP() {
                            $.ajax({
                                url: 'inc/backend/user/leo/getAOP.php',
                                success: function(data) {
                                    $('#displayAOP').html(data);
                                },
                                complete: function() {
                                    // Schedule the next request when the current one's complete
                                    setTimeout(loadAOP, 60000);
                                }
                            });
                        })();
                        (function loadSig100Status() {
                            $.ajax({
                                url: 'inc/backend/user/leo/checkSignal100.php',
                                success: function(data) {
                                    if (data === "1") {
                                        toastr.options = {
                                            "preventDuplicates": true,
                                            "preventOpenDuplicates": true
                                        };
                                        toastr.error('SIGNAL 100 IS IN EFFECT.', 'System:', {
                                            timeOut: 10000
                                        })
                                        $('#signal100Status').html("<font color='red'><b> - SIGNAL 100 IS IN EFFECT</b></font>");

                                        if (!signal100) {
                                            var audio = new Audio('assets/sounds/signal100.mp3');
                                            audio.play();
                                            setTimeout(() => {
																								var msg = new SpeechSynthesisUtterance('ALL UNITS HOLD TRAFFIC - SIGNAL 100 ACTIVATED - STAND BY FOR DETAILS.');
                                                var voices = window.speechSynthesis.getVoices();
                                                window.speechSynthesis.speak(msg);
                                            }, 3000);
                                        }
                                        signal100 = true;
                                    } else {
                                        $('#signal100Status').html("");
                                        signal100 = false;
                                    }
                                },
                                complete: function() {
                                    // Schedule the next request when the current one's complete
                                    setTimeout(loadSig100Status, 500);
                                }
                            });
                        })();
                    }
                    getLeoInfo();
                });
            </script>
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <div class="dropdown pull-right">
                            <b>
                                <div id="getTime">Loading...</div>
                            </b>
                        </div>
                        <h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small>
                                <font color="white">[Supervisor]</font>
                            </small><?php endif; ?> <label id="signal100Status"></label></h4>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openNameSearch">Name Database</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openVehicleSearch">Vehicle Database</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openFirearmSearch">Weapon Database</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#notepadModal">Notepad</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#new911callModal">Create Call</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#newBoloModel">Create BOLO</button>
                        <button class="btn btn-danger btn-sm" onclick="changeSignal();">Signal 100</button>
                        <?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                        <a href="dispatch.php?v=supervisor"><button class="btn btn-darkred btn-sm">Supervisor Panel</button></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">911 Calls</h4>
                        <div id="get911Calls"></div>
                    </div>
										<div class="card-box">
												<h4 class="header-title mt-0 m-b-30">Active Units</h4>
												<div id="getActiveUnits"></div>
										</div>
                </div>
								<div class="col-4">
									<div class="card-box">
											<h4 class="header-title mt-0 m-b-30">BOLOs</h4>
											<div id="getBolos"></div>
									</div>
									<?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
										<div class="card-box">
												<h4 class="header-title mt-0 m-b-30">AOP Editor</h4>
												<form method="post" action="inc/backend/user/leo/setAOP.php" id="changeAOP">
														<div class="form-group">
																<div class="col">
																		<input class="form-control" type="text" required="" name="newAOP" placeholder="New AOP">
																</div>
														</div>
														<div class="form-group">
																<div class="col">
																		<button class="btn btn-warning btn-bordred btn-block waves-effect waves-light" onClick="disableClick()" type="submit">Change AOP</button>
																</div>
														</div>
												</form>
										</div>
									<?php endif; ?>
								</div>
            </div>

            <!-- MODALS -->
            <!-- New Bolo Modal -->
            <div class="modal fade" id="newBoloModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New BOLO</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="newBolo" action="inc/backend/user/dispatch/newBolo.php" method="post">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Description (Please include as much detail as possible)" id="description" name="description" style="white-space: pre-line;" wrap="hard" rows="6" required></textarea>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Create New BOLO">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- New Call Modal -->
            <div class="modal fade" id="new911callModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New 911 Call</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="new911call" action="inc/backend/user/dispatch/new911call.php" method="post">
                                <div class="form-group">
                                    <input type="text" name="call_description" class="form-control" placeholder="Call Desc" data-lpignore="true" required />
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" id="street_ac2" name="call_location" class="form-control" placeholder="Street" data-lpignore="true" required />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="call_postal" class="form-control" pattern="\d*" placeholder="Postal" data-lpignore="true" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label>All Call</label>
                                        <input type="checkbox" class="allcallCheckbox" name="allCall" value="1" />
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Create New Call">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- Call Info Modal -->
            <div class="modal fade" id="callInfoModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-full" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Call Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="callModalBody" class="modal-body">

                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- BOLO Info Modal -->
            <div class="modal fade" id="boloInfoModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Bolo Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="boloModalBody" class="modal-body">

                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- search name modal -->
            <div class="modal fade" id="openNameSearch" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Name Database</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <select class="select2" name="nameSearch" id="getAllCharacters" onchange="showName(this.value)">
                                    <option selected="true" disabled="disabled">Loading Characters...</option>
                                </select>
                            </form>
                            <br>
                            <div id="showPersonInfo"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- search vehicle modal -->
            <div class="modal fade" id="openVehicleSearch" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Vehicle Database</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <select class="select2" name="vehicleSearch" id="getAllVehicles" onchange="showVehicle(this.value)">
                                    <option selected="true" disabled="disabled">Loading Vehicles...</option>
                                </select>
                            </form>
                            <br>
                            <div id="showVehicleInfo"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- search firearm modal -->
            <div class="modal fade" id="openFirearmSearch" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Firearms Database</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <select class="select2" name="firearmSearch" id="getAllFirearms" onchange="showFirearm(this.value)">
                                    <option selected="true" disabled="disabled">Loading Firearms...</option>
                                </select>
                            </form>
                            <br>
                            <div id="showFirearmInfo"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- notepad modal -->
            <div class="modal fade" id="notepadModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Notepad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="leo-index.php">
                                <div class="form-group">
                                    <textarea class="form-control" name="textarea" oninput="updateNotepad(this.value)" rows="12" cols="104"><?php echo $_SESSION['notepad']; ?></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <?php break; ?>

						<?php case "supervisor": ?>
							<?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
								<?php if(isset($_GET['a']) && strip_tags($_GET['a']) === 'edit-id'): ?>
		            <?php
										$id   = $_GET['id'];
										$sql  = "SELECT * FROM identities WHERE identity_id = :identity_id AND department='Dispatch'";
										$stmt = $pdo->prepare($sql);
										$stmt->bindValue(':identity_id', $id);
										$stmt->execute();
										$idDB = $stmt->fetch(PDO::FETCH_ASSOC);
										if ($idDB === false) {
											 echo '<script> location.replace("' . $url['dispatch']. '?v=supervisor&error=id-not-found"); </script>';
											 exit();
										} else {
											$editing_id['id']	= $idDB['identity_id'];
											$_SESSION['editing_identity_id']	= $editing_id['id'];

											$editing_id['name']	= $idDB['name'];
											$editing_id['division']	= $idDB['division'];
											$editing_id['supervisor']	= $idDB['supervisor'];
											$editing_id['user']	= $idDB['user_name'];
											$editing_id['status']	= $idDB['status'];
										}

										if (isset($_POST['suspendIdBtn'])) {
											$sql = "UPDATE identities SET status=? WHERE identity_id=?";
											$stmt = $pdo->prepare($sql);
											$stmt->execute(['Suspended', $_SESSION['editing_identity_id']]);
											echo '<script> location.replace("' . $url['dispatch']. '?v=supervisor&id=suspended"); </script>';
											exit();
										}
										if (isset($_POST['unsuspendIdBtn'])) {
											$sql = "UPDATE identities SET status=? WHERE identity_id=?";
											$stmt = $pdo->prepare($sql);
											$stmt->execute(['Active', $_SESSION['editing_identity_id']]);
											echo '<script> location.replace("' . $url['dispatch']. '?v=supervisor&id=unsuspended"); </script>';
											exit();
										}
										if (isset($_POST['editIdBtn'])) {
											$updateSupervisor    = !empty($_POST['supervisor']) ? trim($_POST['supervisor']) : null;
		  								$updateSupervisor    = strip_tags($updateSupervisor);

											$sql = "UPDATE identities SET supervisor=? WHERE identity_id=?";
											$stmt = $pdo->prepare($sql);
											$stmt->execute([$updateSupervisor, $_SESSION['editing_identity_id']]);
											echo '<script> location.replace("' . $url['dispatch']. '?v=supervisor&id=edited"); </script>';
											exit();
										}
										?>
		            <div class="row">
		                <div class="col-12">
		                    <?php if($editing_id['status'] === "Suspended"): ?>
		                    <div class="alert alert-danger" role="alert">
		                        <strong>This identity is Suspended.</strong>
		                    </div>
		                    <?php endif; ?>
		                    <div class="card-box">
		                        <h4 class="header-title mt-0 m-b-30">Identity Editor (<?php echo $editing_id['name']; ?>)</h4>
		                        <form method="POST">
		                            <div class="form-group">
		                                <div class="col-12">
		                                    <label for="supervisor">Supervisor</label>
		                                    <select class="custom-select my-1 mr-sm-2" id="supervisor" name="supervisor">
		                                        <option selected value="<?php echo $editing_id['supervisor']; ?>"><?php echo $editing_id['supervisor']; ?> (Current)</option>
		                                        <option value="No">No</option>
		                                        <option value="Yes">Yes</option>
		                                    </select>
		                                </div>
		                            </div>

		                            <div class="form-group text-center">
		                                <div class="row">
		                                    <div class="col-6">
		                                        <button class="btn btn-success btn-bordred btn-block waves-effect waves-light" type="submit" name="editIdBtn">Edit</button>
		                                    </div>
		                                    <div class="col-6">
		                                        <?php if($editing_id['status'] === "Suspended"): ?>
		                                        <button class="btn btn-danger btn-bordred btn-block waves-effect waves-light" type="submit" name="unsuspendIdBtn">Unsuspend</button>
		                                        <?php else: ?>
		                                        <button class="btn btn-danger btn-bordred btn-block waves-effect waves-light" type="submit" name="suspendIdBtn">Suspend</button>
		                                        <?php endif; ?>
		                                    </div>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
							 <?php elseif(isset($_GET['a']) && strip_tags($_GET['a']) === 'view-call'): ?>
		            <?php
										$id   = $_GET['id'];
										$sql  = "SELECT * FROM 911calls WHERE call_id = :id AND call_status='Archived'";
										$stmt = $pdo->prepare($sql);
										$stmt->bindValue(':id', $id);
										$stmt->execute();
										$callDB = $stmt->fetch(PDO::FETCH_ASSOC);
										if ($callDB === false) {
											 echo '<script> location.replace("' . $url['dispatch']. '?v=supervisor&error=call-not-found"); </script>';
											 exit();
										} else {
											$viewing_call['id']	= $callDB['call_id'];
											$_SESSION['viewingArchivedCallID']	= $viewing_call['id'];

											$viewing_call['call_desc']	= $callDB['call_description'];
											$viewing_call['call_location']	= $callDB['call_location'];
											$viewing_call['call_postal']	= $callDB['call_postal'];
											$viewing_call['call_status']	= $callDB['call_status'];
											$viewing_call['call_timestamp']	= $callDB['call_timestamp'];
										}
										?>
		            <div class="row">
		                <div class="col-12">
		                    <div class="card-box">
		                        <h4 class="header-title mt-0 m-b-30">Viewing Archived Call #(<?php echo $_SESSION['viewingArchivedCallID']; ?>)</h4>
		                        <div class="row">
															<div class="col">
																	<div class="form-group">
																		<textarea class="form-control" readonly="true" rows="8"><?php echo $viewing_call['call_desc'];?></textarea>
																	</div>
															</div>
														</div>
														<div class="row">
															<div class="col">
																	<div class="form-group">
																			<label>Location (Street / Postal)</label>
																			<input class="form-control" readonly="" value="<?php echo $viewing_call['call_location'];?> / <?php echo $viewing_call['call_postal'];?>"/>
																	</div>
															</div>
															<div class="col">
																	<div class="form-group">
																			<label>Timestamp</label>
																			<input class="form-control" readonly="" value="<?php echo $viewing_call['call_timestamp'];?>"/>
																	</div>
															</div>
														</div>
		                    </div>
		                </div>
		            </div>
								<div class="row">
									<div class="col-12">
										<div class="card-box">
												<h4 class="header-title mt-0 m-b-30">Call Log</h4>
												<table id="datatable" class="table table-borderless">
													<thead>
	                          <tr>
	                              <th>Dispatcher</th>
	                              <th>Timestamp</th>
	                              <th width="60%">Action</th>
	                          </tr>
	                        </thead>
													<tbody>
														<?php
														$sql             = "SELECT * FROM 911call_log WHERE call_id= ?";
														$stmt            = $pdo->prepare($sql);
														$stmt->execute([$_SESSION['viewingArchivedCallID']]);
														$callLogRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

														foreach ($callLogRow as $log) {
														?>
														<tr>
															<td><?php echo $log['dispatcher']?></td>
															<td><?php echo $log['timestamp']?></td>
															<td><?php echo $log['action']?></td>
														</tr>
														<?php } ?>
													</tbody>
												</table>
										</div>
									</div>
								</div>
								<?php else: ?>
								<div class="row">
									<div class="col-12">
										<div class="card-box">
                        <h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small>
                                <font color="white"><i>Supervisor</i></font>
                            </small><?php endif; ?></h4>
                        <?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                        <a href="dispatch.php?v=main"><button class="btn btn-info btn-sm">Back To Patrol Panel</button></a>
                        <?php endif; ?>
                    </div>
									</div>
								</div>
								<div class="row">
									<div class="col-7">
										<div class="card-box">
                    	<h4 class="header-title mt-0 m-b-30">All Dispatch Identities</h4>
											<table id="datatable" class="table table-borderless">
												<thead>
                          <tr>
                              <th>Name</th>
                              <th>Supervisor</th>
                              <th>User</th>
                              <th>Status</th>
                              <th>Actions</th>
                          </tr>
                        </thead>
												<tbody>
													<?php
													$sql             = "SELECT * FROM identities WHERE department='Dispatch'";
													$stmt            = $pdo->prepare($sql);
													$stmt->execute();
													$idsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

													foreach ($idsRow as $identity) {
													?>
													<tr>
														<td><?php echo $identity['name']?></td>
														<td><?php echo $identity['supervisor']?></td>
														<td><?php echo $identity['user_name']?></td>
														<td><?php echo $identity['status']?></td>
														<td><a href="dispatch.php?v=supervisor&a=edit-id&id=<?php echo $identity['identity_id']?>"><input type="button" class="btn btn-sm btn-success btn-block" value="Edit"></a></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-5">
                    <div class="card-box">
                      <h4 class="header-title mt-0 m-b-30">Pending Identities</h4>
                      <div id="getPendingIds"></div>
                    </div>
	                </div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="card-box">
                    	<h4 class="header-title mt-0 m-b-30">Archived Calls (BETA)</h4>
											<table id="datatable2" class="table table-borderless">
												<thead>
                          <tr>
                              <th>Call ID</th>
                              <th>Call Description</th>
                              <th>Actions</th>
                          </tr>
                        </thead>
												<tbody>
													<?php
													$sql             = "SELECT * FROM 911calls WHERE call_status='Archived'";
													$stmt            = $pdo->prepare($sql);
													$stmt->execute();
													$callsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

													foreach ($callsRow as $call) {
													?>
													<tr>
														<td><?php echo $call['call_id']?></td>
														<td><?php echo $call['call_description']?></td>
														<td><a href="dispatch.php?v=supervisor&a=view-call&id=<?php echo $call['call_id']?>"><input type="button" class="btn btn-sm btn-success btn-block" value="View"></a></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<?php else: ?>
								<div class="alert alert-danger" role="alert">
		                You are not a supervisor.
		            </div>
							<?php endif; ?>
						<?php break; ?>

            <?php endswitch; ?>
        </div>
    </div>
    <!-- CONTENT END -->
    <?php include 'inc/copyright.php'; ?>
    <?php include 'inc/page-bottom.php'; ?>
		<!-- this community uses freeCAD. freeCAD is a free and open-source CAD/MDT system. Find our discord here: https://discord.gg/NeRrWZC -->
