<?php


class json
{

    public static function encode($data = null, $params = [])
    {
        sys::specify_params($params, ['pretty']);

        $json = null;

        if($params['pretty'] === true)
        {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $json = str_replace(["\n", "\r"], null, $json);
        $json = trim($json);

        return $json;
    }


    public static function decode($data = null)
    {
        $data = str_replace(["\n", "\r"], null, $data);
        $data = trim($data);
        $arr  = json_decode($data, true);

        if(is_null($arr) || !$arr) return $data;

        return $arr;
    }


    public static function valid($data)
    {
        $data = str_replace(["\n", "\r"], null, $data);
        $data = trim($data);

        $arr = json_decode($data, true);
        if(is_null($arr) || !$arr) return false; else return true;
    }

}


?>