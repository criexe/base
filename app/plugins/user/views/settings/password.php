<form action="/user/change_password" method="post" data-ajax-form data-type="json" class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-3">Eski Şifre</label>
        <div class="col-sm-9">
            <input type="password" name="old-pass" class="form-control" required>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label class="control-label col-sm-3">Yeni Şifre</label>
        <div class="col-sm-9">
            <input type="password" name="new-pass" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">Tekrar</label>
        <div class="col-sm-9">
            <input type="password" name="new-repeat" class="form-control" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <button type="submit" class="btn btn-success btn-sm">Kaydet</button>
        </div>
    </div>
</form>