<?php


class json
{

    public static function encode($data = null)
    {
        return json_encode($data);
    }


    public static function decode($data = null)
    {
        $arr = json_decode($data, true);

        if(is_null($arr) || !$arr) return $data;

        return $arr;
    }

}


?>