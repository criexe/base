<?php

/**
 * home.php
 *
 * @author Mustafa Aydemir
 * @date   15.10.15
 */

class controller_home extends controller
{


    function __construct() {}



    function index ()
    {
        item::insert();
    }

}

?>
