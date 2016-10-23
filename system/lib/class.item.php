<?php
/**
 * User: mustafa
 * Date: 23.07.2016
 * Time: 17:59
 */


class item
{

    public static $name = 'items';

    public static function get(array $params = [])
    {
        $item = db::get(self::$name, $params);

        $item['user']    = user::get($item['user']);
        $item['title']   = json::decode($item['title']);
        $item['content'] = json::decode($item['content']);

        return $item;
    }


    public static function insert(array $data = [])
    {
        try
        {
            sys::array_key_default_value($data, 'created_at', time());
            sys::array_key_default_value($data, 'status', 'active');

            sys::specify_params($data, [

                'updated_at',
                'user',
                'type',
                'title',
                'content',
                'link',
                'locked',
            ]);

            db::insert(self::$name, $data) or throw_exception(db::error());
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

}