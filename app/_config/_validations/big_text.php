<?php

return [
    'name'     => 'Big Text',
    'db_type'  => 'text',
    'html_input' => function($params = []){ return form::textarea($params); },

    'input' => function($name = null, $options = [])
    {
        $GLOBALS['data'] = input::post($name);

        if(array_key_exists('download_images', $options) && $options['download_images'] == true)
        {
            $links = filter::convert_links($GLOBALS['data'], true, [

                'if_image' => function($image_link, $mime)
                {
                    $ext = null;
                    switch($mime)
                    {
                        case 'image/png'  : $ext = '.png'; break;
                        case 'image/jpeg' : $ext = '.jpg'; break;
                        case 'image/pjpeg': $ext = '.jpg'; break;
                        case 'image/gif'  : $ext = '.gif'; break;
                    }

                    $image_name = image::create_name() . $ext;
                    $image_path = 'images' . DS . date('m-Y/d');

                    sys::create_folder(CONTENTS_PATH . DS . $image_path);

                    $path = CONTENTS_PATH . DS . $image_path . DS . $image_name;

                    // Upload Image
                    if(copy($image_link, $path))
                    {
                        $new_image = CONTENTS . '/' . $image_path . '/' . $image_name;
                        $GLOBALS['data'] = str_replace($image_link, $new_image, $GLOBALS['data']);

                        // Create Map
                        sys::create_map_file(

                            $new_image, [

                                'type'     => 'file',
                                'mime'     => $mime,
                                'original' => $new_image
                            ]
                        );
                    }
                }
            ]);
        }

        return $GLOBALS['data'];
    }
];

?>