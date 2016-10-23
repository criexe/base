<div class="sign-io">
    <div class="center-box">
        <div class="center-content">

            <div class="col-md-2 col-md-offset-5">
                <form
                    action="/user/login"
                    method="post"

                    data-ajax-form
                    data-type="json"
                    data-location="/"

                    autocomplete="off"
                >



                    <div class="panel panel-sign-io">
                        <div class="panel-body">
                            <div class="input-group radius-top">
                                <label class="radius-top">
                                    <i class="fa fa-user"></i>
                                    <input type="text" name="user" class="form-control radius-top" placeholder="Email veya Kullanıcı Adı" autocomplete="off">
                                </label>
                            </div>
                            <div class="input-group radius-bottom">
                                <label class="radius-bottom">
                                    <i class="fa fa-key"></i>
                                    <input type="password" name="pass" class="form-control radius-bottom" placeholder="Parola" autocomplete="off">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-left">
                        <button type="submit" class="btn btn-success btn-sign-io">
                            Giriş Yap
                        </button>

                        <label>
                            <input type="checkbox">
                            Beni Hatırla
                        </label>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>