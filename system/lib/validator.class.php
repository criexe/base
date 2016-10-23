<?php
/**
 * class.validator.php
 *
 * @author Mustafa Aydemir
 * @date   25.10.15
 */


// TODO : Methodlara bölünecek

class validator
{

    public static function check($name = null, $data = null, $params = [])
    {
        try
        {
            $config  = self::config($name);
            $valArr  = array_merge($config, $params);
            $len     = strlen($data);

            // Get Type
            $type = 'text';
            if( ! (!array_key_exists('type', $valArr) || $valArr['type'] == 'text') ) $type = $valArr['type'];


            // Type : Text
            if($type == 'text')
            {
                // Required
                if(array_key_exists('required', $valArr))
                {
                    if($data == null || $data == '' || $data == false)
                        throw_exception('[' . $valArr['name'] . '] Required.');
                }

                // Max Len
                if(array_key_exists('max_len', $valArr))
                {
                    if($len > $config['max_len'])
                        throw_exception('[' . $valArr['name'] . '] The number of characters greater than ' . $valArr['max_len'] . '.');
                }

                // Min Len
                if(array_key_exists('min_len', $valArr))
                {
                    if($len < $valArr['min_len'])
                        throw_exception('[' . $valArr['name'] . '] The number of characters younger than ' . $valArr['min_len'] . '.');
                }

                // Pattern
                if(array_key_exists('pattern', $valArr))
                {
                    if(!preg_match($valArr['pattern'], $data))
                        throw_exception('[' . $valArr['name'] . '] Pattern is not compatible.');
                }

                // Filter Var
                if(array_key_exists('filter_var', $valArr))
                {
                    if(!filter_var($data, $valArr['filter_var']))
                        throw_exception('Not Valid');
                }
            } // Text



            // Type : Image
            if($type == 'image')
            {
                // Required
                if(array_key_exists('required', $valArr))
                {
                    if($data == null || $data == '' || $data == false)
                        throw_exception('[' . $valArr['name'] . '] Required.');
                }
            }

            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    /**
     * @param null $name
     * @param null $data
     *
     * @return null
     */
    public static function display($name = null, $data = null, $options = null)
    {
        try
        {
            if($name == null)    throw_exception('No name.');
            if($options == null) $options = [];

            $config = self::config($name);

            if(!array_key_exists('display_function', $config))
            {
                return $data;
            }
            else
            {
                return $config['display_function']($data, $options);
            }
        }
        catch(Exception $e)
        {
            return null;
        }
    }


    /**
     * @param null $name
     * @param null $params
     *
     * @return null
     */
    public static function html_input($name = null, $params = null, $options = [])
    {
        try
        {
            if($params == null) throw_exception('No data.');
            if($name == null) throw_exception('No name.');
            if($options == null) $options = [];

            $config = self::config($name);

            if(!array_key_exists('html_input', $config))
            {
                return null;
            }
            else
            {
                return $config['html_input']($params, $options);
            }
        }
        catch(Exception $e)
        {
            return null;
        }
    }


    public static function input($name = null, $input_name = null, $options = [], $params = [])
    {
        if($options == null) $options = [];
        if($params == null)  $params  = [];

        try
        {
            if($name == null) return null;

            $config = self::config($name);

            if(!array_key_exists('input', $config))
            {
                $data = input::post($input_name);
                self::check($name, $data, $params);
            }
            else
            {
                $data = $config['input']($input_name, $options);
            }

            return $data;
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public static function config($name = null) // TODO: Hatalar düzeltilecek.
    {
        if($name == null) return false;

        $file_path = VALIDATIONS_PATH . DS . $name . '.php';

        if(!file_exists($file_path)) throw_exception('File not exist.');

        $config = include $file_path;
        return $config;
    }

}

?>