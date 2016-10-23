<?php
/**
 * class.image.php
 *
 * @author Mustafa Aydemir
 * @date   09/12/15
 */


class image
{

    public static $image       = null;
    public static $type        = null;
    public static $saved_name  = null;

    public static function download($url = null, $file = null, $resize = [])
    {
        try
        {
            // TODO : Düzeltilecek

            if($url == null) throw_exception('No url.');

            $file   = trim( trim($file), '/' );
            $parsed = explode('/', $file);
            $file   = end($parsed);

            if( ! copy($url, CONTENTS_PATH . DS . 'tmp' . DS . $file) ) throw_exception('Error on copy().');

            if(count($resize) > 0)
            {
                foreach($resize as $size)
                {
                    image::load($file);
                    image::resize($size[0], $size[1]);

                    $size_path = implode('x', $size);

                    image::save($file, CONTENTS_PATH . DS . 'images' . DS . $size_path);
                }
            }
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    /**
     * @param null   $image
     * @param string $return
     *
     * @return bool|int|null
     */
    public static function get_type($image = null, $return = 'str')
    {
        if($image == null) return null;

        $types = [
            1  => 'gif',
            2  => 'jpeg',
            3  => 'png',
            4  => 'swf',
            5  => 'psd',
            6  => 'bmp',
            7  => 'tiff_ii',
            8  => 'tif_mm',
            9  => 'jpc',
            10 => 'jp2',
            11 => 'jpx',
            12 => 'jb2',
            13 => 'swc',
            14 => 'iff',
            15 => 'wbmp',
            16 => 'xbm'
        ];

        $get_type = exif_imagetype($image);

        if(!$get_type) return false;

        if($return == 'int')
        {
            return $get_type;
        }
        else
        {
            return $types[$get_type];
        }
    }


    /**
     * @param null $image
     *
     * @return mixed|null
     */
    public static function get_ext($image = null)
    {
        if($image == null) return null;

        $parsed = explode('.', $image);
        $ext    = end($parsed);

        return $ext;
    }


    public static function image_ext($image = null)
    {
        if($image == null) return null;

        $type = self::get_type($image);

        switch($type)
        {
            case 'jpeg':

                return 'jpg';
                break;

            case 'png':

                return 'png';
                break;

            case 'gif':

                return 'gif';
                break;

            default: return false; break;
        }
    }


    /**
     * @param null $image
     *
     * @return null|string
     */
    public static function get_name($image = null)
    {
        if($image == null) return null;

        $parts = explode('.', $image);
        array_pop($parts);

        $name = trim( trim( trim( implode('.', $parts) ) , '.') );

        return $name;
    }


    public static function get_saved_name()
    {
        return self::$saved_name;
    }


    /**
     * @param null $name
     *
     * @return string
     */
    public static function create_name($name = null)
    {
        $n = $name == null ? null : '_' . filter::slugify($name);
        $user_id = (int)(user::id() ? user::id() : 0);
        return $user_id . '_' . time() . rand(1000, 9999) . $n;
    }


    /**
     * @param null $image_file
     *
     * @return bool|null
     */
    public static function load($image_file = null)
    {
        if($image_file == null) return null;

        self::$image = null;
        self::$type  = null;

        self::destroy();

        try
        {
            $type = self::get_type($image_file);

            switch($type)
            {
                case 'jpeg':

                    self::$image = imagecreatefromjpeg($image_file);
                    self::$type  = 'jpeg';
                    break;

                case 'png':

                    self::$image = imagecreatefrompng($image_file);
                    self::$type  = 'png';
                    break;

                case 'gif':

                    self::$image = imagecreatefromgif($image_file);
                    self::$type  = 'gif';
                    break;

                default: return false; break;
            }
        }
        catch(Exception $e)
        {
            return false;
        }

        return true;
    }


    public static function scale($image = null, $width = 0, $height = 0, $resize_larger = true)
    {
        try
        {
            $image = trim($image, '/');

            $remote   = false;
            $new_path = null;
            $scale    = false;

            if( (strpos($image, 'https://') !== false || strpos($image, 'http://') !== false) )
            {
                $parse_url = parse_url($image);

                if( $parse_url['host'] != parse_url(URL, PHP_URL_HOST))
                {
                    $remote = true;
                }
                else
                {
                    $image = $parse_url['path'];
                }
            }

            $img_name = (string)self::get_name(basename($image));
            $img_ext  = (string)self::get_ext(basename($image));

            if(!$img_name) $img_name = self::create_name();
            if(!$img_ext) return false;

            if($remote == true)
            {
                $new_path = 'images' . DS . date('d-m-Y') . DS . $img_name . '.' . $img_ext;
                sys::create_folder(dirname(CONTENTS_PATH . DS . $new_path));
                copy($image, CONTENTS_PATH . DS . $new_path);

                logger::add("Copied Image : $image");

                $image = trim($new_path, '/');

            }

            $img_name = self::get_name($image);
            $img_ext  = self::get_ext($image);

            $new_name    = $img_name . '_' . $width . 'x' . $height . '.' . $img_ext;
            $return_name = trim(preg_replace('#' . CONTENTS_PATH . '(.*?)#i', '$1', $new_name), '/');

            $image_path = CONTENTS_PATH . DS . $image;

            if( ! file_exists($new_name))
            {
                if( ! file_exists($image_path)) throw_exception('File not found : ' . $image_path);

                if($resize_larger == true)
                {
                    // Check size
                    list($img_width, $img_height) = getimagesize($image_path);

                    if($img_width && $img_height)
                    {
                        if($width == 0 & $height > 0)
                            if($img_height > $height) $scale = true;

                        if($width > 0 & $height == 0)
                            if($img_width > $width)  $scale = true;

                        if($width > 0 & $height > 0)
                            if($img_height > $height || $img_width > $width) $scale = true;
                    }
                }
                else
                {
                    $scale = true;
                }


                if($scale == true)
                {
                    $im = new Imagick(realpath($image_path));

                    $im->scaleImage($width, $height);

                    if($im->writeImage(CONTENTS_PATH . DS . $new_name))
                    {
                        logger::add("Scaled Image : $new_name");
                        return $return_name;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    return $image;
                }
            }
            else
            {
                return $return_name;
            }
        }
        catch(Exception $e)
        {
            //echo $e->getMessage();
            return false;
        }
    }


    /**
     * @param null $width
     * @param null $height
     *
     * @return bool
     * @throws Exception
     */
    public static function resize($width = null, $height = null)
    {
        try
        {
            $width  = (int)$width;
            $height = (int)$height;

            if(self::$image == null) return false;

            if($width != null && $height == null)
            {
                $ratio        = $width / imagesx(self::$image);
                $image_height = imagesy(self::$image) * $ratio;
                $image_width  = $width;
            }
            else if($width == null && $height != null)
            {
                $ratio        = $height / imagesy(self::$image);
                $image_width  = imagesx(self::$image) * $ratio;
                $image_height = $height;
            }
            else if($width != null && $height != null)
            {
                $image_width  = $width;
                $image_height = $height;
            }
            else
            {
                $image_width  = 100;
                $image_height = 100;
            }

            $new = imagecreatetruecolor($image_width, $image_height);

            if(self::$type == 'png' || self::$type == 'gif')
            {
                imagealphablending($new, false);
                imagesavealpha($new, true);
                $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
                imagefilledrectangle($new, 0, 0, $image_width, $image_height, $transparent);
            }

            imagecopyresampled($new, self::$image, 0, 0, 0, 0, $image_width, $image_height, imagesx(self::$image), imagesy(self::$image));

            self::$image = $new;
            return true;
        }
        catch(Exception $e)
        {
            throw_exception($e->getMessage());
            return false;
        }
    }


    public static function save($file_name = null, $path = null)
    {
        try
        {
            $parsed    = explode('/', $file_name);
            $new_name  = end($parsed);

            $type      = self::$type;

            sys::create_folder($path);

            switch($type)
            {
                case 'jpg':
                case 'jpeg':

                    $ext = 'jpg';
                    imagejpeg(self::$image, $path . '/' . $new_name . '.' . $ext, 100);
                    break;

                case 'png':

                    $ext = 'png';
                    imagealphablending(self::$image, false);
                    imagesavealpha(self::$image, true);

                    imagepng(self::$image, $path . '/' . $new_name . '.' . $ext, 9);
                    break;

                case 'gif':

                    $ext = 'gif';
                    imagegif(self::$image, $path . '/' . $new_name . '.' . $ext);
                    break;

                default:

                    $ext = 'png';
                    imagepng(self::$image, $path . '/' . $new_name . '.' . $ext, 9);
                    break;
            }

            self::$saved_name = $new_name . '.' . $ext;

            $r = [
                'name'     => $new_name . '.' . $ext,
                'old_name' => $file_name,
                'ext'      => $ext,
                'path'     => $path
            ];

            return $r;
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public static function output($type = 'jpg')
    {
        try
        {
            switch($type)
            {
                case 'jpg':
                case 'jpeg':
                    imagejpeg(self::$image);
                    break;

                case 'png':
                    imagepng(self::$image);
                    break;

                case 'gif':
                    imagegif(self::$image);
                    break;

                default:
                    imagepng(self::$image);
                    break;
            }
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }


    public static function destroy()
    {
        if(is_resource(self::$image)) imagedestroy(self::$image);
    }

}
?>