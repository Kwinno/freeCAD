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

$bolo_created_by_form = !empty($_POST['bolo_created_by']) ? trim($_POST['bolo_created_by']) : null;
$vehicle_plate_form = !empty($_POST['vehicle_plate']) ? trim($_POST['vehicle_plate']) : null;
$vehicle_model_form = !empty($_POST['vehicle_model']) ? trim($_POST['vehicle_model']) : null;
$vehicle_color_form = !empty($_POST['vehicle_color']) ? trim($_POST['vehicle_color']) : null;
$bolo_reason_form = !empty($_POST['bolo_reason']) ? trim($_POST['bolo_reason']) : null;

dbquery('INSERT INTO bolos (vehicle_plate, vehicle_color, vehicle_model, bolo_reason, bolo_created_by, bolo_created_on) VALUES (
    "' . escapestring($vehicle_plate_form) . '", 
    "' . escapestring($vehicle_color_form) . '", 
    "' . escapestring($vehicle_model_form) . '", 
    "' . escapestring($bolo_reason_form) . '", 
    "' . escapestring($bolo_created_by_form) . '",
    "' . escapestring($date .' '. $time) . '" 
)', false);