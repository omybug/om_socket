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

    /**
     * @param $action Action
     */
    function __construct($action){
        if(!empty($action)){
            $this->action = $action;
        }
    }
}
?>