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

}


?>