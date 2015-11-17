<?php
/**
 * User: omybug
 * Date: 15-10-5 15:00
 */

return array(
    //项目ID
    'app_id' => '1000',
    'is_debug'=>true,
    'server' => array('host'=>'127.0.0.1', 'port'=>'9000'),
    'timezone' => '',
    'lang'  => 'zh-cn',
    'redis' => array('host'=>'192.168.100.105', 'port'=>'6379', 'timeout'=>0.0),
    'mysql' => array('host'=>'192.168.100.105', 'user'=>'root', 'password'=>'000000','dbname'=>'test'),
    //定时器
    'ticks' => array(
        //每60秒执行
        array('t'=>1, 'time'=>60, 'a'=>'Tick', 'f'=>'heartbeat'),
        //每日定时执行
        array('t'=>2, 'time'=>3600*14,'a'=>'Tick','f'=>'announce', 'd'=>'I Love PHP')
    ),
    'filters' => array(
        array('register'=>'Shop::buy','action'=>'Test::hook1','type'=>\core\Filter::BEFORE),
        array('register'=>'Shop::buy','action'=>'Test::hook2','type'=>\core\Filter::AFTER),
    ),
);