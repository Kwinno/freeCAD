<!DOCTYPE html>
<html>
  <head>
        <meta charset="utf-8" />
        <title><?php echo $page['name']; ?> - <?php echo $settings['name']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="Computer Aided Dispatch (CAD) System and Mobile Data Terminal (MDT) for GTA V Roleplaying." name="description" />
        <meta content="HydridSystems" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- <link rel="shortcut icon" href="assets/images/favicon.ico"> -->
        <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <!-- DataTables -->
        <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- Multi Item Selection examples -->
        <link href="assets/plugins/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <?php
            if($settings['dark_mode'] == "true") {
                echo '<link href="assets/css/style-dark.css" rel="stylesheet" type="text/css" />';
            } else {
                echo '<link href="assets/css/style.css" rel="stylesheet" type="text/css" />';
            }
        ?>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/ajaxform.js"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    </head>
