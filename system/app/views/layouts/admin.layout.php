<?php
$types = cx::type();
?>
<!DOCTYPE html>
<html>
<head>
    <?=cx::head()?>
    <?= html::css('/system/app/assets/admin/style.css') ?>
</head>
<body class="grey lighten-4">
<?=cx::body()?>

    <div class="panel grey-text text-darken-4">

        <ul id="addnew-dropdown" class="dropdown-content">
            <?php
            $insert_counter = 0;
            foreach($types as $t)
            {
                if(user::allowed($t['alias'], 'insert') === true)
                {
                    echo '<li><a href="' . URL . '/admin/add/' . $t['alias'] . '">' . $t['name'] . '</a></li>';
                    $insert_counter++;
                }
            }
            if($insert_counter <= 0) echo '<li><span>No permission.</span></li>';
            ?>
        </ul>

        <ul id="latest-dropdown" class="dropdown-content">
            <li><a href="<?=URL?>/admin/latest">All</a></li>
            <li class="divider"></li>
            <?php
            foreach($types as $t)
            {
                if(user::allowed($t['alias'], 'list') === true)
                {
                    echo '<li><a href="' . URL . '/admin/latest/' . $t['alias'] . '">' . $t['name'] . '</a></li>';
                }
            }
            ?>
        </ul>

        <div class="navbar-fixed">
            <nav class="grey darken-3">
                <div class="nav-wrapper container">
                    <a href="/admin" class="brand-logo center white-text"> Panel</a>
                    <ul class="right hide-on-small-and-down">
                        <li><a href="#" class="dropdown-button" data-constrainwidth="false" data-activates="user-dropdown"><?= user::name() ?></a></li>
                    </ul>
                    <ul class="left hide-on-small-and-down">
                        <li><a href="#" class="dropdown-button" data-constrainwidth="false" data-activates="addnew-dropdown">New</a></li>
                        <li><a href="#" class="dropdown-button" data-constrainwidth="false" data-activates="latest-dropdown">Latest</a></li>
                        <?php if(user::authority() == 'admin' || user::authority() == 'developer'): ?>
                        <li><a href="<?=URL?>/admin/settings">Settings</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
        <nav class="orange darken-4">
            <div class="nav-wrapper container">
                <form>
                    <div class="input-field">
                        <input id="search" type="search" required>
                        <label for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
            </div>
        </nav>

        <?= layout::content() ?>

        <!-- sidenav -->
        <ul id="slide-out" class="side-nav">
            <li><div class="user-view">
                    <div class="background">
                        <img src="images/office.jpg">
                    </div>
                    <a href="#!user"><img class="circle" src="images/yuna.jpg"></a>
                    <a href="#!name"><span class="white-text name">John Doe</span></a>
                    <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
                </div></li>
            <li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
            <li><a href="#!">Second Link</a></li>
            <li><div class="divider"></div></li>
            <li><a class="subheader">Subheader</a></li>
            <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
        </ul>
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
        <script>
            $('.button-collapse').sideNav({

                menuWidth    : 300,
                edge         : 'left',
                closeOnClick : true,
                draggable    : true
            });
        </script>
        <!-- sidenav -->

        <a href="<?=URL?>" target="_blank" class="btn-floating cx-fixed btn-large waves-effect waves-light red modal-trigger"><i class="material-icons">home</i></a>
    </div>

    <?=_js(SYS_ASSETS . '/admin/script.js')?>
    <?=cx::footer()?>
</body>
</html>