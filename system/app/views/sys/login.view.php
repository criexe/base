<form id="admin_login_form" action="<?=SYS_URL?>/login" method="post">
    <div class="section">

        <div class="row">
            <div class="col l2 offset-l5">
                <div class="card grey lighten-4">
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
                            <div class="input-field col l12">
                                <button class="btn green">Login</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<script src="<?=SYS_ASSETS?>/admin/script.js"></script>