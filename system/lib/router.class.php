<?php
/**
 * class.router.php
 *
 * @author Mustafa Aydemir
 * @date   15.10.15
 */

class router
{
    // Default Page
    public static $default  = null;
    public static $paths    = null;
    public static $language = null;


    public static function start ()
    {
        // Rewrite
        $replaced_url = self::rewrite();

        //$url_paths = $url->get_paths();
        $url_paths = $replaced_url;
        if(!empty($url_paths))
        {
            self::$paths = $url_paths;
        }
        self::$default = _config('router.default');
    }


    public static function language()
    {
        return self::$language;
    }


    /**
     * @return array
     * @throws Exception
     */
    public static function parse ()
    {
        $r = array();

        if(self::$paths == null)
        {
            $r[0] = self::$default['controller'];
            $r[1] = self::$default['method'];
        }
        else
        {
            $_parts = explode('/', self::$paths);
            $r      = $_parts;
        }
        return $r;
    }


    public static function others ()
    {
        $ps = self::parse();
        if(count($ps) > 2)
        {
            array_shift($ps);
            array_shift($ps);
            return $ps;
        }
        else
        {
            return array();
        }
    }


    public static function get_paths ()
    {
        $_paths      = trim(trim(input::get( _config('sys.url.var') ), '/'));

        // Check AMP
        if(preg_match('/.*?\.amp$/i', $_paths))
        {
            cx::data('amp.is', true);
            $_paths = preg_replace('/(.*?)\.amp$/i', '$1', $_paths);
        }

        // Query Strings Control
        if(stristr($_paths, '#'))
        {
            $cln1   = explode('#', $_paths);
            $_paths = $cln1[0];
        }
        if(stristr($_paths, '?'))
        {
            $cln2   = explode('?', $_paths);
            $_paths = $cln2[0];
        }

        // Language
        if(_config('language.url'))
        {
            $e = explode('/', $_paths);

            if(lang::exist($e[0]))
            {
                self::$language = $e[0];
                unset($e[0]);
            }

            $_paths = implode('/', $e);
        }

        return $_paths;
    }



    // Replace Config
    protected static function rewrite ()
    {
        // Create arrays
        $pattern     = [];
        $replacement = [];

        $router = _config('router');

        foreach($router as $k => $v)
        {
            $pattern[]     = $k;
            $replacement[] = $v;
        }

        // Replaced URL
        $rep = preg_replace($pattern, $replacement, self::get_paths());
        return $rep;
    }

}



?>