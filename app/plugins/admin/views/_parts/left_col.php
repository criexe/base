<div class="user-area">
    <div class="avatar-col">
        <img src="<?= user::profile_image() ?>">
    </div>
    <div class="user-col">
        <a href="#" class="btn btn-danger btn-sm pull-right">Logout</a>
    </div>
</div>

<div class="admin-menu">
    <ul>
        <?= $this->left_menu(); ?>
    </ul>
</div>

<div id="left-bottom-box">
    <div class="text-center">
        <a href="/" class="green-tooltip" data-toggle="tooltip" data-placement="top" title="Home"> <i class="fa fa-home"></i> </a>
        <a href="/settings" class="green-tooltip" data-toggle="tooltip" data-placement="top" title="Settings"> <i class="fa fa-cog"></i> </a>
        <a href="/logout" class="green-tooltip" data-toggle="tooltip" data-placement="top" title="Logout"> <i class="fa fa-sign-out"></i> </a>
    </div>
</div>