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


    /**
     * @param      $type
     * @param bool $multiple
     */
    public static function category($type = null, $categories_data = [], $multiple = true)
    {
        $item_params          = [];
        $item_params['type']  = 'category';
        $item_params['where'] = "`type_alias` = '$type'";
        $type_categories      = item::get_all($item_params);

        $data               = [];
        $data['multiple']   = $multiple == true ? 'multiple' : null;
        $data['categories'] = $type_categories;
        $data['data']       = $categories_data;

        return _render('system/app/views/item/input/category', $data);
    }


    public static function image($name = null, $value = null, $params = [])
    {
        if($name == null) return false;

        sys::specify_params($params, ['wysiwyg', 'preview']);

        $id = filter::slugify($name);

        $tag_id    = 'image_upload_input_' . $id;
        $modal_id  = 'image_upload_modal_' . $id;
        $rand_id   = time() . rand(1, 100000);

        $data             = [];
        $data['tag_id']   = "$tag_id-$rand_id";
        $data['modal_id'] = "$modal_id-$rand_id";
        $data['form_id']  = "image-upload_form_$tag_id-$rand_id";

        $data['src']      = $value != null ? html::image_link($value) : null;
        $data['value']    = $value;
        $data['name']     = $name;

        $data['wysiwyg'] = $params['wysiwyg'];
        $data['preview'] = $params['preview'];

        return _render('system/app/views/item/input/upload_image', $data);
    }


    public static function wysiwyg($name = null, $value = null, $type = 'basic')
    {
        $data          = [];
        $data['name']  = $name;
        $data['value'] = $value;
        $data['type']  = $type;

        return _render('system/app/views/item/input/wysiwyg', $data);
    }


    public static function status($name = null, $value = null)
    {
        $params          = [];
        $params['name']  = $name;
        $params['value'] = $value;

        $params['options'] = [

            'waiting' => 'Waiting',
            'passive' => 'Passive',
            'active'  => 'Active'
        ];

        return self::select($params);
    }


    public static function admin()
    {
        return true;
    }



}
?>