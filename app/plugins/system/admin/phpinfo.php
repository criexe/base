<?php

if(input::get('info'))
{
    echo phpinfo();
    exit;
}

?>

<iframe src="/admin/plugin_page/system/phpinfo?layout=false&info=true" frameborder="0" style="width:100%;height:100%"></iframe>
