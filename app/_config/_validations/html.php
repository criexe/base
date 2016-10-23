<?php

return [
    'name'     => 'HTML',
    'db_type'  => 'text',

    'html_input' =>

        function($params = [])
        {
            $r = null;
            $r .= form::textarea($params);
            $r .= "<script>set_ckeditor('{$params['name']}', {customConfig : '" . ASSETS . 'ckeditor/full_config.js' . "'})</script>";

            return $r;
        }

];

?>