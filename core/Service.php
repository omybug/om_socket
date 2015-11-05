<?php

namespace core;

/**
 * Class Service
 */
class Service{
    /**
     * @var Action
     */
    protected $action;

    protected $log;

    /**
     * @param $action Action
     */
    function __construct($action){
        if(!empty($action)){
            $this->action = $action;
        }
        $this->log = Log::instance();
    }
}
?>