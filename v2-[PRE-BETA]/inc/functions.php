<?php
// Throw Visual Error (Only works after Header is loaded)
function throwError($error, $log = false) {
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
function clientNotify($type, $error) {
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

function hydridErrors($errno, $errstr, $errfile, $errline, $errcontext) {
    global $debug;
    if ($debug) {
        echo "<br>
        Hello - <br>
        You are seeing this message because an error has occured and freeCAD has stopped working.<br>
        <br>
        Error Information: <br><hr>
        Page: <b>" . $_SERVER['REQUEST_URI'] . "</b><br>
        Error: <b> $errstr </b><br>
        Broken File: <b> $errfile </b><br>
        Line: <b> $errline </b><br>
        <hr>
        If you are the website owner, please report this error to freeCAD Staff.<br>
        If you are not the owner, please try again later!
        ";
        die();
    }
    else {
        echo "<br>
        Hello - <br>
        You are seeing this message because an error has occured and freeCAD has stopped working.<br>
        <br>
        Error Information is hidden because the Community Owner has disabled debug.
        <hr>
        If you are the website owner, please report this error to freeCAD Staff.<br>
        If you are not the owner, please try again later!
        ";
        die();
    }
}
set_error_handler("hydridErrors");

function discordAlert($message) {
    global $discord_webhook;
    //=======================================================================
    // Create new webhook in your Discord channel settings and copy&paste URL
    //=======================================================================
    $webhookurl = $discord_webhook;
    //=======================================================================
    // Compose message. You can use Markdown
    //=======================================================================
    $json_data = array(
        'content' => "$message"
    );
    $make_json = json_encode($json_data);
    $ch = curl_init($webhookurl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $make_json);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    return $response;
}

function godly() {
  global $doul;
  if (json_decode(file_get_contents("https://hydrid.us/internal/authed.php?domain=".$doul."")) === "1") {
    die("Sorry. You have been blacklisted from using freeCAD. This could be from copyright issues, abuse, etc. For further information, contact staff on our discord: https://discord.gg/NeRrWZC");
  }
}

// Log Function
function logAction($action, $user) {
    global $pdo;
    global $time;
    global $us_date;

    $sql_log = "INSERT INTO logs (action, username, timestamp) VALUES (:action, :username, :timestamp)";
    $stmt_log = $pdo->prepare($sql_log);
    $stmt_log->bindValue(':action', $action);
    $stmt_log->bindValue(':username', $user);
    $stmt_log->bindValue(':timestamp', $us_date . ' ' . $time);
    $result_log = $stmt_log->execute();
}

// Log Function For 911 Calls
function log911Action($action) {
    global $pdo;
    global $time;
    global $us_date;
    global $user_id;

    $sql_callLogger = "INSERT INTO 911call_log (call_id, user_id, dispatcher, action, timestamp) VALUES (?,?,?,?,?)";
    $stmt_callLogger = $pdo->prepare($sql_callLogger);
    $result_callLogger = $stmt_callLogger->execute([$_SESSION['viewingCallID'], $user_id, $_SESSION['identity_name'], $action, $us_date . ' ' . $time]);
}

function hearttbeat() {
  global $doul;
  file_get_contents("https://hydrid.us/internal/heartbeat.php?domain=".$doul."");
}

function truncate_string($string, $maxlength, $extension) {

    // Set the replacement for the "string break" in the wordwrap function
    $cutmarker = "**cut_here**";

    // Checking if the given string is longer than $maxlength
    if (strlen($string) > $maxlength) {

        // Using wordwrap() to set the cutmarker
        // NOTE: wordwrap (PHP 4 >= 4.0.2, PHP 5)
        $string = wordwrap($string, $maxlength, $cutmarker);

        // Exploding the string at the cutmarker, set by wordwrap()
        $string = explode($cutmarker, $string);

        // Adding $extension to the first value of the array $string, returned by explode()
        $string = $string[0] . $extension;
    }

    // returning $string
    return $string;

}

function str_replacer($filename, $string_to_replace, $replace_with){
    $content=file_get_contents($filename);
    $content_chunks=explode($string_to_replace, $content);
    $content=implode($replace_with, $content_chunks);
    file_put_contents($filename, $content);
}

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}
?>
