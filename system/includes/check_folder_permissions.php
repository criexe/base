<?php

$chmod_777_folders = [

    SYSDATA_PATH,
    SYSDATA_PATH . '/cache',
    SYSDATA_PATH . '/cookies',
    SYSDATA_PATH . '/logs',
    SYSDATA_PATH . '/trash',

    ASSETS_SYS,

    CONTENTS_PATH
];


foreach($chmod_777_folders as $folder)
{
    chmod($folder, 0777);
}


?>