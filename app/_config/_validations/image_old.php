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
                $r .= '<img src="' . CONTENTS . DS . sprintf($params['value'], $size_path) . '">';
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

            $size_path        = array_key_exists('size', $options) ? $options['size'] : 'x30';
            $options['link']  = array_key_exists('link', $options) ? $options['link'] : false;

            if($options['link'] === true)
            {
                return CONTENTS . DS . sprintf($data, $size_path);
            }
            else
            {
                return '<img src="' . CONTENTS . DS . sprintf($data, $size_path) . '">';
            }
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
                $image_name = image::create_name();

                // Save original image
                image::load($file['tmp_name']);
                image::save($image_name, CONTENTS_PATH . DS . $path . DS . 'original');


                if(array_key_exists('resize', $options) && is_array($options['resize']))
                {
                    foreach($options['resize'] as $size)
                    {
                        image::load($file['tmp_name']);
                        image::resize($size[0], $size[1]);

                        $size_path = implode('x', $size);

                        image::save($image_name, CONTENTS_PATH . DS . $path . DS . $size_path);
                    }
                }

                // Standart sizes
                image::load($file['tmp_name']);
                image::resize(30);
                image::save($image_name, CONTENTS_PATH . DS . $path . DS . '30x');

                image::load($file['tmp_name']);
                image::resize(50);
                image::save($image_name, CONTENTS_PATH . DS . $path . DS . '50x');

                image::load($file['tmp_name']);
                image::resize(null, 30);
                image::save($image_name, CONTENTS_PATH . DS . $path . DS . 'x30');

                image::load($file['tmp_name']);
                image::resize(null, 50);
                image::save($image_name, CONTENTS_PATH . DS . $path . DS . 'x50');

                $saved_name = image::get_saved_name();

                image::destroy();

                return $path . DS . '%s' . DS . $saved_name;
            }
        }

];

?>