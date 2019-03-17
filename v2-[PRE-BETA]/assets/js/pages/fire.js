$(document).ready(function() {
    function getUserIdentitys() {
      $.ajax({
          url: 'inc/backend/user/fire/getUserIdentitys.php',
          success: function(data) {
              $('#listIdentitys').html(data);
          },
          complete: function() {
              // Schedule the next request when the current one's complete
              setTimeout(getUserIdentitys, 5000);
          }
      });
    }

    getUserIdentitys();

    $('.select2').select2({
        minimumInputLength: 3
    });
});

function checkActiveDispatchers() {
  $.ajax({
      url: 'inc/backend/user/leo/checkActiveDispatchers.php',
      success: function(data) {
          $('#checkDispatchers').html(data);

          if (data !== "") {
              // If no dispatchers are online....
                //Future update
          } else {
              // If dispatchers are online....
                //Future update
          }
      },
      complete: function() {
          setTimeout(checkActiveDispatchers, 5000);
      }
  });
}
checkActiveDispatchers();

function setUnitStatus(selectObject) {
    var i = selectObject.value;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //hmmmzz
        }
    };
    xmlhttp.open("GET", "inc/backend/user/fire/setStatus.php?status=" + i, true);
    xmlhttp.send();
    if (i === "Off-Duty") {
      window.location.replace("fire.php?v=nosession");
    }
    toastr.success('Status Updated', 'System');
}

function setFireDivision(selectObject) {
    var i = selectObject.value;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //hmmmzz
        }
    };
    xmlhttp.open("GET", "inc/backend/user/fire/setFireDivision.php?div=" + i, true);
    xmlhttp.send();
    toastr.success('Division Updated', 'System');
}

function updateNotepad(str) {
    if (str == "") {
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //hmmm
            }
        };
        xmlhttp.open("GET", "inc/backend/user/leo/updateNotepad.php?txt=" + str, true);
        xmlhttp.send();
    }
}

function getPendingIds() {
  $.ajax({
      url: 'inc/backend/user/leo/getPendingIds.php',
      success: function(data) {
          $('#getPendingIds').html(data);
      },
      complete: function() {
          // Schedule the next request when the current one's complete
          setTimeout(getPendingIds, 1000);
      }
  });
}
getPendingIds();

function approveID(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/fire/approveID.php?id=" + i,
        cache: false,
        success: function(result) {
            toastr.success('ID Approved.', 'System:', {
                timeOut: 10000
            })
        }
    });
}

function rejectID(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/fire/rejectID.php?id=" + i,
        cache: false,
        success: function(result) {
            toastr.error('ID Rejected.', 'System:', {
                timeOut: 10000
            })
        }
    });
}

function getMyCalls() {
  $.ajax({
      url: 'inc/backend/user/fire/getMyCalls.php',
      success: function(data) {
          $('#getMyCalls').html(data);
      },
      complete: function() {
          setTimeout(getMyCalls, 1000);
      }
  });
}

getMyCalls();

function getAttchedUnits() {
  $.ajax({
      url: 'inc/backend/user/dispatch/getAttchedUnits.php',
      success: function(data) {
          $('#getAttchedUnits').html(data);
      },
      complete: function() {
          setTimeout(getAttchedUnits, 1000);
      }
  });
}
getAttchedUnits();

function clear911Call() {
    toastr.warning('Please Wait...')
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $('#callInfoModal').modal('hide');
            toastr.success('Call Archived.')
        }
    };
    xmlhttp.open("GET", "inc/backend/user/dispatch/archiveCall.php", true);
    xmlhttp.send();
}
