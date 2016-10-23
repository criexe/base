<!DOCTYPE html>
<html>
<head>
    <?= cx::head() ?>
    <link rel="stylesheet" href="/system/app/assets/developer/style.css">
</head>
<body class="grey darken-4">

    <div class="developer grey-text ligten-4">

        <ul id="sysdata-dropdown" class="dropdown-content">
            <li><a href="<?=URL?>/developer/timer">Timer</a></li>
            <li class="divider"></li>
            <li><a href="<?=URL?>/developer/logs">Logs</a></li>
            <li><a href="<?=URL?>/developer/cache">Caches</a></li>
            <li><a href="<?=URL?>/developer/files">Files</a></li>
        </ul>

        <ul id="ide-dropdown" class="dropdown-content">
            <li><a href="<?=URL?>/developer/ide/php">PHP</a></li>
            <li><a href="<?=URL?>/developer/ide/sql">SQL</a></li>
        </ul>

        <ul id="item-dropdown" class="dropdown-content">
            <li><a href="<?=URL?>/developer/types">Types</a></li>
            <li><a href="<?=URL?>/developer/item/latest">Latest</a></li>
            <li class="divider"></li>
            <li><a href="<?=URL?>/developer/item/export" target="_blank">Export</a></li>
        </ul>

        <div class="navbar-fixed">
            <nav>
                <div class="nav-wrapper orange darken-4">
                    <div class="container">
                        <a href="/developer" class="brand-logo">Developer</a>

                        <ul class="right hide-on-med-and-down">
                            <li><a href="#" class="dropdown-button" data-activates="item-dropdown">Item</a></li>
                            <li><a href="#" class="dropdown-button" data-activates="ide-dropdown">IDE</a></li>
                            <li><a href="#" class="dropdown-button" data-activates="sysdata-dropdown">System</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="container">
            <?=layout::content()?>
        </div>

    </div>

    <?= cx::footer() ?>
</body>
</html>