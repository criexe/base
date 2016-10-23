<?php


class response
{

    public static function ajax($data = [])
    {
        sys::specify_params($data, ['status', 'message']);
        sys::array_key_default_value($data, 'location', false);

        return json::encode($data, ['pretty' => true]);
    }

}


?>