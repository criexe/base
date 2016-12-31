<?php


class utils
{

    public static function limit_text($str = null, $len = 100)
    {
        $str = substr($str, 0, $len);

        return $str;
    }


    public static function limit_words($str = null, $count = 10)
    {
        $words = explode(' ', $str);

        if(count($words) > $count)
        {
            array_splice($words, $count);

            return "<span data-title='$str'>" . implode(' ', $words) . '...</span>';
        }
        else
        {
            return $str;
        }
    }


    public static function count_words($str = null)
    {
        $words = explode(' ', $str);

        return count($words);
    }


    public static function compress_html($buffer)
    {
        $search  = ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'];
        $replace = ['>', '<', '\\1'];

        //    if(preg_match("/\<html/i", $buffer) == 1 && preg_match("/\<\/html\>/i", $buffer) == 1)
        //    {
        //        $buffer = preg_replace($search, $replace, $buffer);
        //    }

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }


    public static function images_add_alt_tags($content = null)
    {
        $title = cx::title();

        preg_match_all('/<img (.*?)\/>/', $content, $images);

        if(!is_null($images))
        {
            foreach($images[1] as $index => $value)
            {
                if(!preg_match('/alt=/', $value))
                {
                    $new_img = str_replace('<img', '<img alt="' . $title . '"', $images[0][$index]);
                    $content = str_replace($images[0][$index], $new_img, $content);
                }
            }
        }
        return $content;
    }

    public static function ucfirst($string, $encoding = 'UTF-8')
    {
        $strlen    = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then      = mb_substr($string, 1, $strlen - 1, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $then;
    }

}


?>