<?php
//REQUIRED - DO NOT REMOVE . YOU WILL BREAK STUFF .
require '../../includes/connect.php';
include '../../includes/config.php';
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ' . $url['login'] . '');
    exit();
}
include '../../includes/isLoggedIn.php';

$call_description = !empty($_POST['call_description']) ? trim($_POST['call_description']) : null;
$call_location      = !empty($_POST['call_location']) ? trim($_POST['call_location']) : null;
$call_crossstreat       = !empty($_POST['call_crossstreat']) ? trim($_POST['call_crossstreat']) : null;
$call_postal       = !empty($_POST['call_postal']) ? trim($_POST['call_postal']) : null;

dbquery('INSERT INTO 911calls (caller_id, call_description, call_location, call_crossstreat, call_postal, call_timestamp) VALUES (
    "Dispatch", 
    "' . escapestring($call_description) . '", 
    "' . escapestring($call_location) . '", 
    "' . escapestring($call_crossstreat) . '", 
    "' . escapestring($call_postal) . '",
    "' . escapestring($date .' '. $time) . '" 
)', false);