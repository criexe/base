<?php
/**
 * class.cookie.php
 *
 * @author Mustafa Aydemir
 * @date   17.10.15
 */

class cookie
{
    /**
     * @param null   $key
     * @param null   $value
     * @param null   $expire
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httponly
     *
     * @throws Exception
     */
    public static function set ($key = NULL, $value = NULL, $params = [])
    {
        if($key == NULL)
        {
            throw_exception('No key.');
        }
        else
        {
            $key = self::set_prefix($key);

            if(!is_array($params)) $params = [];

            if(!array_key_exists('expire', $params)) $params['expire']     = time() + 360000;
            if(!array_key_exists('path', $params)) $params['path']         = '/';
            if(!array_key_exists('domain', $params)) $params['domain']     = '.' . $_SERVER["HTTP_HOST"];
            if(!array_key_exists('secure', $params)) $params['secure']     = false;
            if(!array_key_exists('httponly', $params)) $params['httponly'] = true;

            setcookie($key, $value, $params['expire'], $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
    }


    /**
     * @param null $key
     *
     * @throws Exception
     */
    public static function delete ($key = null)
    {
        self::set($key, '', time() - 3600);
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
            throw_exception('No key.');
        }
        else
        {
            $key = self::set_prefix($key);

            if(isset($_COOKIE[$key]))
            {
                return $_COOKIE[$key];
            }
            else
            {
                return null;
            }
        }
    }



    /**
     * @return mixed
     */
    public static function cookies ()
    {
        return $_COOKIE;
    }



    /**
     * @param null $str
     *
     * @return mixed
     */
    public static function set_prefix($str = null)
    {
        if($str != null)
        {
            return _config('cookie.prefix') . $str;
        }
        else
        {
            return null;
        }
    }



    public static function destroy ()
    {
        foreach($_COOKIE as $k => $v)
        {
            self::delete($k);
        }
    }
}

?>