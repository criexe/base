<?php
/**
 * class.trash.php
 *
 * @author Mustafa Aydemir
 *
 * @email  mustafa@aydemir.im
 * @date   13/12/15
 */


class trash
{

    public static function add($data = [], $name = null, $folder = 'database', $date = true)
    {
        $path = TRASH_PATH . '/' . $folder . '/' . date('d-m-Y');

        if($name == null)
            $file = date('d-m-Y') . '-' . time() . '.trash';
        else
            $file = $name . '-' . date('d-m-Y') . '-' . time() . '.trash';


        $add = sys::write([
            'file' => $path . '/' . $file,
            'data' => json_encode($data)
        ]);

        if(!$add) return false; else return $add;
    }

}
?>