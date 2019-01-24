$(document).ready(function() {
    function getUserIdentitys() {
        (function worker() {
            $.ajax({
                url: 'inc/backend/user/dispatch/getUserIdentitys.php',
                success: function(data) {
                    $('#listIdentitys').html(data);
                },
                complete: function() {
                    // Schedule the next request when the current one's complete
                    setTimeout(worker, 5000);
                }
            });
        })();
    }

    getUserIdentitys();

    $('.js-example-basic-single').select2({
        // theme: "bootstrap4",
        minimumInputLength: 1,
    });
    $('.js-example-basic-multiple').select2({
        theme: "bootstrap4"
    });
});

function getAllCharacters() {
    (function getAllCharacters() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllCharacters.php',
            success: function(data) {
                $('#getAllCharacters').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllCharacters, 5000);
            }
        });
    })();
}
getAllCharacters();

function getAllVehicles() {
    (function getAllVehicles() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllVehicles.php',
            success: function(data) {
                $('#getAllVehicles').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllVehicles, 5000);
            }
        });
    })();
}
getAllVehicles();

function getAllFirearms() {
    (function getAllFirearms() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllFirearms.php',
            success: function(data) {
                $('#getAllFirearms').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllFirearms, 5000);
            }
        });
    })();
}
getAllFirearms();

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
    xmlhttp.open("GET", "inc/backend/user/leo/setStatus.php?status=" + i, true);
    xmlhttp.send();
    toastr.success('Status Updated', 'System');
}

function showName(str) {
    if (str == "") {
        document.getElementById("showPersonInfo").innerHTML = "";
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
                document.getElementById("showPersonInfo").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "inc/backend/user/leo/searchNameDB.php?id=" + str, true);
        xmlhttp.send();
    }
}

function showVehicle(str) {
    if (str == "") {
        document.getElementById("showVehicleInfo").innerHTML = "";
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
                document.getElementById("showVehicleInfo").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "inc/backend/user/leo/searchVehicleDB.php?id=" + str, true);
        xmlhttp.send();
    }
}

function showFirearm(str) {
    if (str == "") {
        document.getElementById("showFirearmInfo").innerHTML = "";
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
                document.getElementById("showFirearmInfo").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "inc/backend/user/leo/searchWeaponDB.php?id=" + str, true);
        xmlhttp.send();
    }
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

function suspendDriversLicense(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/leo/suspendDriversLicense.php?character=" + i,
        cache: false,
        success: function(result) {
            toastr.info('Drivers License Suspended - Changes will take effect in a moment.', 'System:', {
                timeOut: 10000
            })
        }
    });
}

function suspendFirearmsLicense(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/leo/suspendFirearmsLicense.php?character=" + i,
        cache: false,
        success: function(result) {
            toastr.info('Firearms License Suspended - Changes will take effect in a moment.', 'System:', {
                timeOut: 10000
            })
        }
    });
}

function getActiveUnits() {
  var isFocusedDispatch = false;
  (function worker() {
      $.ajax({
          url: 'inc/backend/user/dispatch/getActiveUnits.php',
          success: function(data) {
              $(document).ajaxComplete(function() {
                  $('.select-units').focus(function() {
                      isFocusedDispatch = true;
                  });
                  $('.select-units').blur(function() {
                      isFocusedDispatch = false;
                  });
              });
              if (!isFocusedDispatch) {
                  $('#getActiveUnits').html(data);
              }
          },
          complete: function() {
              setTimeout(worker, 1000);
          }
      });
  })();
}

getActiveUnits();

function updateUnitStatus(selectObject) {
  var i = selectObject.id;
  var str = selectObject.value;
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
  xmlhttp.open("GET", "inc/backend/user/dispatch/updateUnitStatus.php?unit=" + i + "&status=" + str, true);
  xmlhttp.send();
  // alert(str + " " + uid);
  $(".select-units").blur();
  isFocused = false;
}
