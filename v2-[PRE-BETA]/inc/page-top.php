<!DOCTYPE html>
<html>
  <head>
        <meta charset="utf-8" />
        <title><?php echo $page['name']. ' - ' . $settings['name']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="Computer Aided Dispatch (CAD) System and Mobile Data Terminal (MDT) for GTA V Roleplaying." name="description" />
        <meta content="freeCAD" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <link rel="stylesheet" href="assets/plugins/switchery/switchery.css">
        <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <!-- DataTables -->
        <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- Multi Item Selection examples -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="assets/plugins/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css?v=<?php echo $assets_ver ?>" rel="stylesheet" type="text/css" />
        <script src="//code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/ajaxform.js?v=<?php echo $assets_ver ?>"></script>
        <script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
    </head>
    <?php
    if (!function_exists('godly')) {
      die('Seems like you are trying to remove the auth checks. Not smart. This has been logged.');
    }
    ?>
    <style>
    .ui-front {
        z-index: 9999;
    }

    .ui-front {
        z-index: 9999;
    }
    .ui-autocomplete {
      position: fixed;
      z-index: 215000000 !important;
    }
    </style>
