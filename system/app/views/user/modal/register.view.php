<div class="modal <?=$class?>" id="<?=$id?>">
    <div class="orange">
        <div class="modal-content">
            <h4 class="white-text clearfix">
                <i class="material-icons">lock</i>
                <span>Register</span>
            </h4>
        </div>
    </div>
    <div class="modal-content">
        <form action="<?=SYS_URL?>/register" method="post" data-cx-modal-user-register-form>

            <input type="hidden" name="ref" value="<?=urlencode('/' . url::path())?>">

            <div class="row">
                <div class="input-field col l12 m12 s12">
                    <input type="text" name="name" class="validate" required>
                    <label>Name Surname</label>
                </div>
                <div class="input-field col l12 m12 s12">
                    <input type="text" name="username" class="validate" required>
                    <label>Username</label>
                </div>
                <div class="input-field col l12 m12 s12">
                    <input type="email" name="email" class="validate" required>
                    <label>Email</label>
                </div>
                <div class="input-field col l12 m12 s12">
                    <input type="password" name="password" class="validate" required>
                    <label>Password</label>
                </div>
                <div class="input-field col l12 m12 s12">
                    <button class="btn orange waves-effect">REGISTER</button>
                </div>
            </div>
        </form>
    </div>
</div>