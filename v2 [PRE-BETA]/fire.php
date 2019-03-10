<?php
session_name('hydrid');
session_start();
require_once 'inc/connect.php';

require_once 'inc/config.php';

require_once 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'Fire/EMS Module';

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
            header('Location: fire.php?v=nosession&error=identity-not-found');
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

          $_SESSION['on_duty'] = "Fire/EMS";

          if ($identity_owner !== $user_id) {
  				header('Location: fire.php?v=nosession&error=identity-owner');
  				exit();
		       }

    			$stmt2              = $pdo->prepare("DELETE FROM `on_duty` WHERE `name`=:identity_name");
    			$stmt2->bindValue(':identity_name', $identity_name);
    			$result2 = $stmt2->execute();
          $stmt3              = $pdo->prepare("INSERT INTO on_duty (name, department, status) VALUES (:name, :department, 'Off-Duty')");
    			$stmt3->bindValue(':name', $identity_name);
    			$stmt3->bindValue(':department', $identity_department);
    			$result3 = $stmt3->execute();

          header('Location: fire.php?v=main');
          exit();
        }
    }
}
?>
<?php include 'inc/page-top.php'; ?>
<script src="assets/js/pages/fire.js?v=<?php echo $assets_ver ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#createIdentity').ajaxForm(function(error) {
            error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#createIdentity")[0].reset();
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
    <?php include 'inc/top-nav.php';

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
                        <form class="form-horizontal m-t-20" id="createIdentity" action="inc/backend/user/fire/createIdentity.php" method="POST">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="name" placeholder="[F-20] John Doe">
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
            <!-- js is put here to prevent issues on other parts of fire/ems -->
            <script type="text/javascript">
                $(document).ready(function() {
                    var signal100 = false;

                    function checkTime(i) {
                        if (i < 10) {
                            i = "0" + i;
                        }
                        return i;
                    }

                    $('textarea').keypress(function(event) {
                        if (event.which == 13) {
                            event.preventDefault();
                            this.value = this.value + "\n";
                        }
                    });

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

                    function getFireInfo() {
                        (function loadStatus() {
                            $.ajax({
                                url: 'inc/backend/user/fire/getStatus.php',
                                success: function(data) {
                                    $('#getDutyStatus').html(data);
                                    if (data === "Off-Duty") {
                                      document.getElementById("divisionSetter").style.display= "block";
                                      document.getElementById("statusSetter").style.display= "none";
                                      document.getElementById("getMyCalls").style.display= "none";
                                    } else {
                                      document.getElementById("statusSetter").style.display= "block";
                                      document.getElementById("getMyCalls").style.display= "block";
                                      document.getElementById("divisionSetter").style.display= "none";
                                    }
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
                    getFireInfo();
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
                        <h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small><i>Supervisor</i></small><?php endif; ?> <label id="signal100Status">Loading...</label></h4>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#notepadModal">Notepad</button>
                    </div>
                </div>
            </div>
            <div id="checkDispatchers">Loading...</div>
            <div class="row">
                <div class="col-9">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">My Calls
                        </h4>
                        <div id="getMyCalls"></div>
                        <div id="noDis911Calls"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div id="statusSetter">
                      <div class="card-box">
                          <h4 class="header-title mt-0 m-b-30">Current Status: <label id="getDutyStatus">Loading...</label></h4>
                          <div class="form-group">
                              <select class="form-control" name="setUnitStatus" onChange='setUnitStatus(this)'>
                                  <option value="Available">Available</option>
                                  <option value="Responding To Call">Responding To Call</option>
                                  <option value="Off-Duty">Off Duty</option>
                              </select>
                          </div>
                      </div>
                    </div>

                    <div id="divisionSetter">
                      <div class="card-box">
                          <h4 class="header-title mt-0 m-b-30">Set Division</h4>
                          <div class="form-group">
                              <select class="form-control" name="setFireDivision" onChange='setFireDivision(this)'>
                                 <option value="D.F.D - Engine">D.F.D - Engine</option>
                                 <option value="D.F.D - Ladder">D.F.D - Ladder</option>
                                 <option value="D.F.D - Ambulance">D.F.D - Ambuance</option>

                                 <option value="R.H.F.D - Engine">R.H.F.D - Engine</option>
                                 <option value="R.H.F.D - Ladder">R.H.F.D - Ladder</option>
                                 <option value="R.H.F.D - Ambulance">R.H.F.D - Ambuance</option>

                                 <option value="E.B.H.F.D - Engine">E.B.H.F.D - Engine</option>
                                 <option value="E.B.H.F.D - Ladder">E.B.H.F.D - Ladder</option>
                                 <option value="E.B.H.F.D - Ambulance">E.B.H.F.D - Ambuance</option>

                                 <option value="P.B.F.D - Engine">P.B.F.D - Engine</option>
                                 <option value="P.B.F.D - Ladder">P.B.F.D - Ladder</option>
                                 <option value="P.B.F.D - Ambulance">P.B.F.D - Ambuance</option>

                                 <option value="S.S.F.D - Engine">S.S.F.D - Engine</option>
                                 <option value="S.S.F.D - Ladder">S.S.F.D - Ladder</option>
                                 <option value="S.S.F.D - Ambulance">S.S.F.D - Ambuance</option>
                              </select>
                          </div>
                      </div>
                    </div>

                    <?php if($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">AOP Editor</h4>
                        <form method="post" action="inc/backend/user/fire/setAOP.php" id="changeAOP">
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
                            <form method="post" action="fire.php">
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


            <?php endswitch; ?>
        </div>
    </div>
    <!-- CONTENT END -->
    <?php include 'inc/copyright.php'; ?>
    <?php include 'inc/page-bottom.php'; ?>
