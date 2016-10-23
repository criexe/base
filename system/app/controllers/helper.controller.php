<?php

class controller_helper
{

    function upload_image()
    {
        try
        {
            $path  = 'images' . DS . date('m-Y') . DS . date('d');
            $path  = trim($path, '/ \\');
            $image = false;

            $file = input::file('image');

            if(!$file)
            {
                throw_exception('No file.');
            }
            else
            {
                sys::create_folder(CONTENTS_PATH . DS . $path);
                $image_name = $path . DS . image::create_name($file['name']) . '.' . image::image_ext($file['tmp_name']);

                if(move_uploaded_file($file['tmp_name'], CONTENTS_PATH . DS . $image_name))
                {
                    $image = [

                        'url' => CONTENTS_PATH . DS . $image_name,
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
                    'filename'   => $image['public_id'] . '.' . $image['format']
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

}

?>