<form action="/user/update_account" method="post" data-ajax-form data-type="json" class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-3">Kullanıcı Adı</label>
        <div class="col-sm-9">
            <input type="text" name="username" class="form-control" value="<?= $username ?>" required>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label class="control-label col-sm-3">İsim</label>
        <div class="col-sm-9">
            <input type="text" name="name" class="form-control" value="<?= $name ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">Soyisim</label>
        <div class="col-sm-9">
            <input type="text" name="surname" class="form-control" value="<?= $surname ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">Email</label>
        <div class="col-sm-9">
            <input type="email" name="email" class="form-control" value="<?= $email ?>" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <button type="submit" class="btn btn-success btn-sm" data-loading-text="Lütfen Bekleyin...">Kaydet</button>
        </div>
    </div>
</form>