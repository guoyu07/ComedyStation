<?php

return array(
    'URL_MODEL' => 2,//URL访问模式
    'URL_ROUTER_ON' => true,//开启路由
    'URL_ROUTE_RULES' => array(
        'user/:id\d' => 'User/Index/jokes',
        'jokes/p/:p' => 'Home/Index/index',
        'hot$' => 'Home/Index/hot',
        'hot/:type' => 'Home/Index/hot',
        'about/:content$' => 'Home/Joke/about',
        'pic' => 'Home/Index/pic',
        'text' => 'Home/Index/text',
        'gif' => 'Home/Index/gif',
        'video' => 'Home/Index/video',
        'hotjoke' => 'Home/Index/hotjoke',
        'joke/:id\d' => 'Home/Index/joke',
        'tags$'=>'Home/Index/showtags',
        'tag/:id\d'=>'Home/Index/tag',
        'godreply'=>'Home/Index/godreply',
//        ''
//        'user/feed'  => 'User/Info/feed',
//        'user/review'  => 'User/Info/review',
//        'user/gift'  => 'User/Info/gift',
//        'user/jokes'  => 'User/Info/jokes',
//        'user/index'  => 'User/Info/jokes',
//        'user/info/uploadify'=>'user/info/uploadify',
//        'user/info/setavatar'  => 'User/Info/setavatar',
//        'user/info'  => 'User/Info/info',
        'user/setpassword' => 'User/Info/setpassword',
        'user/setemail' => 'User/Info/setemail',
        'user/setqq' => 'User/Info/setqq',
        'user/setphone' => 'User/Info/setphone',
//        'user/setavatar'  => 'User/Info/setavatar',
//        'user/:user_id\d'  => 'User/Main/Info',
        'user/login' => 'User/Index/login',
        'user/register' => 'User/Index/register',
        'user/logout' => 'User/Index/logout',
    )//路由规则
);