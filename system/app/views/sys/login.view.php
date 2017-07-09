<div class="center-box">
    <div class="center-content">

        <form id="admin_login_form" action="<?=SYS_URL?>/login" method="post">
            <div class="section">

                <div class="row">
                    <div class="col l2 offset-l5">
                        <div class="card red white-text">
                            <div class="card-content">
                                <div class="row">
                                    <div class="input-field col l12">
                                        <input name="user" type="text" class="validate" autocomplete="off">
                                        <label>Username or Email</label>
                                    </div>
                                    <div class="input-field col l12">
                                        <input name="pass" type="password" class="validate" autocomplete="off">
                                        <label>Password</label>
                                    </div>

                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="grey lighten-4">
                                <button type="submit">Login</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<?= _css (SYS_ASSETS . '/admin/style.css') ?>
<?= _js  (SYS_ASSETS . '/admin/script.js') ?>