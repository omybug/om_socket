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
    'lang'  => 'zh-cn',
    'redis' => array('host'=>'127.0.0.1', 'port'=>'6379', 'timeout'=>0.0),
    'mysql' => array('host'=>'127.0.0.1', 'user'=>'root', 'password'=>'000000','dbname'=>'test'),
    //定时器
    'ticks' => array(
//        array('t'=>1, 'time'=>1000,'a'=>'Tick','f'=>'test','d'=>'hello world'),
//        array('t'=>1, 'time'=>2000,'a'=>'Tick','f'=>'test2', 'd'=>'I Love PHP')
    ),
    'timezone' => '',
);