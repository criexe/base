<?php
$all_types = cx::type();
?>
<form action="<?= $form_action ?>" method="post">

    <input type="hidden" name="db[type]" value="<?=$item_type?>">

    <div class="row">
        <div class="col l9 m12 s12">

            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">face</i>
                            <input name="db[title]" id="id_title" type="text" class="validate" length="255" value="<?= $data['title'] ?>">
                            <label for="id_title">Name Surname</label>
                        </div>
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">http</i>
                            <input name="db[url]" id="id_url" type="text" class="validate" length="255" value="<?= $data['url'] ?>" placeholder="<?= "/$item_type" ?>">
                            <label for="id_url">URL</label>
                        </div>
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">description</i>
                            <textarea name="db[description]" id="id_description" length="155" class="materialize-textarea validate"><?= $data['description'] ?></textarea>
                            <label for="id_description">About</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">face</i>
                            <input name="db[username]" type="text" class="validate" length="255" value="<?= $data['username'] ?>">
                            <label>Username</label>
                        </div>
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">email</i>
                            <input name="db[email]" type="email" class="validate" length="255" value="<?= $data['email'] ?>">
                            <label>Email</label>
                        </div>
                        <div class="input-field col l12">
                            <i class="material-icons prefix orange-text text-darken-4">vpn_key</i>
                            <input name="db[password]" type="password" class="validate" length="255" value="">
                            <label>Password</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col l9">

                    <div class="section">
                        <h4 class="header light orange-text text-darken-4">Permissions</h4>
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <?php foreach($all_types as $type): ?>
                                        <div class="col l3">
                                            <div class="section">
                                                <h6 class="header red-text text-darken-4"><?=$type['title']?></h6>
                                            </div>
                                            <div class="divider"></div>
                                            <div class="section">
                                                <p>
                                                    <input name="db[permissions][<?=$type['alias']?>][list]" type="checkbox" id="permis-checkbox-list-<?=$type['alias']?>" value="true" <?= user::allowed($type['alias'], 'list', $data['id']) === true ? 'checked' : null ?>>
                                                    <label for="permis-checkbox-list-<?=$type['alias']?>">List</label>
                                                </p>
                                                <p>
                                                    <input name="db[permissions][<?=$type['alias']?>][insert]" type="checkbox" id="permis-checkbox-insert-<?=$type['alias']?>" value="true" <?= user::allowed($type['alias'], 'insert', $data['id']) === true ? 'checked' : null ?>>
                                                    <label for="permis-checkbox-insert-<?=$type['alias']?>">Insert</label>
                                                </p>
                                                <p>
                                                    <input name="db[permissions][<?=$type['alias']?>][update]" type="checkbox" id="permis-checkbox-update-<?=$type['alias']?>" value="true" <?= user::allowed($type['alias'], 'update', $data['id']) === true ? 'checked' : null ?>>
                                                    <label for="permis-checkbox-update-<?=$type['alias']?>">update</label>
                                                </p>
                                                <p>
                                                    <input name="db[permissions][<?=$type['alias']?>][delete]" type="checkbox" id="permis-checkbox-delete-<?=$type['alias']?>" value="true" <?= user::allowed($type['alias'], 'delete', $data['id']) === true ? 'checked' : null ?>>
                                                    <label for="permis-checkbox-delete-<?=$type['alias']?>">Delete</label>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="card-action">
                                <a href="" class="red-text text-darken-2">Select All</a>
                                <a href="" class="red-text text-darken-2">Deselect All</a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col l3">

                    <div class="section">
                        <h4 class="header light orange-text text-darken-4">Authority</h4>
                        <div class="card">
                            <div class="card-content">
                                <p>
                                    <input type="radio" name="db[authority]" id="authority-user" value="user" <?= $data['authority'] == 'user' || $data['authority'] == null ? 'checked' : null ?>>
                                    <label for="authority-user">User</label>
                                </p>
                                <p>
                                    <input type="radio" name="db[authority]" id="authority-staff" value="staff" <?= $data['authority'] == 'staff' ? 'checked' : null ?>>
                                    <label for="authority-staff">Staff</label>
                                </p>
                                <p>
                                    <input type="radio" name="db[authority]" id="authority-admin" value="admin" <?= $data['authority'] == 'admin' ? 'checked' : null ?>>
                                    <label for="authority-admin">Admin</label>
                                </p>
                                <p>
                                    <input type="radio" name="db[authority]" id="authority-developer" value="developer" <?= $data['authority'] == 'developer' ? 'checked' : null ?>>
                                    <label for="authority-developer">Developer</label>
                                </p>
                            </div>
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
                    <div class="divider"></div>
                    <div class="section">
                        <div class="switch center">
                            <label>
                                Unlock
                                <input name="db[locked]" type="checkbox" value="locked" <?= $data['locked'] == 'locked' ? 'checked' : null ?>>
                                <span class="lever"></span>
                                Lock
                            </label>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="section">
                        <input type="date" name="db[released_at]" class="datepicker" placeholder="Release Date" value="">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <img id="db_image_preview" src="<?=$data['image_thumb']?>">
                    <?= form::image('db[image]', $data['image'], ['preview' => '#db_image_preview']) ?>
                </div>
            </div>

        </div>
    </div>
</form>