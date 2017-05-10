<?php


class net
{

    public function connect($params = [])
    {
        sys::specify_params($params, ['url', 'referer', 'cookie_file']);

        if($params['url'] == null) return false;

        $default_user_agent = _config('net.user_agent');

        sys::array_key_default_value($params, 'user_agent', $default_user_agent);
        sys::array_key_default_value($params, 'header', false);
        sys::array_key_default_value($params, 'return_transfer', true);
        sys::array_key_default_value($params, 'ssl', true);
        sys::array_key_default_value($params, 'follow_location', true);
        sys::array_key_default_value($params, 'timeout', 300);
        sys::array_key_default_value($params, 'connect_timeout', 50);
        sys::array_key_default_value($params, 'safe_upload', true);

        sys::array_key_default_value($params, 'post', 0);
        sys::array_key_default_value($params, 'data', []);

        if($params['post'] == true) $params['post'] = 1;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $params['url']);
        curl_setopt($ch, CURLOPT_USERAGENT, $default_user_agent);
        curl_setopt($ch, CURLOPT_HEADER, $params['header']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $params['return_transfer']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $params['ssl']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $params['follow_location']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $params['timeout']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $params['connect_timeout']);

        if($params['referer'] != null) curl_setopt($ch, CURLOPT_REFERER, $params['referer']);

        if($params['post'] == 1)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params['data']);
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, $params['safe_upload']);
        }

        if($params['cookie_file'] != null)
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $params['cookie_file']);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $params['cookie_file']);
        }

        $code     = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $info     = curl_getinfo($ch);

        return [

            'content'  => $code,
            'httpcode' => $httpcode,
            'info'     => $info
        ];
    }

}


?>