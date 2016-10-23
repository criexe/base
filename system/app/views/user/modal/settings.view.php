<?php $data = user::get(user::id()); ?>

<div class="modal <?=$class?>" id="<?=$id?>">

    <div class="orange darken-4">
        <div class="modal-content center">
            <h4 class="white-text clearfix">
                <span><?=user::name()?></span>
            </h4>
        </div>
    </div>
    <div class="divider"></div>
    <div class="white">
        <ul class="tabs">
            <li class="tab"><a class="orange-text text-darken-4" href="#user_general_settings">General</a></li>
            <li class="tab"><a class="orange-text text-darken-4" href="#user_password_settings">Password</a></li>
        </ul>
    </div>
    <div class="divider"></div>
    <div class="modal-content grey lighten-3">


        <div id="user_general_settings">
            <form action="<?=SYS_URL?>/user_settings" method="post" data-cx-modal-user-settings-form>

                <input type="hidden" name="user_id" value="<?=$data['id']?>">
                <input type="hidden" name="tab" value="general">

                <div class="row">
                    <div class="col l3 m4 s12">
                        <div class="section center">
                            <img id="user_settings_modal_image_preview" class="card" src="<?=$data['image_url']?>" alt="<?=$data['title']?>">
                            <?= form::image('user_settings[image]', $data['image'], ['preview' => '#user_settings_modal_image_preview']) ?>
                            <?php if($data['image'] != null): ?>
                            <div class="section">
                                <button class="btn red darken-4">
                                    <i class="material-icons left">delete</i>
                                    <span>Delete</span>
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col l9 m8 s12">
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" name="user_settings[username]" value="<?=$data['username']?>" class="validate" placeholder="<?=$data['username']?>" required>
                                <label>Username</label>
                            </div>
                            <div class="input-field col s12">
                                <input type="text" name="user_settings[title]" value="<?=$data['title']?>" class="validate" placeholder="<?=$data['title']?>" required>
                                <label>Name Surname</label>
                            </div>
                            <div class="input-field col s12">
                                <input type="email" name="user_settings[email]" value="<?=$data['email']?>" class="validate" placeholder="<?=$data['email']?>" required>
                                <label>Email</label>
                            </div>
                            <div class="input-field col s12">
                                <input type="text" name="user_settings[content]" value="<?=$data['content']?>" class="validate" placeholder="<?=$data['content']?>">
                                <label>About</label>
                            </div>
                            <div class="input-field col l9 m8 s12">
                                <i class="material-icons prefix red-text text-darken-4">lock</i>
                                <input type="password" name="current_password" class="validate" placeholder="Enter your current password." required>
                                <label>Password</label>
                            </div>
                            <div class="input-field col l3 m4 s12 right-align">
                                <button type="submit" class="btn green">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div id="user_password_settings">
            <form action="<?=SYS_URL?>/user_settings" method="post" data-cx-modal-user-settings-form>

                <input type="hidden" name="user_id" value="<?=$data['id']?>">
                <input type="hidden" name="tab" value="password">

                <div class="row">
                    <div class="input-field col s12">
                        <input type="password" name="user_settings[new_password]">
                        <label>New Password</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="password" name="user_settings[new_password_repeat]">
                        <label>New Password (Repeat)</label>
                    </div>
                    <div class="input-field col l10 m9 s12">
                        <i class="material-icons prefix red-text text-darken-4">lock</i>
                        <input type="password" name="current_password" class="validate" placeholder="Enter your current password." required>
                        <label>Password</label>
                    </div>
                    <div class="input-field col l2 m3 s12 right-align">
                        <button type="submit" class="btn green">Save</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>