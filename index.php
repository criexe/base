<?php

require_once 'settings.cx';
if(!defined('cx')) die('Error.');

require_once SYSTEM_INCLUDES_PATH . '/load_site.php';

// App Views Counter
cx::counter('app.views', 1);

cx::save();

?>