<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/15
 * Time: 17:37
 */


class TickAction extends \core\Action{

    /**
     * 定时发送消息
     */
    public function announce(){
        $data = array('f'=>'Chat','a'=>'publish','msg'=>$this->data);
        $this->sendToRoom(\core\Zone::LOBBY_ROOM_ID,$data);
    }

    /**
     *
     */
    public function heartbeat(){
        $this->log->log('heartbeat');
        $closeFds = $this->soc->heartbeat();
        if(empty($closeFds)){
            return;
        }
        $this->log->log('heartbeat:'.json_encode($closeFds));
        $us = new UserService();
        foreach($closeFds as $fd){
            $us->offline($fd);
        }
    }

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