<?php
/**
 * class.load.php
 *
 * @author Mustafa Aydemir
 * @date   17.10.15
 */



// TODO: Bütün metodların ortak noktaları tek bir metod altında toplanacak.



class load
{

    /**
     * @param null $model_name
     *
     * @return bool
     */
    public static function model($model_name = null)
    {
        if($model_name == null) return false;
        $class_name = null;

        if(strpos($model_name, ':'))
        {
            $model_parts = explode(':', $model_name);
            $plugin_name = $model_parts[0];
            $class_name  = end($model_parts);

            if($plugin_name == null) $plugin_name = $class_name;

            $model_file  = PLUGINS_PATH . '/' . $plugin_name . '/models/model.' . $class_name . '.php';
        }
        else
        {
            $class_name = $model_name;
            $model_file = MODELS_PATH . '/model.' . $model_name . '.php';
        }

        if(!file_exists($model_file))
        {
            return false;
        }
        else
        {
            $model_class = 'model_' . $class_name;

            require_once($model_file);
            $model = new $model_class();
            return $model;
        }
    }



    public static function library($lib_name = null)
    {
        if($lib_name == null) return false;
        $class_name = null;

        if(strpos($lib_name, ':'))
        {
            $lib_parts   = explode(':', $lib_name);
            $plugin_name = $lib_parts[0];
            $class_name  = end($lib_parts);

            $lib_file    = PLUGINS_PATH . '/' . $plugin_name . '/libraries/lib.' . $class_name . '.php';
        }
        else
        {
            $class_name = $lib_name;
            $lib_file = LIBRARIES_PATH . '/lib.' . $lib_name . '.php';
        }

        if(!file_exists($lib_file))
        {
            return false;
        }
        else
        {
            $lib_class = 'lib_' . $class_name;

            require_once($lib_file);
            $lib = new $lib_class();
            return $lib;
        }
    }

}

?>