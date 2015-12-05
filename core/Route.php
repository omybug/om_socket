<?php
/**
 * User: omybug
 * Date: 15-10-16 20:21
 */

namespace core;

class Route {

    private static $filter;

    const SEPARATOR = '@';

    function __construct(){

    }

    public static function route($serv, $msg, $fd = -1, $taskId = -1, $fromId = -1){

        if(!is_array($msg)){
            $msg = json_decode($msg,true);
        }

        if(!self::check($msg)){
            Log::error('msg error '. json_encode($msg));
            return false;
        }

        $_route = $msg['controller'];
        $_cons  = explode(Route::SEPARATOR, $_route);
        $_data  = array_key_exists('data', $msg) ? $msg['data'] : null;
        $cname  = $_cons[0];
        $fname  = $_cons[1];


        $controller = self::getController($cname, $fname, $_data, $fd, $serv);
        if(empty($controller)){
            return false;
        }

        if(self::$filter == null){
            self::$filter = new Filter();
        }

        self::$filter->doBefore($_route, $serv, $fd, $_data);

        $result = $controller->$fname();

        if($result){
            if(is_bool($result)){
                self::$filter->doAfter($_route, $serv, $fd, $_data);
            }else{
                self::$filter->doAfter($_route, $serv, $fd, $result);
            }
        }

        return true;
    }

    public static function getController($cname, $fname, $data, $fd, $serv){
        $cname = '\\controller\\'.$cname;

        if(!class_exists($cname)) {
            Log::error('controller '.$cname.' is not exist!');
            return false;
        }

        /**
         * Controller
         */
        $controller = new $cname();

        if(!method_exists($controller,$fname)) {
            Log::error($controller.'.'.$fname.' is not exist!');
            return false;
        }

        $controller->setSoc($serv);
        $controller->setFd($fd);

        //校验数据
        if($controller->validator($fname, $data)){
            $controller->setData($data);
        }else{
            $msg = new Message('','validation failure!','-1');
            $serv->send($fd, $msg);
            return false;
        }

        return $controller;
    }

    private static function check(&$data){

        if(array_key_exists('c', $data)) {
            $_c = explode(Route::SEPARATOR, $data['c']);
            $_c[0] = $_c[0].'Controller';
            $data['controller'] = $_c[0].'@'.(empty($_c[1]) ? 'index' : $_c[1]);
            unset($data['c']);
        }

        if(array_key_exists('d', $data)){
            $data['data'] = $data['d'];
            unset($data['d']);
        }

        if(!array_key_exists('controller', $data)){
            return false;
        }

        return $data;

    }

}