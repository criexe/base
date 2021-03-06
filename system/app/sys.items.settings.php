<?php


cx::type([

    'alias'   => 'user',
    'name'    => 'User',
    'columns' => ['username', 'password', 'email', 'permissions', 'authority'],
    'form'    => 'user',
    'layout'  => 'app',
    'sitemap' => true
]);


cx::type([

    'alias'   => 'category',
    'name'    => 'Category',
    'form'    => 'category',
    'layout'  => 'app',
    'sitemap' => true
]);


cx::type([

    'alias'   => 'tag',
    'name'    => 'Tag',
    'layout'  => 'app',
    'sitemap' => true
]);


cx::type([

    'alias'   => 'comment',
    'title'   => 'Comment',
    'columns' => ['for'],
    'notification.insert' => false
]);


cx::type([

    'alias'   => 'redirect',
    'title'   => 'Redirect',
    'form'    => 'redirect'
]);


cx::type([
    
    'alias' => 'page',
    'title' => 'Page'
]);