<?php
/**
 * User: omybug
 * Date: 2015/10/15
 * Time: 17:37
 */

namespace controller;

use core\Zone;
use core\Log;
use service\UserService;

class TickController extends Controller{

    /**
     * 定时发送消息
     */
    public function announce(){
        $data = array('f'=>'Chat','a'=>'publish','msg'=>$this->data);
        $this->sendToRoom(Zone::LOBBY_ROOM_ID,$data);
    }

    /**
     *
     */
    public function heartbeat(){
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
        Log::debug($this->soc->worker_id);
        sleep(10);
        Log::debug(time().' '.$this->soc->worker_id.' '.$this->data);
    }

    public function test2(){
        Log::debug($this->soc->worker_id);
        sleep(10);
        Log::debug(time().' '.$this->soc->worker_id.' '.$this->data);
    }
}