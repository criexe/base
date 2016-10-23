<?php
/**
 * functions.system.php
 *
 * @author Mustafa Aydemir
 * @date   14.10.15
 */


// Throw Exception
function throw_exception($message = null, $code = null)
{
    logger::add($message);
    throw new Exception($message, $code);
}


// Error Handler
function error_handler($error_no, $error_message, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) return;

    $msg = null;

    switch ($error_no)
    {
        case E_USER_ERROR:

            error::load_page('error.php');
            break;

        case E_USER_WARNING:

            $msg = "<strong>:Warning</strong> [$error_no] $error_message<br />\n";
            break;

        case E_USER_NOTICE:

            $msg = "<strong>:Notice</strong> [$error_no] $error_message<br />\n";
            break;

        default:

            $msg = "<strong>Error:</strong> [$error_no] $error_message<br />\n";
            break;
    }

    logger::add("[$error_no] $error_message");
    echo "[$error_no] $error_message";

    return true;
}
//set_error_handler('error_handler', E_ALL);


/**
 * @param $buffer
 *
 * @return mixed
 */
function compress_output($buffer)
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

?>