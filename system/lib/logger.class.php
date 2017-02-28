<?php
/**
 * class.log.php
 *
 * @author Mustafa Aydemir
 * @date   15.10.15
 */

class logger
{

    /**
     * @param null $message
     */
    public static function add($data = null, $file = null, $options = [])
    {
        _log($data, $file, $options);
    }


}

?>