<?php
/**
 * class.form.php
 *
 * @author Mustafa Aydemir
 * @date   24.10.15
 */


class form
{

    /**
     * @param array $params
     *
     * @return array|null
     */
    public static function create_params($params = [])
    {
        if(is_array($params))
        {
            $params_data = [];

            foreach($params as $k => $v)
            {
                if($v == null)
                {
                    $params_data[] = $k;
                }
                else
                {
                    $params_data[] = $k . '="' . $v . '"';
                }
            }
            return implode(' ', $params_data);
        }
        else
        {
            return null;
        }
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public static function text($params = [])
    {
        return '<input type="text" ' . self::create_params($params) . '>';
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public static function email($params = [])
    {
        return '<input type="email" ' . self::create_params($params) . '>';
    }



    /**
     * @param array $params
     *
     * @return string
     */
    public static function date($params = [])
    {
        return '<input type="date" ' . self::create_params($params) . '>';
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public static function password($params = [])
    {
        return '<input type="password" ' . self::create_params($params) . '>';
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public static function hidden($params = [])
    {
        return '<input type="hidden" ' . self::create_params($params) . '>';
    }



    /**
     * @param array $params
     *
     * @return string
     */
    public static function checkbox($params = [])
    {
        return '<input type="checkbox" ' . self::create_params($params) . '>';
    }



    /**
     * @param array $params
     *
     * @return string
     */
    public static function textarea($params = [])
    {
        $pr    = self::create_params($params);
        $value = array_key_exists('value', $params) ? $params['value'] : null;

        return '<textarea ' . $pr . '>' . $value . '</textarea>';
    }


    /**
     * @param array $params
     *
     * @return null|string
     */
    public static function select($params = [])
    {
        if(!array_key_exists('options', $params)) return null;

        $selected_value = array_key_exists('value', $params) ? $params['value'] : false;

        $options = '';
        foreach($params['options'] as $value => $text)
        {
            $selected = $selected_value == $value ? 'selected="selected"' : null;
            $options .= '<option ' . $selected . ' value="' .  $value . '">' . $text . '</option>';
        }

        unset($params['options'], $params['value']);

        return '<select ' . self::create_params($params) . '>' . $options . '</select>';
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public static function file($params = [])
    {
        return '<input type="file" ' . self::create_params($params) . '>';
    }



}
?>