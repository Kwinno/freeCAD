<?php
require('../../../includes/connect.php');
include('../../../includes/config.php');
session_start();

require('../../../classes/lib/password.php');

userLogin($_POST['username'], $_POST['password']);