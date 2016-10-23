<?php
/**
 * functions.autoload.php
 *
 * @author Mustafa Aydemir
 * @date   15.10.15
 */

// Library Classes
function autoload_lib($class_name = null)
{
    $class_path = SYSTEM_LIB_PATH . '/class.' . $class_name . '.php';
    if(is_readable($class_path))
    {
        require_once($class_path);
    }
}


spl_autoload_register('autoload_lib');

?>