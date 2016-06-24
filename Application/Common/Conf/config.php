<?php
return array(
    'SHOW_PAGE_TRACE' => false,
    'LOAD_EXT_CONFIG' => 'db,route,const',
    'OUTPUT_ENCODE' => true,            //页面压缩输出
    'PAGE_NUM' => 15,
    'URL_HTML_SUFFIX' => '.html',
    'COOKIE_PATH' => '/',            // Cookie路径
    'COOKIE_PREFIX' => '',            // Cookie前缀 避免冲突
    'SESSION_EXPIRE' => 600,
    'PAGE_SIZE' => 10,
    'LAYOUT_ON' => true, //开启模板
    'LAYOUT_NAME' => 'layout',
    'REVIEW_STATUS_UNAUDIT'=>2,
    'USER_DEFAULT_MONEY' => 100,
    'USER_STATUS_UNACTIVATE'=>2,
    'TMPL_PARSE_STRING' => array(                //更改系统的模版的常量
        '__PUBLIC__' => '',
    )
);