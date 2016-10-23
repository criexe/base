<!doctype html>
<html lang="tr">
<head>

    <!--

    -------------------------------------------
                       _
              ___ _ __(_) _____  _____
             / __| '__| |/ _ \ \/ / _ \
            | (__| |  | |  __/>  <  __/
             \___|_|  |_|\___/_/\_\___|


    -------------------------------------------

    -->

    <meta charset="UTF-8">
    <title><?=$this->get_title()?></title>

    <link rel="stylesheet" href="<?= ASSETS ?>/font-awesome/css/font-awesome.min.css">
    <link href="<?= ASSETS ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= ASSETS ?>/admin/style.css" rel="stylesheet">

    <script type="text/javascript" src="<?= ASSETS ?>/jquery/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="<?= ASSETS ?>/jquery/jquery.form.js"></script>
    <script type="text/javascript" src="<?= ASSETS ?>/default/js/default.js"></script>
    <script type="text/javascript" src="<?= ASSETS ?>/default/js/functions.js"></script>
    <script type="text/javascript" src="<?= ASSETS ?>/ckeditor/ckeditor.js"></script>

    <?=html::base()?>
</head>
<body>

<div id="main-content">

    <div id="left-col">
        <div id="left-col-body">

            <?php $this->view('_parts/left_col', null, null); ?>

        </div><!--left-col-body-->
    </div><!-- left-col -->

    <div id="right-col">
        <div id="admin-content">
