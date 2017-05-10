<div class="settings-form">

    <form action="<?= URL ?>/admin/settings" id="settings_form" method="post">
        <div class="container">
            <div class="section">
                <div class="row">
                    <div class="col s12 m9 l10">
                        <div id="general-settings" class="section scrollspy">
                            <h4 class="header light orange-text text-darken-4">General Settings</h4>
                            <div class="row section">
                                <div class="input-field col l6">
                                    <input type="text" name="option[app.name]" value="<?=cx::option('app.name')?>" class="validate" placeholder="Criexe">
                                    <label>Site Name</label>
                                </div>
                                <div class="input-field col l6">
                                    <input type="text" name="option[app.home_title]" value="<?=cx::option('app.home_title')?>" class="validate" placeholder="Criexe">
                                    <label>Home Title</label>
                                </div>
                                <div class="input-field col l12">
                                    <input type="text" name="option[app.url]" value="<?=cx::option('app.url')?>" class="validate" placeholder="http://...">
                                    <label>Site URL</label>
                                </div>
                                <div class="input-field col l6">
                                    <textarea name="option[app.description]" class="materialize-textarea validate" length="155"><?=cx::option('app.description')?></textarea>
                                    <label>Site Description</label>
                                </div>
                                <div class="input-field col l6">
                                    <textarea name="option[app.keywords]" class="materialize-textarea validate" placeholder="criexe, framework, ..."><?=cx::option('app.keywords')?></textarea>
                                    <label>Site Keywords</label>
                                </div>
                                <div class="input-field col l12">
                                    <select name="option[app.ssl]">
                                        <option value="passive">Passive</option>
                                        <option value="active">Active</option>
                                    </select>
                                    <label>SSL</label>
                                </div>
                                <div class="input-field col l12">
                                    <button type="submit" class="btn green waves-effect">Save</button>
                                </div>
                            </div>
                        </div>

                        <div id="tracking-settings" class="section scrollspy">
                            <h4 class="header light orange-text text-darken-4">Tracking Settings</h4>
                            <div class="row section">
                                <div class="input-field col l12">
                                    <input type="text" name="option[google.analytics.id]" value="<?=cx::option('google.analytics.id')?>" class="validate" placeholder="UA-XXXXX-Y">
                                    <label>Google Analytics ID</label>
                                </div>
                                <div class="input-field col l12">
                                    <textarea name="option[app.tracking_code]" class="materialize-textarea validate"><?=cx::option('app.tracking_code')?></textarea>
                                    <label>Tracking Code</label>
                                </div>
                                <div class="input-field col l12">
                                    <button type="submit" class="btn green waves-effect">Save</button>
                                </div>
                            </div>
                        </div>

                        <div id="static-codes-settings" class="section scrollspy">
                            <h4 class="header light orange-text text-darken-4">Static Codes</h4>
                            <div class="row section">

                                <div class="input-field col l12">
                                    <textarea name="option[static_code.head]" class="materialize-textarea validate"><?=cx::option('static_code.head')?></textarea>
                                    <label>Head Code</label>
                                </div>
                                <div class="input-field col l12">
                                    <button type="submit" class="btn green waves-effect">Save</button>
                                </div>
                            </div>
                        </div>

                        <div id="mail-settings" class="section scrollspy">
                            <h4 class="header light orange-text text-darken-4">Mail Settings</h4>
                            <div class="row section">
                                <div class="input-field col l10">
                                    <input type="text" name="option[mail.smtp.host]" value="<?=cx::option('mail.smtp.host')?>" class="validate">
                                    <label>SMTP Host</label>
                                </div>
                                <div class="input-field col l2">
                                    <input type="number" min="0" name="option[mail.smtp.port]" value="<?=cx::option('mail.smtp.port')?>" class="validate">
                                    <label>SMTP Port</label>
                                </div>
                                <div class="input-field col l6">
                                    <input type="text" name="option[mail.smtp.username]" value="<?=cx::option('mail.smtp.username')?>" class="validate">
                                    <label>SMTP Username</label>
                                </div>
                                <div class="input-field col l6">
                                    <input type="password" name="option[mail.smtp.password]" value="<?=cx::option('mail.smtp.password')?>" class="validate">
                                    <label>SMTP Password</label>
                                </div>

                                <div class="input-field col l6">
                                    <input type="text" name="option[mail.sender.name]" value="<?=cx::option('mail.sender.name')?>" class="validate">
                                    <label>Sender Name</label>
                                </div>
                                <div class="input-field col l6">
                                    <input type="text" name="option[mail.sender.email]" value="<?=cx::option('mail.sender.email')?>" class="validate">
                                    <label>Sender Email</label>
                                </div>
                                <div class="input-field col l12">
                                    <button type="submit" class="btn green waves-effect">Save</button>
                                </div>
                            </div>
                        </div>

                        <div id="facebook-settings" class="section scrollspy">
                            <h4 class="header light orange-text text-darken-4">Facebook Settings</h4>
                            <div class="row section">
                                <div class="input-field col l12">
                                    <input type="text" name="option[facebook.app_id]" value="<?=cx::option('facebook.app_id')?>" class="validate">
                                    <label>App ID</label>
                                </div>
                                <div class="input-field col l12">
                                    <button type="submit" class="btn green waves-effect">Save</button>
                                </div>
                            </div>
                        </div>

                        <div id="twitter-settings" class="section scrollspy">
                            <h4 class="header light orange-text text-darken-4">Twitter Settings</h4>
                            <div class="row section">
                                <div class="input-field col l12">
                                    <input type="text" name="option[twitter.username]" value="<?=cx::option('twitter.username')?>" class="validate">
                                    <label>Username</label>
                                </div>
                                <div class="input-field col l12">
                                    <button type="submit" class="btn green waves-effect">Save</button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col hide-on-small-only m3 l2">
                        <ul class="section table-of-contents">
                            <li><a href="#general-settings">General</a></li>
                            <li><a href="#tracking-settings">Tracking</a></li>
                            <li><a href="#static-codes-settings">Static Codes</a></li>
                            <li><a href="#mail-settings">Mail</a></li>
                            <li><a href="#facebook-settings">Facebook</a></li>
                            <li><a href="#twitter-settings">Twitter</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </form>

</div>