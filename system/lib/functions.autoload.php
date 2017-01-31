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
    global $files;

    $classes = $files['class']; // All Class Files
    $found   = preg_grep("/$class_name\.class(?:\.php)?$/si", $classes); // example.class.php
    $found   = array_values($found);

    if(count($found) > 0)
    {
        // File Path
        $class_path = $found[0];

        // Include Class File
        if(is_readable($class_path)) require_once($class_path);
    }
}


spl_autoload_register('autoload_lib');

?>