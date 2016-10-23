<div class="container mgt15">

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item"><a href="<?= url::set_parameter('tab', 'account') ?>">Account</a></li>
                <li class="list-group-item"><a href="<?= url::set_parameter('tab', 'change_password') ?>">Change Password</a></li>
            </ul>
        </div>
        <div class="col-md-9">

            <?php $this->view('settings/' . $tab, ['user_info' => $user_info], null); ?>

        </div>
    </div>

</div>