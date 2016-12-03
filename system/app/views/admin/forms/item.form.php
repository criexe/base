<form action="<?= $form_action ?>" method="post">

    <input type="hidden" name="db[type]" value="<?=$item_type?>">

    <div class="row">
        <div class="col l9 m12 s12">

            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">title</i>
                            <input name="db[title]" id="id_title" type="text" class="validate" length="255" value="<?= $data['title'] ?>">
                            <label for="id_title">Title / Name</label>
                        </div>
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">http</i>
                            <input name="db[url]" id="id_url" type="text" class="validate" length="255" value="<?= $data['url'] ?>" placeholder="<?= "$item_type/..." ?>">
                            <label for="id_url">URL</label>
                        </div>
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">description</i>
                            <textarea name="db[description]" id="id_description" length="155" class="materialize-textarea validate"><?= $data['description'] ?></textarea>
                            <label for="id_description">Description</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <?= form::wysiwyg('db[content]', $data['content'], 'full') ?>
            </div>

        </div>
        <div class="col l3 m12 s12">

            <div class="card">
                <div class="card-content center">
                    <button class="btn green waves-effect waves-light" type="submit">Save</button>
                    <button class="btn red darken-3 waves-effect waves-light" type="reset">Reset</button>
                </div>
            </div>

            <div class="card">
                <div class="card-content clear">
                    <div class="input-field">
                        <?= form::status('db[status]', $data['status']) ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col l12">
                            <input type="text" id="id_user" value="<?= array_key_exists('name', $data['user']) ? $data['user']['name'] : 'Unknown' ?>" disabled>
                            <label for="id_user">User</label>
                        </div>
                        <div class="input-field col l12">
                            <input name="db[views]" type="number" id="id_views" value="<?= $data['views'] ?>" min="0" class="validate">
                            <label for="id_views">Views</label>
                        </div>

                        <?= form::category($item_type, $data['category']) ?>

                        <div class="input-field col l12">
                            <textarea name="db[keywords]" id="id_keywords" class="materialize-textarea validate"><?= $data['keywords'] ?></textarea>
                            <label for="id_keywords">Keywords</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <img id="db_image_preview" src="<?=$data['image_url']?>">
                    <?= form::image('db[image]', $data['image'], ['preview' => '#db_image_preview']) ?>
                </div>
            </div>

        </div>
    </div>
</form>