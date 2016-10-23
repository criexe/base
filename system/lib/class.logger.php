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
    public static function add($data = null, $folder = null, $options = [])
    {
        sys::array_key_default_value($options, 'date', true);
        sys::array_key_default_value($options, 'ext', '.log');
        sys::array_key_default_value($options, 'filename', 'log-' . date('d-m-Y'));
        sys::array_key_default_value($options, 'folder', date('m-Y'));

        if($options['date'] === false)
        {
            $insert_data = $data;
        }
        else
        {
            $date = date(sys::get_config('application')['date_pattern']);
            $insert_data = "[{$date}] {$data}";
        }

        sys::create_folder(LOGS_PATH . DS . $options['folder']);

        sys::write([
            //'file' => LOGS_PATH . DS . trim($folder . DS . $options['folder'], '/') . DS . $options['filename'] . $options['ext'],
            'file' => LOGS_PATH . DS . 'logs.log',
            'data' => $insert_data . "\n\n"
        ]);

        return $insert_data . "\n";
    }


}

?>