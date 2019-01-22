<?php
// Log Function

function logAction($action, $user)
{
	global $time;
	global $us_date;
}

// Throw Visual Error (Only works after Header is loaded)

function throwError($error, $log = false)
{
	// Load Toastr JavaScript and CSS

	echo '
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script type="text/javascript">
            if(window.toastr != undefined) {
                if (typeof jQuery == "undefined") {
                    alert("Error Handler: ' . $error . '")
                } else {
                    toastr.error("' . $error . '")
                }
            } else {
                alert("Error Handler: ' . $error . '")
            }
        </script>
    ';
}

// Throw Notification (Only works after Header is loaded)

function clientNotify($type, $error)
{
	echo '
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script type="text/javascript">
            if(window.toastr != undefined) {
                if (typeof jQuery == "undefined") {
                    alert("System: ' . $error . '")
                } else {
                    toastr.' . $type . '("' . $error . '")
                }
            } else {
                alert("System: ' . $error . '")
            }
        </script>
    ';
}

function hydridErrors($errno, $errstr, $errfile, $errline, $errcontext) 
{
    global $debug;
    if ($debug) {
        echo "<br>
        Hello - <br>
        You are seeing this message because an error has occured and Hydrid has stopped working.<br>
        <br>
        Error Information: <br><hr>
        Page: <b>". $_SERVER['REQUEST_URI'] ."</b><br>
        Error: <b> $errstr </b><br>
        Broken File: <b> $errfile </b><br>
        Line: <b> $errline </b><br>
        <hr>
        If you are the website owner, please report this error to Hydrid Staff.<br>
        If you are not the owner, please try again later!
        ";
        die();
    } else {
        echo "<br>
        Hello - <br>
        You are seeing this message because an error has occured and Hydrid has stopped working.<br>
        <br>
        Error Information is hidden because the Community Owner has disabled debug.
        <hr>
        If you are the website owner, please report this error to Hydrid Staff.<br>
        If you are not the owner, please try again later!
        ";
        die();
    }
}
set_error_handler("hydridErrors");

?>