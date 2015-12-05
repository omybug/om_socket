<?php
/**
 * development environment
 */
return [
	'app_id' 	=> '1000',
	'is_debug'	=> true,
    'timezone' 	=> '',
    'lang'   	=> 'zh-cn',
    'token'     => '000000',
    'route_type'=> 1,   //路由类型，1：直接匹配，2：通过routes.php配置执行
    'server' 	=> ['host'=>'127.0.0.1', 'port'=>'9000'],
    'redis'  	=> ['host'=>'127.0.0.1', 'port'=>'6379', 'timeout'=>0.0],
    'mysql'  	=> ['host'=>'127.0.0.1', 'user'=>'root', 'password'=>'','dbname'=>'test'],
];
