<?php

return [

    'name'        => 'Profile Image',
    'type'        => 'image',
    'db_type'  => 'varchar(500)',

    'html_input'  => function($params = [])
    {
        $r = null;

        if(array_key_exists('value', $params))
            $r .= '<div class="input-group-addon"><img src="' . CONTENTS . '/user/profile_images/s30/' . $params['value'] . '"></div>';

        unset($params['value']);

        $r .= form::file( array_merge($params, ['accept' => 'image/x-png, image/jpeg, image/gif']) );

        return $r;
    },

    'display_function' => function($data = null)
    {
        return '<img src="' . CONTENTS . '/user/profile_images/s30/' . $data . '">';
    },

    'input'         => function($name = null)
    {
        $file = input::file($name);

        $image_name = image::create_name();

        image::load($file['tmp_name']);

        image::save($image_name, CONTENTS_PATH . '/user/profile_images/original');

        image::resize(180);
        image::save($image_name, CONTENTS_PATH . '/user/profile_images/s180');

        image::resize(120);
        image::save($image_name, CONTENTS_PATH . '/user/profile_images/s120');

        image::resize(100);
        image::save($image_name, CONTENTS_PATH . '/user/profile_images/s100');

        image::resize(60);
        image::save($image_name, CONTENTS_PATH . '/user/profile_images/s60');

        image::resize(30);
        image::save($image_name, CONTENTS_PATH . '/user/profile_images/s30');



        return image::get_saved_name();
    }

];

?>