<?php
/**
 * User: omybug
 * Date: 15-10-5 14:54
 */

namespace core;


class Redis {

    private $redis;
    private static $instance = null;

    function __construct($tag = '', $host = '127.0.0.1', $port = 6379, $timeout = 0.0){
        $this->tag = $tag;
        $redis = new \Redis();
        $redis->connect($host, $port, $timeout);
        $this->redis = $redis;
    }

    public static function instance(){
        if(!isset(self::$instance)){
            $tag = Config::get('app_id');
            $config = Config::get('redis');
            self::$instance = new Redis($tag,$config['host'],$config['port'],$config['timeout']);
        }
        return self::$instance;
    }

    /**
     * @param $args
     * @return mixed
     */
    public function transform(&$args){
        $tag = Config::get('app_id');
        $len = strlen($tag);
        if(is_array($args)){
            foreach($args as &$a){
                $a = substr($a,$len);
            }
        }else{
            $args = substr($args,$len);
        }
        return $args;
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