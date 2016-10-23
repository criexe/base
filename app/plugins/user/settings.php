<?php


sitemap::set([

    'table'       => 'users',

    'url'         => '/user/profile/{user}',
    'priority'    => 1,
    'changefreq'  => 'weekly',
    'lastmod'     => 'update_date'
]);


?>