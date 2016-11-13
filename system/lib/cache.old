<?php
/**
 * class.cache.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */


class cache
{

    /**
     * @param array $params
     */
    public static function create($params = [])
    {
        ob_start();

        $file_name = self::create_file_name([
            'uri' => $_SERVER['REQUEST_URI'],
            'ext' => 'cache'
        ]);

        // Cache Commend Line
        $cache_comment = '<!-- [ Loaded From The Cache ] ' . date(sys::get_config('application')['date_pattern']) . ' -->';
        $cache_content = $cache_comment . "\n" . ob_get_contents();

        // Cache File Data
        $params['data'] = array_key_exists('data', $params) ? $params['data'] : $cache_content;

        // Create Cache File
        sys::write([

            'file' => CACHE_PATH . DS . $file_name,
            'data' => $params['data'],
            'mode' => 'w'
        ]);

        // Add Log
        //logger::add('Cache is stored : ' . $file_name, 'cache');
    }



    /**
     * @param array $params
     *
     * @return string
     */
    public static function create_file_name($params = [])
    {
        return 'cache_' . load::library('user:user')->get_user_id() . '_' .  md5($params['uri']) . '.' . $params['ext'];
    }



    /**
     * @param null $file_name
     */
    public static function load($file_name = null)
    {
        echo file_get_contents( CACHE_PATH . '/' . $file_name );
    }



    /**
     * @param null $expire
     *
     * @return bool|string
     */
    public static function get($expire = null)
    {
        $file_name = self::create_file_name([
            'uri' => $_SERVER['REQUEST_URI'],
            'ext' => 'cache'
        ]);

        if(!file_exists(CACHE_PATH . '/' . $file_name))
        {
            return false;
        }
        else
        {
            $expire = is_int($expire) ? $expire : time() - sys::get_config('cache')['time'];

            if(filemtime(CACHE_PATH . '/' . $file_name) >= $expire)
            {
                return $file_name;
            }
            else
            {
                return false;
            }
        }
    }



    /**
     * @param string $pattern
     */
    public static function clear()
    {
        $deleted = [];
        $files   = glob(CACHE_PATH . DS . '*.cache');

        foreach($files as $file)
        {
            $deleted[] = $file;
            unlink($file);
        }

        $files   = glob(CACHE_PATH . DS . 'db' . DS . '*.cache');

        foreach($files as $file)
        {
            $deleted[] = $file;
            unlink($file);
        }

        return $deleted;
    }

}

?>