<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/19
 * Time: 14:52
 */

class ChatAction extends \core\Action{

    function __construct(){


    }

    public function setData($data){
        $this->data = $data;
        if(array_key_exists('msg',$this->data)){
            $this->data['msg'] = \core\Util::filterWords($this->data['msg']);
        }
        return $this;
    }

    public function publish(){
//        $zs = new \core\Zone();
//        $lobby = $zs->getLobbyRoom();
//        var_dump($lobby->getUsers());
//        $this->sendToRoom($lobby->getRoomId(),$this->data['msg']);
    }
}