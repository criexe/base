<?php
/**
 * class.session.php
 *
 * @author Mustafa Aydemir
 * @date   17.10.15
 */

class session
{
    public static function start ()
    {
        session_name('CXSESSID');
        if(session_status() == PHP_SESSION_NONE) session_start();
    }


    /**
     * @param $key
     * @param $value
     *
     * @throws Exception
     */
    public static function set ($key = null, $value = null)
    {
        if($key == NULL)
        {
            throw_exception('No key.');
        }
        else
        {
            $_SESSION[$key] = $value;
        }
    }


    /**
     * @param null $key
     *
     * @return mixed
     * @throws Exception
     */
    public static function get ($key = null)
    {
        if($key == null)
        {
            return null;
        }
        else if(!isset($_SESSION[$key]))
        {
            return null;
        }
        else
        {
            return $_SESSION[$key];
        }
    }


    /**
     * @param null $key
     *
     * @throws Exception
     */
    public static function delete ($key = null)
    {
        if($key == null)
        {
            throw_exception('No key.');
        }
        else
        {
            unset($_SESSION[$key]);
        }
    }


    public static function destroy ()
    {
        session_destroy();
    }
}


?>