<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?=$this->get_title()?></title>

    <?= asset::load_css($this->get_current_plugin()) ?>

    <link rel="stylesheet" href="<?= ASSETS ?>/font-awesome/css/font-awesome.min.css">
<!--    <link href="--><?//= ASSETS ?><!--/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
<!--    <link href="--><?//= ASSETS ?><!--/default/css/settings.css" rel="stylesheet">-->
<!--    <link href="--><?//= ASSETS ?><!--/default/css/style.css" rel="stylesheet">-->

    <script type="text/javascript" src="<?= ASSETS ?>/jquery/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="<?= ASSETS ?>/jquery/jquery.form.js"></script>
    <script type="text/javascript" src="<?= ASSETS ?>/default/js/default.js"></script>
    <?=html::base()?>
</head>
<body>
