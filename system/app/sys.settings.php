<?php


cx::type([

    'alias'   => 'user',
    'name'    => 'User',
    'columns' => ['username', 'password', 'email', 'permissions', 'authority'],
    'form'    => 'user',
    'layout'  => 'user',
    'sitemap' => true
]);


cx::type([

    'alias'   => 'category',
    'name'    => 'Category',
    'form'    => 'category',
    'sitemap' => true
]);


cx::type([

    'alias'   => 'tag',
    'name'    => 'Tag',
    'sitemap' => true
]);

?>