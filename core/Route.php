<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/16
 * Time: 17:21
 */

namespace core;


class Route {

    function __construct(){

    }

    public static function route($serv, $msg, $fd = -1, $taskId = -1, $fromId = -1){
        if(is_array($msg)){
            $data = $msg;
        }else{
            $data = json_decode($msg,true);
        }
        if(!self::check($data)){
            Log::error('msg error '. $msg);
            return false;
        }
        $action = $data['a'].'Action';
        $func = $data['f'];
        if(class_exists($action)) {
            $action = new $action();
            $action->setSoc($serv);
            if($fd > 0){
                $action->setFd($fd);
            }
            if(array_key_exists('d', $data)){
                $action->setData($data['d']);
            }
            if(method_exists($action,$func)){
                $action->$func();
            }else{
                Log::error($action.'.'.$func.' is not exist!');
            }
        }else{
            Log::error('action '.$action.' is not exist!');
        }
    }


    private static function check($data){
        if(!array_key_exists('a', $data)){
            return false;
        }
        if(!array_key_exists('f', $data)){
            return false;
        }
        return true;
    }


}