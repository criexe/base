<script>
    $(function(){ cx.settings.footer() });
</script>

<?php if(user::logged_in()): ?>
    <ul id="user-dropdown" class="dropdown-content">
        <li><a class="grey-text text-darken-3 modal-trigger" href="#user_settings_modal"><i class="fa fa-cog"></i>Settings</a></li>
        <?= (user::authority() === 'admin' || user::authority() === 'developer') ? '<li class="divider"></li>' : null ?>
        <?php if(user::authority() === 'admin' || user::authority() === 'developer'): ?>
        <li><a class="grey-text text-darken-3" target="_blank" href="<?= ADMIN_URL ?>"><i class="fa fa-server"></i>Admin Panel</a></li>
        <?php endif; ?>
        <?php if(user::authority() === 'developer'): ?>
        <li><a class="grey-text text-darken-3" target="_blank" href="<?= DEVELOPER_URL ?>"><i class="fa fa-code"></i>Developer Panel</a></li>
        <?php endif; ?>
        <li class="divider"></li>
        <li><a class="orange-text text-darken-4" href="<?=URL?>/sys/logout?ref=<?=urlencode('/' . url::path())?>"><i class="fa fa-sign-out"></i>Logout</a></li>
    </ul>

    <?= user::modal('settings') ?>

<?php else: ?>
    <?= user::modal('login') ?>
    <?= user::modal('register') ?>
<?php endif; ?>

<?php if(user::authority() === 'developer'): ?>
    <script>
        <?= ! timer::active() ? " cx.alert.toast('<span class=\"red-text\">The timer can be stopped.</span>');" : null ?>
    </script>
<?php endif; ?>

<?= ( (defined('CONTROLLER')) && ! (CONTROLLER == 'admin' || CONTROLLER == 'developer')) ? cx::option('app.tracking_code') : null ?>
