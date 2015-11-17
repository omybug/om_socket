<?php
/**
 * User: omybug
 * Date: 15-10-16 20:21
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
        $aname = $data['a'];
        $func = $data['f'];
        $d = array_key_exists('d', $data) ? $data['d'] : null;

        $action = self::getAction($aname, $func, $d, $fd, $serv);
        if(empty($action)){
            return false;
        }

        $filter = new Filter();
        $filter->doBefore($aname, $func, $serv, $fd, $d);
        $result = $action->$func();
        if($result){
            if(is_bool($result)){
                $filter->doAfter($aname, $func, $serv, $fd, $d);
            }else{
                $filter->doAfter($aname, $func, $serv, $fd, $result);
            }
        }
        return true;
    }

    public static function getAction($aname, $func, $data, $fd, $serv){
        $aname = $aname.'Action';
        if(!class_exists($aname)) {
            Log::error('action '.$aname.' is not exist!');
            return false;
        }
        $action = new $aname();
        if(!method_exists($action,$func)) {
            Log::error($action.'.'.$func.' is not exist!');
            return false;
        }
        $action->setSoc($serv);
        if($fd > 0){
            $action->setFd($fd);
        }
        if(!empty($data)){
            $action->setData($data);
        }
        return $action;
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