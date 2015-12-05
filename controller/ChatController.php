<?php
/**
 * User: omybug
 * Date: 15-10-18 14:52
 */

namespace controller;

use core\Message;
use core\Util;
use core\Zone;
use service\AdminService;

class ChatController extends Controller{

    function __construct(){

    }

    public function setData($data){
        $this->data = $data;
        if(array_key_exists('msg',$this->data)){
            $this->data['msg'] = Util::filterWords($this->data['msg']);
        }
        return $this;
    }

    public function publish(){
        if($this->isBan()){
            $msg = new Message('Chat@publish',array('msg'=>'ban chat'));
            $this->send($msg);
            return;
        }
        $str = Util::filterWords($this->data['msg']);
        $msg = new Message('Chat@publish',array('msg'=>$str));
        $this->sendToRoom(Zone::LOBBY_ROOM_ID,$msg);
    }

    /**
     * @return bool
     */
    private function isBan(){
        $as = new AdminService();
        if($as->isBanChat(array('uid'=>$this->uid))){
            return true;
        }
        if($as->isBanChat(array('ip'=>$this->getIp()))){
            return true;
        }
        return false;
    }
}