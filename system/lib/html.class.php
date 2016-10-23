<?php
/**
 * class.html.php
 *
 * @author Mustafa Aydemir
 * @date   15.10.15
 */


class html
{

    public static function pagination($total_content = null, $active_page = null, $row_number = 100, $ajax_content = false)
    {
        if($active_page == null)
            $active_page = (int)input::get('page_no') ? (int)input::get('page_no') : 1;

        if($ajax_content) $ajax_content = ' data-ajax-link data-target="' . $ajax_content . '"';
        else              $ajax_content = null;

        $pagination = '<ul class="pagination">';

        if($active_page > 1)
        {
            $pagination .= '<li><a' . $ajax_content . ' href="' . url::set_parameter('page_no', $active_page - 1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
        }

        $total_page = ceil($total_content / $row_number);

        for($i = 1; $i <= $total_page; $i++)
        {
            $active      = $i == $active_page ? ' class="active"' : null;
            $pagination .= '<li' . $active . '><a' . $ajax_content . ' href="' . url::set_parameter('page_no', $i) . '">' . $i . '</a></li>';
        }

        if($total_page > $active_page)
        {
            $pagination .= '<li><a' . $ajax_content . ' href="' . url::set_parameter('page_no', $active_page + 1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
        }

        $pagination .= '</ul>';
        return $pagination;
    }


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


    public static function render($name = null, $datas = null)
    {
        try
        {
            if($name == null)
            {
                throw_exception('View file name can\'t empty.');
            }
            else
            {
                $nameArr = explode(':', $name);

                if(count($nameArr) != 2) return false;

                $view_file = PLUGINS_PATH . DS . $nameArr[0] . DS . 'views' . DS . $nameArr[1] . '.php';

                if(!file_exists($view_file))
                {
                    throw_exception("View not found : $name - $view_file");
                }
                else
                {
                    // Data Variables
                    if($datas != null)
                        foreach($datas as $k => $v)
                            $$k = $v;

                    ob_start();
                    include $view_file;
                    $contents = ob_get_contents();
                    ob_end_clean();

                    return $contents;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            logger::add('view(): ' . $e->getMessage(), 'render');
            return false;
        }
    }


    public static function alert($msg = null, $type = 'success')
    {
        return '<div class="alert alert-' . $type . '">' . $msg . '</div>';
    }

    public static function css($url = null)
    {
        if($url == null) return false;

        return '<link rel="stylesheet" href="' . $url . '">';
    }

    public static function js($url = null)
    {
        if($url == null) return false;

        return '<script src="' . $url . '" type="text/javascript"></script>';
    }

    public static function image($path, $width = 0, $height = 0, $params = [], $resize_larger = true, $link = false)
    {
        if($width  > 0 && ! array_key_exists('width' , $params)) $params['width']  = $width;
        if($height > 0 && ! array_key_exists('height', $params)) $params['height'] = $height;

        if(cdn::active())
        {
            if($link == true)
            {
                $image_tag = cdn::image_tag($path, $params);

                $doc = new DOMDocument();
                $doc->loadHTML($image_tag);
                $imageTags = $doc->getElementsByTagName('img');

                foreach($imageTags as $tag) return $tag->getAttribute('src');

                return false;
            }
            else
            {
                return cdn::image_tag($path, $params);
            }
        }
        else
        {
            if( ! extension_loaded('imagick'))
            {
                return '<img src="' . CONTENTS . '/' . $path . '"' . self::create_params($params) . '>';
            }

            $resized_img = image::scale($path, $width, $height, $resize_larger);

            $img_path = $resized_img;

            $param_width  = $width > 0 ? $width : null;
            $param_height = $height > 0 ? $height : null;

            if($link == true)
            {
                return CONTENTS . '/' . $img_path;
            }
            else
            {
                // return "<img src='$img_path' width='$param_width' height='$param_height' alt='$img_path' " . self::create_params($params) . ">";
                return '<img src="' . CONTENTS . '/' . $img_path . '"' . self::create_params($params) . '>';
            }
        }
    }

    public static function image_link($path, $width = 0, $height = 0, $params = [], $resize_larger = true)
    {
        return self::image($path, $width, $height, $params, $resize_larger, true);
    }

    public static function description($desc = null)
    {
        $desc = utils::limit_text(strip_tags($desc), 150);
        $meta = "<meta name='description' content='$desc'>";

        return $meta;
    }


    public static function base($url = null, $target = '_self')
    {
        if($url == null) $url = URL;
        return "<base href='" . $url . "' target='$target'>";
    }

}

?>