<?php

$hooks = [];

$plugin_list = plugin::list_all();

foreach($plugin_list as $plugin)
{
    $settings_file = PLUGINS_PATH . DS . $plugin . DS . 'settings.php';
    if(file_exists($settings_file))
    {
        include_once $settings_file;
    }
}


?>