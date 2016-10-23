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

        if($file == null) $file = 'logs';

        sys::write([
            // 'file' => LOGS_PATH . DS . 'logs.log',
            'file' => LOGS_PATH . DS . $file . $options['ext'],
            'data' => $insert_data . "\n"
        ]);

        cx::counter("log.$file", 1);

        return $insert_data . "\n";
    }


}

?>