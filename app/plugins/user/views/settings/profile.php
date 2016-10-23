<form action="/user/change_profile_image" method="post" data-ajax-form data-type="json" class="form-horizontal" enctype="multipart/form-data">

    <div class="row">
        <div class="col-sm-3">
            <?= user::id_to_avatar(null, 80, 80, false) ?>
        </div>
        <div class="col-sm-9">
            <div class="form-group">
                <label class="control-label">Profil Resmi</label>
                <?= validator::html_input('image', [

                    'name' => 'profile-image',
                    'class' => 'form-control'

                ]) ?>
            </div>
            <button type="submit" class="btn btn-success btn-sm" data-loading-text="Lütfen Bekleyin...">Kaydet</button>
        </div>
    </div>

</form>