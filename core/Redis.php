<?php
/**
 * User: omybug
 * Date: 15-10-5 14:54
 */

namespace core;


class Redis {

    private $redis;
    private static $instance;
    private $tag;

    function __construct($tag = '', $host = '127.0.0.1', $port = 6379, $timeout = 0.0){
        $this->tag = $tag;
        $redis = new \Redis();
        $redis->connect($host, $port, $timeout);
        $this->redis = $redis;
    }

    public static function instance(){
        if(!isset(self::$instance)){
            self::$instance == new \core\Redis();
        }
        return self::$instance;
    }

    function __call($func, $args){
        if(empty($args)){
            return $this->redis->$func();
        }
        $argsNum = count($args);
        if($argsNum == 1){
            return $this->redis->$func($this->tag.$args[0]);
        }
        if($argsNum == 2){
            return $this->redis->$func($this->tag.$args[0], $args[1]);
        }
        if($argsNum == 3){
            return $this->redis->$func($this->tag.$args[0], $args[1],$args[2]);
        }
        Log::error("redis func $func is error ".json_decode($args));
        return false;
    }
} 