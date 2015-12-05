<?php
/**
 * production environment
 */
return [
    'app_id' 	=> '1000',
    'is_debug'	=> false,
    'timezone' 	=> '',
    'lang'   	=> 'zh-cn',
    'token'     => '000000',
    'server' 	=> ['host'=>'127.0.0.1', 'port'=>'9000'],
    'redis'  	=> ['host'=>'127.0.0.1', 'port'=>'6379', 'timeout'=>0.0],
    'mysql'  	=> ['host'=>'127.0.0.1', 'user'=>'root', 'password'=>'000000','dbname'=>'test'],
];
