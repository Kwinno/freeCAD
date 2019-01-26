<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (isset($_SESSION['on_duty']) && $_SESSION['on_duty'] === "Dispatch") {
  $call_id = strip_tags($_GET['id']);
  $sql             = "SELECT * FROM 911calls WHERE call_id= ?";
  $stmt            = $pdo->prepare($sql);
  $stmt->execute([$call_id]);
  $callInfo = $stmt->fetch(PDO::FETCH_ASSOC);

  $_SESSION['viewingCallID'] = $call_id;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="callLocation">Call Location</label>
          <input class="form-control" type="text" readonly="" value="<?php echo $callInfo['call_location'] .' / '. $callInfo['call_crossstreat'] .' / '. $callInfo['call_postal']; ?>">
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label for="callStatus">Call Status</label>
          <input class="form-control" type="text" readonly="" value="<?php echo $callInfo['call_status']; ?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="callDetails">Call Details</label>
          <textarea class="form-control" name="textarea" onfocusout="updatingCallDesc(this.value)" rows="6"><?php echo htmlspecialchars($callInfo['call_description']); ?></textarea>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="getAllActiveUnitsForCall">Assign Unit</label>
          <select class="form-control" name="getAllActiveUnitsForCall" id="getAllActiveUnitsForCall" onchange="assignUnit(this.value)">
             <option selected="true" disabled="disabled">Loading Units...</option>
          </select>
        </div>
      </div>
      <div class="col-6">
        <div id="getAttchedUnits"></div>
      </div>
    </div>
  </body>
</html>
