<?php
/**
 * class.input.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */


class input
{

    public static function get($name = null, $params = [])
    {
        global $_GET;

        if(!array_key_exists('security_level', $params)) $params['security_level'] = 'high';
        if(!array_key_exists('empty', $params)) $params['empty'] = false;

        @$s = $_GET[$name];

        if($s == null || $s == '' || $s == false)
        {
            return $params['empty'];
        }
        else
        {
            require_once( SYSTEM_LIB_PATH . '/Emojione/autoload.php' );
            $s = Emojione\Emojione::toShort($s);

            return filter::request($s, $params['security_level']);
        }
    }


    public static function post($name = null, $params = [])
    {
        global $_POST;

        if(!array_key_exists('security_level', $params)) $params['security_level'] = 'normal';
        if(!array_key_exists('empty', $params)) $params['empty'] = false;

        @$s = $_POST[$name];

        if($s == null || $s == '' || $s == false)
        {
            return $params['empty'];
        }
        else
        {
            require_once( SYSTEM_LIB_PATH . '/Emojione/autoload.php' );
            $s = Emojione\Emojione::toShort($s);

            return filter::request($s, $params['security_level']);
        }
    }


    public static function file($name = null, $params = [])
    {
        global $_FILES;

        @$file = $_FILES[$name];

        if(!array_key_exists('empty', $params)) $params['empty'] = false;

        if($file['error'] == UPLOAD_ERR_NO_FILE)
        {
            return $params['empty'];
        }
        else
        {
            return $file;
        }
    }

}


?>