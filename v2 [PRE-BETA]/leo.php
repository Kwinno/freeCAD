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
                header('Location: ' . $url['leo'] . '?v=nosession&error=identity-owner');
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
        $("input[type='submit']").attr("disabled", false);

        $("form").submit(function(){
          $("input[type='submit']").attr("disabled", true);
          setTimeout(function(){ $("input[type='submit']").attr("disabled", false); }, 30000);
          return true;
        })
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
			               <?php echo $page['name']; ?>
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
								<?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings): ?>
									<button class="btn btn-darkred btn-sm" data-toggle="modal" data-target="#pendingIdsModal">Pending Identites</button>
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
			         </div>
						<div class="col">
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
			         </div>
			      </div>
			      <div class="row">
			         <div class="col-9">
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
						 <?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings): ?>
							 <!-- pending ids modal -->
   					   <div class="modal fade" id="pendingIdsModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   					      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
   					         <div class="modal-content">
   					            <div class="modal-header">
   					               <h5 class="modal-title" id="exampleModalLabel">Pending Identites</h5>
   					               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
   					               <span aria-hidden="true">&times;</span>
   					               </button>
   					            </div>
   					            <div class="modal-body">
   					              <div id="getPendingIds"></div>
   					            </div>
   					         </div>
   					      </div>
   					   </div>
   						 <!-- // -->
						 <?php endif; ?>
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
															<input class="btn btn-primary" type="submit" value="Submit Ticket">
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
															<input class="btn btn-primary" type="submit" value="Submit Arrest Report">
													</div>
												</div>
											</form>
	 					            </div>
	 					         </div>
	 					      </div>
	 					   </div>
	 						 <!-- // -->
			      <?php break; ?>
			      <?php endswitch; ?>
			   </div>
			</div>
        <!-- CONTENT END -->
        <?php require_once('inc/copyright.php'); ?>
        <?php require_once('inc/page-bottom.php'); ?>
