<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- End Required meta tags -->
    <!-- Begin SEO tag -->
    <title> <?= $this->title ?> </title>
    <meta name="author" content="Shrief Mohamed">
    <meta property="og:locale" content="en_AU">
    <!-- End SEO tag -->
    <!-- FAVICONS -->
    <link rel="shortcut icon" href="<?= IMAGES_DIR ?>favicon.png" type="image/x-icon">
    <link rel="icon" href="<?= IMAGES_DIR ?>favicon.png" type="image/x-icon">

    <meta name="theme-color" content="#3063A0">
    <!-- End FAVICONS -->

    <!-- BEGIN BASE STYLES -->
    <script src="<?= VENDOR_DIR ?>pace/pace.min.js"></script>
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>pace/pace.min.css">

    <?php if ($this->controller == 'index') : ?>
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>bootstrap1/dist/css/bootstrap.min.css">
    <?php else : ?>
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>bootstrap/css/bootstrap.min.css">
    <?php endif; ?>

    <link rel="stylesheet" href="<?= VENDOR_DIR ?>open-iconic/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>fontawesome/css/fontawesome.all.css">
    <!-- END BASE STYLES -->

    <?php if ($this->controller == 'quotes') : ?>
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>nouislider/nouislider.min.css">
    <?php endif; ?>

    <!-- BEGIN THEME STYLES -->
    <link rel="stylesheet" href="<?= CSS_DIR ?>main.min.css">
    <link rel="stylesheet" href="<?= CSS_DIR ?>custom.css">

    <?php if (
            $this->controller == 'pos' ||
            $this->controller == 'quotes' ||
            $this->controller == 'xero' ||
            $this->controller == 'admin' ||
            $this->controller == 'licenses' ||
            $this->controller == 'customers'
    ) : ?>
    <link rel="stylesheet" href="<?= CSS_DIR ?>pos.css">
    <style>.page-inner {padding-top: 0}</style>
    <?php endif; ?>

    <?php if ($this->controller == 'quotes') : ?>
        <link rel="stylesheet" href="<?= CSS_DIR ?>quotes.css">
    <?php endif; ?>


    <?php if ($this->controller == 'admin') : ?>
        <style>.page-inner {padding-top: 4px !important;}</style>
    <?php endif; ?>

    <?php if ($this->controller == 'technician') : ?>
        <style>
            .app-main {padding-left: 0 !important;}
            .page-inner {padding-right: 1rem;padding-left: 1rem;}
        </style>
    <?php endif; ?>


    <!-- END THEME STYLES -->

    <!-- BEGIN PLUGINS STYLES -->
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>jquery.datetimepicker/jquery.datetimepicker.min.css">

    <link rel="stylesheet" href="<?= VENDOR_DIR ?>DataTables/datatables.min.css">
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>DataTables/Buttons-1.6.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="<?= VENDOR_DIR ?>DataTables/Responsive-2.2.5/css/responsive.dataTables.min.css">

<!--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">-->
<!--    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.css"/>-->
    <!-- END PLUGINS STYLES -->

    <script src="<?= VENDOR_DIR ?>jquery/jquery.min.js"></script>
</head>
<body data-spy="scroll" data-target="#nav-content" data-offset="76">

<!-- .app //has-fullwidth-->
<div class="app">