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
        return time() . rand(1000, 9999) . $n;
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