<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/15
 * Time: 17:37
 */


class TickAction extends \core\Action{

    public function test(){
        \core\Log::debug($this->soc->worker_id);
        sleep(10);
        \core\Log::debug(time().' '.$this->soc->worker_id.' '.$this->data);
    }

    public function test2(){
        \core\Log::debug($this->soc->worker_id);
        sleep(10);
        \core\Log::debug(time().' '.$this->soc->worker_id.' '.$this->data);
    }
}