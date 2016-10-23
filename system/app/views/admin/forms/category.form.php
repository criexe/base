<form action="<?= $form_action ?>" method="post">

    <input type="hidden" name="db[type]" value="<?=$item_type?>">

    <div class="row">
        <div class="col l9 m12 s12">

            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">title</i>
                            <input name="db[title]" id="id_title" type="text" class="validate" length="255" value="<?= $data['title'] ?>" required>
                            <label for="id_title">Title / Name</label>
                        </div>
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">http</i>
                            <input name="db[url]" id="id_url" type="text" class="validate" length="255" value="<?= $data['url'] ?>" placeholder="<?= "$item_type/..." ?>" required>
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
                    <div class="section">
                        <div class="switch center">
                            <label>
                                Passive
                                <input name="db[status]" type="checkbox" value="active" <?= $data['status'] == 'active' ? 'checked' : null ?>>
                                <span class="lever"></span>
                                Active
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <div class="input-field">
                        <select name="db[type_alias]" required>
                            <option value="" disabled selected>Type</option>
                            <?php foreach(cx::type() as $type) echo "<option value='{$type['alias']}'>{$type['name']}</option>"; ?>
                        </select>
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