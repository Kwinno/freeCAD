<?php
if (floatval(phpversion()) < 5.6) {
	die("Your PHP Version is not supported. Please update to continue using Hydrid.");
}

// MySQL Settings
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "hydrid_rw");
require ('functions.php');

// Do Not Edit Below --- SERIOUSLY DON'T TOUCH THIS STUFF.

$pdoOptions = array(
	PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
	PDO::ATTR_EMULATE_PREPARES => false
);
try {
	$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, $pdoOptions);
}

catch(Exception $e) {
	throwError('Unable to connect to database.', true);
	die('Unable to connect to database.');
}
