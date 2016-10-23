<?php

class cdn
{


    public static $file_type = 'image';


    public static function type($alias = null)
    {
        if($alias == null) return self::$file_type;
        else               self::$file_type = $alias;
    }


    public static function active()
    {
        $status = cx::option('cdn.status');

        if($status === 'active') return true; else return false;
    }


    public static function config()
    {
        $config = [];

        sys::specify_params($config, ['cloud_name', 'api_key', 'api_secret']);

        $config['cloud_name'] = cx::option('cdn.cloud_name');
        $config['api_key']    = cx::option('cdn.api_key');
        $config['api_secret'] = cx::option('cdn.api_secret');

        return $config;
    }


    public static function upload($local_path = null)
    {
        if($local_path == null) return false;

        require_once SYSTEM_LIB_PATH . DS . 'Cloudinary' . DS . 'Cloudinary.php';
        require_once SYSTEM_LIB_PATH . DS . 'Cloudinary' . DS . 'Uploader.php';
        require_once SYSTEM_LIB_PATH . DS . 'Cloudinary' . DS . 'Api.php';

        \Cloudinary::config(self::config());

        $image = \Cloudinary\Uploader::upload($local_path);
        $image['filename'] = $image['public_id'] . '.' . $image['format'];

        return $image;
    }


    public static function image_tag($path, $params = [])
    {
        require_once SYSTEM_LIB_PATH . DS . 'Cloudinary' . DS . 'Cloudinary.php';
        require_once SYSTEM_LIB_PATH . DS . 'Cloudinary' . DS . 'Uploader.php';
        require_once SYSTEM_LIB_PATH . DS . 'Cloudinary' . DS . 'Api.php';

        \Cloudinary::config(self::config());

        return cl_image_tag($path, $params);
    }

}