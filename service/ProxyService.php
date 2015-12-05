<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/4
 * Time: 17:46
 */

namespace service;


use core\Redis;

class ProxyService extends Service{
    private $redis;
    function __construct(){
        $this->redis = Redis::instance();
    }

    public function register($fd, $ip){

    }

}