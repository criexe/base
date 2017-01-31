<?php
/**
 * functions.system.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */


/**
 * @param null $message
 * @param null $code
 *
 * @throws Exception
 */
function throw_exception($message = null, $code = null)
{
    logger::add($message);
    throw new Exception($message, $code);
}

?>