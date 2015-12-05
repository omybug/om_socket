<?php
/**
 * User: omybug
 * Date: 15-10-5 22:13
 */

namespace core;

/**
 * Class BaseService
 */
class BaseService{

    /**
     * @var BaseController
     */
    protected $controller;

    protected $log;

    function __construct(BaseController $controller = null){
        if(!empty($controller)){
            $this->controller = $controller;
        }
        $this->log = Log::instance();
    }

    protected function begin(){
        DB::instance()->begin();
    }

    protected function commit(){
        DB::instance()->commit();
    }

    protected function rollback(){
        DB::instance()->rollback();
    }

}
?>