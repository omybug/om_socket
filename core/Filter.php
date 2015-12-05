<?php
/**
 * User: omybug
 * Date: 15-10-19 21:26
 */

namespace core;


class Filter {

    const BEFORE = 1;
    const AFTER  = 2;

    private $filters;

    function __construct(){
        $this->filters = Config::get('filters');
    }

    public function doBefore($hook, $serv, $fd, $data = null){
        $this->trigger($hook, $serv, $fd, $data = null, self::BEFORE);
    }

    public function doAfter($hook, $serv, $fd, $data = null){
        $this->trigger($hook, $serv, $fd, $data = null, self::AFTER);
    }

    private function trigger($hook, $serv, $fd, $data = null, $type){

        $filters = $this->getFilters($hook,$type);

        if(empty($filters)){
            return false;
        }

        foreach($filters as $filter){
            $arg = explode(Route::SEPARATOR, $filter);
            $cname = $arg[0];
            $fname = $arg[1];
            $controller = Route::getController($cname, $fname, $data, $fd, $serv);
            if(empty($controller)){
                Log::error("filter $arg is not found");
                return false;
            }
            $controller->$fname();
        }

        return true;
    }

    private function getFilters($hook,$type){
        $filters = array();
        foreach($this->filters as $_f){
            if($_f['hook'] == $hook && $_f['type'] == $type){
                array_push($filters, $_f['controller']);
            }
        }
        return $filters;
    }

}