<div class="modal <?=$class?>" id="<?=$id?>">
    <div class="green">
        <div class="modal-content">
            <div class="white-text clearfix fsz-h4">
                <i class="material-icons">lock_open</i>
                <span>Login</span>
            </div>
        </div>
    </div>
    <div class="modal-content">
        <form action="<?=SYS_URL?>/login" method="post" data-cx-modal-user-login-form>

            <input type="hidden" name="ref" value="<?=urlencode('/' . url::path())?>">

            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <input type="text" name="user" class="validate">
                    <label>Username or Email</label>
                </div>
                <div class="input-field col l12 m12 s12">
                    <input type="password" name="pass" class="validate">
                    <label>Password</label>
                </div>
                <div class="input-field col l12 m12 s12">
                    <button class="btn green waves-effect">Login</button>
                </div>
            </div>
        </form>
    </div>
</div>