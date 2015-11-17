<?php
/**
 * User: omybug
 * Date: 15-10-19 21:26
 */

namespace core;


class Filter {

    const BEFORE = 1;
    const AFTER  = 2;

    const SEPARATOR = '::';
    private $filters;

    function __construct(){
        $this->filters = Config::get('filters');
    }

    private function trigger($aname, $fname, $serv, $fd, $data = null, $type){
        $filter = $this->getFilter($aname,$fname,$type);
        if(!$filter){
            return false;
        }
        $arg = explode(self::SEPARATOR, $filter);
        $func = $arg[1];
        $action = Route::getAction($arg[0], $func, $data, $fd, $serv);
        if(empty($action)){
            Log::error("filter {$arg[0]}:$func is not found");
            return false;
        }
        $action->$func();
        return true;
    }

    public function doBefore($aname, $fname, $serv, $fd, $data = null){
        $this->trigger($aname, $fname, $serv, $fd, $data = null, self::BEFORE);
    }

    public function doAfter($aname, $fname, $serv, $fd, $data = null){
        $this->trigger($aname, $fname, $serv, $fd, $data = null, self::AFTER);
    }

    private function getFilter($aname,$fname,$type){
        $h = $aname.'::'.$fname;
        foreach($this->filters as $_f){
            if($_f['register'] == $h && $_f['type'] == $type){
                return $_f['action'];
            }
        }
        return false;
    }

}