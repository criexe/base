<?php


class is
{

    public static function posted()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') return true; else return false;
    }


}


?>