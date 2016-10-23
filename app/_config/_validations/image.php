<?php

return [

    'name'        => 'Image',
    'db_type'     => 'varchar(500)',
    'type'        => 'image',



    'html_input' =>

        function($params = [], $options = null)
        {
            $r = null;

            if(array_key_exists('value', $params))
            {
                $size_path = array_key_exists('size', $options) ? $options['size'] : 'x30';

                $r .= '<div class="input-group-addon">';
                $r .= html::image($params['value'], 0, 30);
                $r .= '</div>';
            }

            // Remove Value
            unset($params['value']);

            $r .= form::file( array_merge($params, ['accept' => 'image/x-png, image/jpeg, image/gif']) );

            return $r;
        },





    'display_function' =>

        function($data = null, $options = null)
        {
            if($data == null) return null;

            return html::image($data, 0, 50);
        },





    'input' =>

        function($name = null, $options = [])
        {
            $path = array_key_exists('path', $options) ? $options['path'] : 'images' . DS . date('d-m-Y');
            $path = trim($path, '/ \\');

            $file = input::file($name);

            if(!$file)
            {
                return false;
            }
            else
            {
                sys::create_folder(CONTENTS_PATH . DS . $path);
                $image_name = $path . DS . image::create_name() . '.' . image::image_ext($file['tmp_name']);

                $im = new Imagick($file['tmp_name']);
                if($im->writeImage(CONTENTS_PATH . DS . $image_name))
                {
                    return $image_name;
                }
                else
                {
                    return false;
                }
            }
        }

];

?>