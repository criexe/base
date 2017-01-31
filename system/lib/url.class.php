<?php
/**
 * class.url.php
 *
 * @author Mustafa Aydemir
 * @date   15.11.15
 */


class url
{

    public static function set_parameter($parameter = null, $value = null)
    {
        unset($_GET['cxURL'], $_GET['layout']);
        $changed = array_merge($_GET, [$parameter => $value]);

        $params = [];

        foreach($changed as $k => $v) $params[] = "{$k}={$v}";

        $newParams = implode('&', $params);

        return '?' . $newParams;
    }


    public static function get_query()
    {
        $qstring  = [];
        $qstring  = $_SERVER['QUERY_STRING'];
        parse_str($qstring, $qstring);
        $urlvar   = _config('sys.url.var');

        unset($qstring[$urlvar]);

        return http_build_query($qstring);
    }


    public static function path()
    {
        $path = trim(trim(input::get( _config('sys.url.var') ), '/'));
        $path = preg_replace('/(.*?)\.amp$/i', '$1', $path);
        return $path;
    }


    public static function get()
    {
        @ $scheme = array_key_exists('REQUEST_SCHEME', $_SERVER) ? $_SERVER['REQUEST_SCHEME'] : 'http';
        return $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

}

?>