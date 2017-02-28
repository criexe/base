<?php

class controller_helper
{

    function upload_image()
    {
        try
        {
            $path  = date('m-Y') . DS . date('d');
            $path  = trim($path, '/ \\');
            $image = false;

            $file = input::file('image');

            if(!$file)
            {
                throw_exception('No file.');
                $image_name = null;
            }
            else
            {
                sys::create_folder(CONTENTS_PATH . DS . 'images' . DS . $path);
                $image_name = $path . DS . image::create_name($file['name']) . '.' . image::image_ext($file['tmp_name']);

                if(move_uploaded_file($file['tmp_name'], CONTENTS_PATH . DS . 'images' . DS . $image_name))
                {
                    $image = [

                        'url'        => CONTENTS . "/images/$image_name",
                        'secure_url' => CONTENTS . "/images/$image_name",
                    ];
                }
            }

            if(cdn::active())
            {
                $image = cdn::upload(CONTENTS_PATH . DS . $image_name);
            }

            if($image)
            {
                echo response::ajax([

                    'status'     => true,
                    'message'    => 'Success.',
                    'image'      => $image,
                    'url'        => $image['url'],
                    'secure_url' => $image['secure_url'],
                    'filename'   => $image_name
                ]);
            }
            else
            {
                throw_exception('Error on upload.');
            }
        }
        catch(Exception $e)
        {
            echo response::ajax([

                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    function check_url()
    {
        $url = input::post('url');

        if(item::get(['where' => "`url` = '$url'"])) die('true');
        else die('false');
    }

}

?>