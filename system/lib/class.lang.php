<?php

class lang
{

    public static $lang_data = null;
    public static $curr_lang = null;


    public static function start()
    {
        $router_lang  = router::language();

        if($router_lang != null && self::exist($router_lang))
        {
            $lang = $router_lang;
        }
        else
        {
            $lang_cookie = cookie::get('language');

            if($lang_cookie != null && self::exist($lang_cookie))
            {
                $lang = $lang_cookie;
            }
            else
            {
                $lang = sys::get_config('language')['default'];
            }
        }

        if(self::exist($lang))
        {
            $datas = include(self::file_name($lang));

            cookie::set('language', $lang);

            self::$lang_data = $datas;
            self::$curr_lang = $lang;

            return $datas;
        }
        else
        {
            return false;
        }
    }


    public static function current()
    {
        return self::$curr_lang;
    }


    public static function get_data()
    {
        if(self::$lang_data == null) return [];

        return self::$lang_data;
    }


    public static function get($alias = null)
    {
        if($alias == null) return false;

        if(array_key_exists($alias, self::$lang_data))
        {
            return self::$lang_data[$alias];
        }

        return false;
    }


    public static function exist($lang = null)
    {
        if($lang == null) return false;

        if(file_exists(self::file_name($lang)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public static function file_name($lang = null)
    {
        return LANGUAGES_PATH . DS . $lang . '.php';
    }


    public static function url($lang = null)
    {
        $lang_config  = sys::get_config('language');
        $url_language = null;

        if($lang != null)
        {
            $url_language = $lang;
        }
        else
        {
            $lang_cookie = cookie::get('language');

            if($lang_cookie != null && self::exist($lang_cookie))
                $ul = $lang_cookie;
            else
                $ul = $lang_config['default'];

            $show_lang_in_url = $lang_config['show_in_url'] && $lang_config['default'];
            $url_language     = $show_lang_in_url ? $ul : null;
        }

        if( ! self::exist($url_language)) $url_language = $lang_config['default'];

        return sys::get_config('application')['url'] . '/' . $url_language;
    }


    public static function change($lang = null)
    {
        return self::url($lang) . '/' . router::get_paths();
    }

}

?>