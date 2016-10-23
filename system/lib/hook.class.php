<?php


class hook
{

    public static $hooks = [];

    public static function add($hook = null, $place = null, $function = null)
    {
        global $hooks;

        $hooks[$hook][$place][] = $function;
    }


    public static function listen($event = null, $place = null, $data = null)
    {
        global $hooks;

        if(!array_key_exists($event, $hooks)) return false;
        if(!array_key_exists($place, $hooks[$event])) return false;

        // Execute
        $all_hooks = $hooks[$event][$place];
        foreach($all_hooks as $item)
        {
            $item($data);
        }
    }

}


?>