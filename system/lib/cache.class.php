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
     * @param null $cache_id
     * @param null $content
     */
    public static function create($cache_id = null, $content = null)
    {
        try
        {
            $file_name = self::create_file_name([

                'id'  => $cache_id,
                'ext' => 'cache'
            ]);

            // Cache Commend Line
            if($content == null)
            {
                ob_start();
                $content = ob_get_contents();
            }

            if(is_array($content)) $content = json::encode($content);

            // Create Cache File
            sys::write([

                'file' => CACHE_PATH . DS . $file_name,
                'data' => $content,
                'mode' => 'w'
            ]);

            // Add Log
            logger::add('Cache is stored : ' . $file_name, 'cache');
        }
        catch(Exception $e)
        {
            logger::add('Cache Error : ' . $e->getMessage(), 'cache');
        }
    }



    /**
     * @param array $params
     *
     * @return string
     */
    public static function create_file_name($params = [])
    {
        if(user::logged_in()) return 'u' . user::id() . '.' . $params['id'] . '.' . $params['ext'];
        else                  return $params['id'] . '.' . $params['ext'];
    }



    /**
     * @param null $file_name
     */
    public static function load($file_name = null)
    {
        echo file_get_contents( CACHE_PATH . DS . $file_name );
    }



    /**
     * @param null $expire
     *
     * @return bool|string
     */
    public static function get($cache_id = null)
    {
        try
        {
            $file_name = self::create_file_name([
                'id'  => $cache_id,
                'ext' => 'cache'
            ]);

            if(!file_exists(CACHE_PATH . DS . $file_name))
            {
                return false;
            }
            else
            {
                $cache = sys::read(CACHE_PATH . DS . $file_name);

                if(json::valid($cache))
                {
                    return json::decode($cache);
                }
                else
                {
                    return  $cache;
                }
            }
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public static function delete($cache_id = null)
    {
        try
        {
            if($cache_id == null) return false;

            $file_name = self::create_file_name([

                'id' => $cache_id,
                'ext' => 'cache'
            ]);

            return sys::delete(CACHE_PATH . DS . $file_name);
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    /**
     * @param string $pattern
     */
    public static function clear($pattern = null)
    {
        try
        {
            $deleted = [];
            $files   = glob(CACHE_PATH . DS . '*.cache');

            if($pattern != null) $files = glob(CACHE_PATH . DS . $pattern . '.cache');

            if(is_array($files))
            {
                foreach($files as $file)
                {
                    $deleted[] = $file;
                    sys::delete($file);
                }
            }
            return $deleted;
        }
        catch(Exception $e)
        {
            logger::add('Cache Clear : ' . $e->getMessage());
            return false;
        }
    }

}

?>