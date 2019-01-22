<?php
session_name('hydrid');
session_start();
require 'inc/connect.php';

require 'inc/config.php';

require 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'Law Enforcement Module';
require_once('inc/page-top.php');

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
            header('Location: ' . $url['leo'] . '?v=nosession&error=identity-not-found');
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
            
            $_SESSION['on_duty'] = "LEO";
            
            if ($identity_owner !== $user_id) {
					echo '<script> location.replace("'.$url['leo'].'?v=nosession&error=identity-owner"); </script>';
					exit();
				}
				
				$stmt2              = $pdo->prepare("DELETE FROM `on_duty` WHERE `name`=:identity_name");
				$stmt2->bindValue(':identity_name', $identity_name);
				$result2 = $stmt2->execute();
				$stmt3              = $pdo->prepare("INSERT INTO on_duty (name, department, division, status) VALUES (:name, :department, :division, '10-41')");
				$stmt3->bindValue(':name', $identity_name);
				$stmt3->bindValue(':department', $identity_department);
				$stmt3->bindValue(':division', $identity_division);
				$result3 = $stmt3->execute();
            
            echo '<script> location.replace("'.$url['leo'].'?v=main"); </script>';
            exit();
        }
    }
}
?>
<script src="assets/js/pages/leo.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#createIdentity').ajaxForm(function (error) {
            error = JSON.parse(error);
            if (error['msg'] === "") {
                toastr.success('Identity Created! You can now select it.', 'System:', {timeOut: 10000})
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                })
            }
        });
			$('#newTicket').ajaxForm(function (error) {
					error = JSON.parse(error);
					if (error['msg'] === "") {
							$('#newTicketModal').modal('hide');
							toastr.success('Ticket Created!', 'System:', {timeOut: 10000})
					} else {
							toastr.error(error['msg'], 'System:', {
									timeOut: 10000
							})
					}
			});
			$('#newArrestReport').ajaxForm(function (error) {
					error = JSON.parse(error);
					if (error['msg'] === "") {
							$('#newArrestReportModal').modal('hide');
							toastr.success('Arrest Report Created!', 'System:', {timeOut: 10000})
					} else {
							toastr.error(error['msg'], 'System:', {
									timeOut: 10000
							})
					}
			});
    });
</script>
    <body>
        <?php require_once('inc/top-nav.php');

        if (isset($_GET['error']) && strip_tags($_GET['error']) === 'identity-not-found') {
            clientNotify('error', 'We couldn\'t find that Identity.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'identity-owner') {
            clientNotify('error', 'No Permission.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'identity-session') {
            clientNotify('error', 'Session Error. Select Identity again.');
        }
        ?>
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
			               <form class="form-horizontal m-t-20" id="createIdentity" action="inc/backend/user/leo/createIdentity.php"
			                  method="POST">
			                  <div class="row">
			                     <div class="col">
			                        <div class="form-group">
			                           <input type="text" class="form-control" required="" name="name" placeholder="[1A-01] John Doe">
			                        </div>
			                     </div>
			                  </div>
			                  <div class="row">
			                     <div class="col">
			                        <div class="form-group">
			                           <select class="form-control" id="listLeoDivisions" name="division" required>
			                              <option selected="true" disabled="disabled">Loading Divisions...</option>
			                           </select>
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
			      <!-- js is put here to prevent issues on other parts of leo -->
			      <script type="text/javascript">
			         $(document).ready(function () {
			         	 var priority = false;
			             function checkTime(i) {
			                 if (i < 10) {
			                     i = "0" + i;
			                 }
			                 return i;
							 }
							 
							 $('#changeAOP').ajaxForm(function (error) {
									console.log(error);
									error = JSON.parse(error);
									if (error['msg'] === "") {
										toastr.success('New AOP Set - Please allow a minute for changes to display.', 'System:', {
												timeOut: 10000
										})
									} else {
										toastr.error(error['msg'], 'System:', {
												timeOut: 10000
										})
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
			                 t = setTimeout(function () {
			                     startTime()
			                 }, 500);
			             }

			             startTime();

			             function getLeoInfo() {
			                 (function loadStatus() {
			                     $.ajax({
			                         url: 'inc/backend/user/leo/getStatus.php',
			                         success: function (data) {
			                             $('#getDutyStatus').html(data);
			                         },
			                         complete: function () {
			                             // Schedule the next request when the current one's complete
			                             setTimeout(loadStatus, 1000);
			                         }
			                     });
								  })();
								  (function loadAOP() {
			                     $.ajax({
			                         url: 'inc/backend/user/leo/getAOP.php',
			                         success: function (data) {
			                             $('#displayAOP').html(data);
			                         },
			                         complete: function () {
			                             // Schedule the next request when the current one's complete
			                             setTimeout(loadAOP, 60000);
			                         }
			                     });
			                 })();
			                 (function loadPriorityStatus() {
			                     $.ajax({
			                         url: 'inc/backend/user/leo/panicButton.php',
			                         success: function (data) {
			                             if (data === "1") {
			                                 toastr.options = {
			                                  "preventDuplicates": true,
			                                  "preventOpenDuplicates": true
			                                 };
			                                 toastr.error('PRIORITY IN PROGRESS.', 'System:', {timeOut: 10000})
			                                 $('#panicButtonStatus').html("<font color='red'><b> - PRIORITY IN PROGRESS</b></font>");

			                                 if (!priority) {
			                                     var audio = new Audio('assets/sounds/signal100.mp3');
			                                     audio.play();
			                                     setTimeout(() => {
			                                         var msg = new SpeechSynthesisUtterance('Priority In Progress - Check For Details');
			                                         var voices = window.speechSynthesis.getVoices();
			                                         window.speechSynthesis.speak(msg);
			                                     }, 3000);
			                                 }
			                                 priority = true;
			                             } else {
			                                 $('#panicButtonStatus').html("");
			                                 priority = false;
			                             }
			                         },
			                         complete: function () {
			                             // Schedule the next request when the current one's complete
			                             setTimeout(loadPriorityStatus, 500);
			                         }
			                     });
			                 })();
			             }
			             getLeoInfo();
			         });
			      </script>
			      <!-- code here -->
						<div class="row">
							<div class="col">
								<div class="card-box">
									<div class="dropdown pull-right">
										<b>
											<div id="getTime">Loading...</div>
										</b>
									</div>
									<h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small><font color="white"><i>Supervisor</i></font></small><?php endif; ?> <label id="panicButtonStatus">Loading...</label></h4>
									<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openNameSearch">Name Database</button>
									<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openVehicleSearch">Vehicle Database</button>
									<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openFirearmSearch">Weapon Database</button>
									<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#newTicketModal">Ticket Report</button>
									<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#newArrestReportModal">Arrest Report</button>
									<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#notepadModal">Notepad</button>
									<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#activeUnitsModal">Active Units</button>
									<button class="btn btn-warning btn-sm" onclick="panicBtnMsg();">PANIC BUTTON</button>
									<?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings): ?>
										<a href="leo.php?v=supervisor"><button class="btn btn-darkred btn-sm">Supervisor Panel</button></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-9">
								<div class="card-box">
									<h4 class="header-title mt-0 m-b-30">My Calls</h4>
									<table class="table table-borderless">
										<thead>
											<tr>
												<th>Info</th>
												<th>Location</th>
												<th>Other Units Assigned</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td width="50%">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum, neque optio. Ea adipisci harum vel quibusdam aperiam molestiae. Dolorem sapiente totam mollitia facilis recusandae explicabo veniam vero, quos soluta velit!</td>
												<td>Sandy Shores RD / Joshua RD</td>
												<td>[1A-99] John Doe</td>
											</tr>
										</tbody>
									</table>
								</div>

								<div class="card-box">
									<h4 class="header-title mt-0 m-b-30">Active Bolos</h4>
									<table class="table table-borderless">
										<thead>
											<tr>
												<th>Description</th>
												<th>Created On</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td width="75%">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum, neque optio. Ea adipisci harum vel quibusdam aperiam molestiae. Dolorem sapiente totam mollitia facilis recusandae explicabo veniam vero, quos soluta velit!</td>
												<td>11/24/2018 6:52:04PM</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-3">
								<div class="card-box">
									<h4 class="header-title mt-0 m-b-30">Current Status: <label id="getDutyStatus">Loading...</label></h4>
									<div class="form-group">
										<select class="form-control" name="setUnitStatus" onChange='setUnitStatus(this)'>
										<?php
										$sql             = "SELECT * FROM 10_codes";
										$stmt            = $pdo->prepare($sql);
										$stmt->execute();
										$dbq10codes = $stmt->fetchAll(PDO::FETCH_ASSOC); 
										foreach($dbq10codes as $codes) {
											echo '<option value="'. $codes['code'] .'">'. $codes['code'] .'</option>';
										}
										?>
										</select>
									</div>
								</div>

								<?php if($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings): ?>
								<div class="card-box">
									<h4 class="header-title mt-0 m-b-30">AOP Editor</h4>
									<form method="post" action="inc/backend/user/leo/setAOP.php" id="changeAOP">
										<div class="form-group">
											<div class="col">
												<input class="form-control" type="text" required="" name="newAOP" placeholder="New AOP" value="<?php echo $_SESSION['current_aop']; ?>">
											</div>
											<div class="col m-t-5">
											<button class="btn btn-warning btn-bordred btn-block waves-effect waves-light" onClick="disableClick()" type="submit">Change AOP</button>
										</div>
										</div>
									</form>
								</div>
								<?php endif; ?>
							
							</div>
						</div>
						<!-- MODALS -->
						<!-- search name modal -->
						<div class="modal fade" id="openNameSearch" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							 <div class="modal-dialog modal-full" role="document">
									<div class="modal-content">
										 <div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Name Database</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
												</button>
										 </div>
										 <div class="modal-body">
												<form>
													 <select class="js-example-basic-single" name="nameSearch" id="getAllCharacters" onchange="showName(this.value)">
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
							 <div class="modal-dialog modal-full" role="document">
									<div class="modal-content">
										 <div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Vehicle Database</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
												</button>
										 </div>
										 <div class="modal-body">
												<form>
													 <select class="js-example-basic-single" name="vehicleSearch" id="getAllVehicles" onchange="showVehicle(this.value)">
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
							 <div class="modal-dialog modal-full" role="document">
									<div class="modal-content">
										 <div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Firearms Database</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
												</button>
										 </div>
										 <div class="modal-body">
												<form>
													 <select class="js-example-basic-single" name="firearmSearch" id="getAllFirearms" onchange="showFirearm(this.value)">
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
						<!-- active units modal -->
					   <div class="modal fade" id="activeUnitsModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
					         <div class="modal-content">
					            <div class="modal-header">
					               <h5 class="modal-title" id="exampleModalLabel">Active Units</h5>
					               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					               <span aria-hidden="true">&times;</span>
					               </button>
					            </div>
					            <div class="modal-body">
												<small>Updated every 15 seconds</small>
					              <div id="getActiveUnits"></div>
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
												<textarea name="textarea" oninput="updateNotepad(this.value)" rows="12" cols="104"><?php echo $_SESSION['notepad']; ?></textarea>
											</div>
										</form>
						           </div>
						         </div>
						      </div>
						   </div>
							 <!-- // -->
							 <!-- new ticket modal -->
	 					    <div class="modal fade" id="newTicketModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	 					      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
	 					         <div class="modal-content">
	 					            <div class="modal-header">
	 					               <h5 class="modal-title" id="exampleModalLabel">Writing New Ticket</h5>
	 					               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	 					               <span aria-hidden="true">&times;</span>
	 					               </button>
	 					            </div>
	 					            <div class="modal-body">
										<form id="newTicket" action="inc/backend/user/leo/newTicket.php" method="post">
											<div class="form-group">
												<select class="js-example-basic-single" name="suspect" id="getAllCharacters2" required>
														<option selected="true" disabled="disabled">Loading Characters...</option>
												</select>
											</div>
											<div class="form-group">
												<input type="text" name="location" class="form-control" placeholder="Ticket Location" data-lpignore="true" required />
											</div>
											<div class="form-group">
												<input type="text" name="postal" class="form-control" pattern="\d*" placeholder="(Nearest Postal)" data-lpignore="true" required />
											</div>
											<div class="form-group">
												<input type="text" name="amount" class="form-control" pattern="\d*" placeholder="Fine Amount" data-lpignore="true" required />
											</div>
											<div class="form-group">
												<input type="text" name="reason" class="form-control" maxlength="255" placeholder="Ticket Reason(s)" data-lpignore="true" required />
											</div>
											<div class="modal-footer">
												<div class="form-group">
														<input class="btn btn-primary" onClick="disableClick()" type="submit" value="Submit Ticket">
												</div>
											</div>
										</form>
	 					            </div>
	 					         </div>
	 					      </div>
	 					   </div>
	 						 <!-- // -->
							 <!-- new arrest modal -->
							 <div class="modal fade" id="newArrestReportModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	 					      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
	 					         <div class="modal-content">
	 					            <div class="modal-header">
	 					               <h5 class="modal-title" id="exampleModalLabel">Writing New Arrest Report</h5>
	 					               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	 					               <span aria-hidden="true">&times;</span>
	 					               </button>
	 					            </div>
	 					            <div class="modal-body">
										<form id="newArrestReport" action="inc/backend/user/leo/newArrestReport.php" method="post">
											<div class="form-group">
												<select class="js-example-basic-single" name="suspect" id="getAllCharacters3" required>
														<option selected="true" disabled="disabled">Loading Characters...</option>
												</select>
											</div>
											<div class="form-group">
												<input type="text" name="reason" class="form-control" maxlength="500" placeholder="Summary" data-lpignore="true" required />
											</div>
											<div class="modal-footer">
												<div class="form-group">
														<input class="btn btn-primary" onClick="disableClick()" type="submit" value="Submit Arrest Report">
												</div>
											</div>
										</form>
	 					            </div>
	 					         </div>
	 					      </div>
	 					   </div>
	 						 <!-- // -->
			      <?php break; ?>

					<?php case "supervisor": ?>
						<?php if($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings): ?>
							<?php if(isset($_GET['a']) && strip_tags($_GET['a']) === 'edit-id'): ?>
								<?php
								$id   = $_GET['id'];
								$sql  = "SELECT * FROM identities WHERE identity_id = :identity_id AND department='Law Enforcement'";
								$stmt = $pdo->prepare($sql);
								$stmt->bindValue(':identity_id', $id);
								$stmt->execute();
								$idDB = $stmt->fetch(PDO::FETCH_ASSOC);
								if ($idDB === false) {
									 echo '<script> location.replace("' . $url['leo'] . '?v=supervisor&error=id-not-found"); </script>';
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
									echo '<script> location.replace("' . $url['leo'] . '?v=supervisor&id=suspended"); </script>';
									exit();
								}
								if (isset($_POST['unsuspendIdBtn'])) {
									$sql = "UPDATE identities SET status=? WHERE identity_id=?";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['Active', $_SESSION['editing_identity_id']]);
									echo '<script> location.replace("' . $url['leo'] . '?v=supervisor&id=unsuspended"); </script>';
									exit();
								}
								if (isset($_POST['editIdBtn'])) {
									$updateDivision    = !empty($_POST['division']) ? trim($_POST['division']) : null;
									$updateDivision    = strip_tags($updateDivision);
									$updateSupervisor    = !empty($_POST['supervisor']) ? trim($_POST['supervisor']) : null;
    								$updateSupervisor    = strip_tags($updateSupervisor);

									$sql = "UPDATE identities SET division=?, supervisor=? WHERE identity_id=?";
									$stmt = $pdo->prepare($sql);
									$stmt->execute([$updateDivision, $updateSupervisor, $_SESSION['editing_identity_id']]);
									echo '<script> location.replace("' . $url['leo'] . '?v=supervisor&id=edited"); </script>';
									exit();
								}
								?>
								<div class="row">
									<div class="col-7">
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
												<div class="form-group">
													<div class="col-12">
													<label for="division">Division</label>
													<select class="custom-select my-1 mr-sm-2" id="division" name="division">
														<option selected value="<?php echo $editing_id['division']; ?>"><?php echo $editing_id['division']; ?> (Current)</option>
														<?php 
														$sql             = "SELECT * FROM leo_division";
														$stmt            = $pdo->prepare($sql);
														$stmt->execute();
														$divRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
														foreach($divRow as $leoDivision) {
															echo '
																<option value="' . $leoDivision['name'] . '">' . $leoDivision['name'] . '</option>
															';
														}
														?>
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
										<div class="card-box">
											<h4 class="header-title mt-0 m-b-30">Arrests (<?php echo $editing_id['name']; ?>)</h4>
											<table id="datatable" class="table table-borderless">
											<thead>
											<tr>
													<th>Arrest ID</th>
													<th>Date/Time</th>
													<th>Suspect</th>
													<th>Summary</th>
											</tr>
											</thead>


											<tbody>
											<?php 
											$sql             = "SELECT * FROM arrest_reports WHERE arresting_officer=:editing_idname";
											$stmt            = $pdo->prepare($sql);
											$stmt->bindValue(':editing_idname', $editing_id['name']);
											$stmt->execute();
											$arrestsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

											foreach ($arrestsRow as $arrest) {
												echo '
												<tr>
													<td>'. $arrest['arrest_id'] .'</td>
													<td>'. $arrest['timestamp'] .'</td>
													<td>'. $arrest['suspect'] .'</td>
													<td width="50%">'. $arrest['summary'] .'</td>
												</tr>
												';
											}
											?>
											</table>
										</div>
										<div class="card-box">
											<h4 class="header-title mt-0 m-b-30">Tickets (<?php echo $editing_id['name']; ?>)</h4>
											<table id="datatable2" class="table table-borderless">
											<thead>
											<tr>
													<th>Ticket ID</th>
													<th>Date/Time</th>
													<th>Suspect</th>
													<th>Reason</th>
											</tr>
											</thead>


											<tbody>
											<?php 
											$sql2             = "SELECT * FROM tickets WHERE officer=:editing_idname";
											$stmt2            = $pdo->prepare($sql2);
											$stmt2->bindValue(':editing_idname', $editing_id['name']);
											$stmt2->execute();
											$ticketRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

											foreach ($ticketRow as $ticket) {
												echo '
												<tr>
													<td>'. $ticket['ticket_id'] .'</td>
													<td>'. $ticket['ticket_timestamp'] .'</td>
													<td>'. $ticket['suspect'] .'</td>
													<td width="50%">'. $ticket['reason'] .'</td>
												</tr>
												';
											}
											?>
											</table>
										</div>
									</div>
									<div class="col-5">
										<div class="card-box">
											<h4 class="header-title mt-0 m-b-30">Shift Logs (<?php echo $editing_id['name']; ?>)</h4>
											<!-- CONTENT -->
										</div>
									</div>
								</div>
							<?php else: ?>
							<div class="row">
								<div class="col">
									<div class="card-box">
										<h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small><font color="white"><i>Supervisor</i></font></small><?php endif; ?></h4>
										<?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings): ?>
											<a href="leo.php?v=main"><button class="btn btn-info btn-sm">Back To Patrol Panel</button></a>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-7">
									<div class="card-box">
										<h4 class="header-title mt-0 m-b-30">All LEO Identities</h4>
										<table id="datatable" class="table table-borderless">
										<thead>
										<tr>
												<th>Name</th>
												<th>Division</th>
												<th>Supervisor</th>
												<th>User</th>
												<th>Status</th>
												<th>Actions</th>
										</tr>
										</thead>
										<tbody>
										<?php 
										$sql             = "SELECT * FROM identities WHERE department='Law Enforcement'";
										$stmt            = $pdo->prepare($sql);
										$stmt->execute();
										$leoIdsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

										foreach ($leoIdsRow as $identity) {
											echo '
											<tr>
												<td>'. $identity['name'] .'</td>
												<td>'. $identity['division'] .'</td>
												<td>'. $identity['supervisor'] .'</td>
												<td>'. $identity['user_name'] .'</td>
												<td>'. $identity['status'] .'</td>
												<td><a href="leo.php?v=supervisor&a=edit-id&id='. $identity['identity_id'] .'"><input type="button" class="btn btn-sm btn-success btn-block" value="Edit"></a></td>
											</tr>
											';
										}
										?>
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
        <?php require_once('inc/copyright.php'); ?>
        <?php require_once('inc/page-bottom.php'); ?>
