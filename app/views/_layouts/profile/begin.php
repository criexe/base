<?php $user_lib = load::library('user:user'); ?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?=$this->get_title()?></title>
    <?= $this->get_description() ?>

    <?= asset::load_css() ?>
    <?= asset::load_js_header() ?>

    <link rel="stylesheet" href="<?= ASSETS ?>/font-awesome/css/font-awesome.min.css">

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- favicon -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?= sys::get_config('tracker')['google_analytics']; ?>
    <?=html::base()?>
</head>
<body>

<div class="topbar">

    <div class="topbar-body">
        <div class="topbar-logo-col">
            <a href="/">
                <i class="fa fa-share-alt"></i>
                Mustafa Aydemir
            </a>

<!--            <ul class="logo-menu">-->
<!--                <li><a href="#">Akış</a></li>-->
<!--                <li><a href="#">Sözlük</a></li>-->
<!--                <li><a href="#">Sorular</a></li>-->
<!--                <li><a href="#">Resimler</a></li>-->
<!--                <li><a href="#">Haberler</a></li>-->
<!--            </ul>-->
        </div>
        <div class="topbar-new-button-col">
            <a href="#new" class="btn btn-success" data-toggle="modal" data-target="#newDictModal">
                <i class="fa fa-plus"></i>
                <span>Yeni</span>
            </a>
        </div>
        <div class="topbar-search-col">
            <form action="#" method="get">
                <input type="search" class="form-control" placeholder="Ara...">
            </form>
        </div>
        <div class="topbar-user-col hidden-xs">
            <?php if($user_lib->logged_in()): ?>
                <div class="pull-left"><?= user::print_image($user_lib->get_profile_image(), $user_lib->get_name_surname(), 28, 28) ?></div>
                <a href="/user/profile/mustafa" class="user-name pull-left"><?=$user_lib->get_name_surname()?></a>
                <a href="#settings" class="topbar-icon pull-right" data-toggle="modal" data-target="#settingsModal">
                    <i class="fa fa-cog" data-toggle="tooltip" data-placement="left" title="Ayarlar"></i>
                </a>
            <?php else: ?>
                <a href="/login" class="btn btn-success btn-sm">
                    <i class="fa fa-user"></i>
                    <span>Giriş Yap</span>
                </a>
                <a href="/register" class="btn btn-warning btn-sm">
                    <i class="fa fa-lock"></i>
                    <span>Kayıt Ol</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

</div>

<div class="site-container">

    <div class="site-content-col col-lg-10 col-md-9">
        <div class="content-body scrollbar">

            <!--
            <nav class="header-menu">
                <div class="container">
                    <a href="/dictionary" class="btn btn-success"> <i class="fa fa-plus"></i> Yeni</a>
                </div>
            </nav>
            -->

            <div class="container">

                <div class="profile">