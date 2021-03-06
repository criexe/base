<form action="<?= $form_action ?>" method="post">

    <input type="hidden" name="db[type]" value="<?=$item_type?>">

    <div class="row">
        <div class="col l9 m12 s12">

            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">http</i>
                            <input name="db[url]" id="id_url" type="text" class="validate" value="<?= $data['url'] ?>" required>
                            <label for="id_url">URL</label>
                        </div>
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">http</i>
                            <input name="db[moved_to]"  type="text" class="validate" value="<?= $data['moved_to'] ?>" required>
                            <label>Moved To</label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col l3 m12 s12">

            <div class="card">
                <div class="card-content grey lighten-5">
                    <button class="btn green waves-effect" type="submit" disabled>Save</button>

                    <?php if($data['url']): ?><a href="<?=$data['full_url']?>" class="btn orange waves-effect" disabled>Preview</a><?php endif; ?>
                </div>
                <div class="divider"></div>
                <div class="card-content">
                    <div class="row mgb-0 mgt-0">
                        <div class="input-field col l12">
                            <?= form::status('db[status]', $data['status']) ?>
                            <label>Status</label>
                        </div>
                        <div class="input-field col l12">
                            <input type="text" id="id_user" value="<?= $data['user'] != null ? $data['user']['title'] : null ?>" disabled>
                            <input type="hidden" name="db[user]" value="<?= $data['user'] != null ? $data['user']['id'] : null ?>">
                            <label for="id_user">User</label>
                        </div>
                        <div class="input-field col l12">
                            <input name="db[views]" type="number" id="id_views" value="<?= $data['views'] ?>" min="0" class="validate">
                            <label for="id_views">Views</label>
                        </div>

                        <?= form::category(null, $data['category']) ?>

                        <div class="input-field col l12">
                            <textarea name="db[keywords]" id="id_keywords" class="materialize-textarea validate" placeholder="tag1, tag2, tag3, tag4, ..."><?= $data['keywords'] ?></textarea>
                            <label for="id_keywords">Keywords</label>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="card-content grey lighten-5">
                    <img id="db_image_preview" src="<?=$data['image_url']?>">
                    <?= form::image('db[image]', $data['image'], ['preview' => '#db_image_preview']) ?>
                </div>
            </div>

        </div>
    </div>
</form>