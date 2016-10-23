<?php

// CSS Files
asset::css('https://fonts.googleapis.com/css?family=Roboto:400,300,100,500&subset=latin,latin-ext');
asset::css('https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext');
asset::css('https://fonts.googleapis.com/css?family=Ubuntu:400,700,300|Open+Sans:400,300,700');

asset::css(ASSETS . '/bootstrap/css/bootstrap.min.css');
asset::css(ASSETS . '/default/css/settings.css');
asset::css(ASSETS . '/fullpage/jquery.fullpage.min.css');

asset::css(ASSETS . '/default/css/style.css');
asset::css(ASSETS . '/default/css/functions.css');
asset::css(ASSETS . '/default/css/profile.css');
asset::css(ASSETS . '/default/css/sign-io.css');
asset::css(ASSETS . '/default/css/user.css');

asset::css(ASSETS . '/perfect-scrollbar/css/perfect-scrollbar.css');
asset::css(ASSETS . '/tags/jquery.tagsinput.min.css');

asset::css(ASSETS . '/emoji/css/emojione.min.css');
asset::css(ASSETS . '/emoji/css/emojione-awesome.css');


// JS Header Files
asset::js_header(ASSETS . '/jquery/jquery-2.1.4.min.js');
asset::js_header(ASSETS . '/jquery/jquery.form.js');
asset::js_header(ASSETS . '/default/js/default.js');

asset::js_header(ASSETS . '/perfect-scrollbar/js/perfect-scrollbar.jquery.js');
asset::js_header(ASSETS . '/tags/jquery.tagsinput.min.js');
asset::js_header(ASSETS . '/default/js/functions.js');
asset::js_header(ASSETS . '/load-more/js/load_more.js');

asset::js_footer(ASSETS . '/app/opt.js');
asset::js_footer(ASSETS . '/default/js/script.js');
asset::js_footer(ASSETS . '/fullpage/jquery.fullpage.min.js');
asset::js_footer(ASSETS . '/bootstrap/js/bootstrap.min.js');
asset::js_footer(ASSETS . '/default/js/ajax.js');




?>