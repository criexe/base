<?php
/**
 * class.error.php
 *
 * @author Mustafa Aydemir
 * @date   15.10.15
 */

class error
{
    protected static $path = PAGES_PATH;


    public static function load_page($file_name = null)
    {
        ob_start();
        ob_end_clean();
        $file = self::$path . '/' . $file_name;
        include($file);
        exit;
    }


    public static function show_404()
    {
        header("HTTP/1.0 404 Not Found");
//        cx::counter('error.404', 1);
        self::load_page('404.php');
    }

}

?>