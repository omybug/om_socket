<?php
/**
 * User: omybug
 * Date: 15-10-12 16:38
 */

namespace controller;

use core\Log;
use core\Message;
use core\Zone;
use service\UserService;

class HomeController extends Controller{

    public function login(){
        $userService = new UserService($this);
        $result = $userService->login($this->data['account'], $this->data['password'], $this->fd);
        if($result == -2){
            $userService->register($this->data['account'], $this->data['password']);
            $result = $userService->login($this->data['account'], $this->data['password'], $this->fd);
        }
        if($result != -2){

        }
        $uid = $result['id'];
        $zone = new Zone();
        $lobby = $zone->getLobbyRoom();
        if(empty($lobby)){
            Log::debug('lobby room is null');
            return false;
        }
        //上线群发消息
        if($lobby->join($uid)){
            $msg = new Message('Chat@publish','Welcome '.$result['account'].' !');
            $this->sendToRoom($lobby->getRoomId(),$msg);
        }
        $msg = new Message('Home@login',$result);
        $this->send($msg);
    }

    public function logout(){
        $userService = new UserService();
        $userService->logout($this->fd);
    }

}