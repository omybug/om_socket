<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/12
 * Time: 16:38
 */

class HomeAction extends \core\Action{

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
        $zone = new \core\Zone();
        $lobby = $zone->getLobbyRoom();
        //上线群发消息
        if($lobby->join($uid)){
            $msg = new \core\Message('Chat','publish','Welcome '.$result['account'].' !');
            $this->sendToRoom($lobby->getRoomId(),$msg);
        }
        $msg = new \core\Message('Home','login',$result);
        $this->send($msg);
    }

    public function logout(){
        $userService = new UserService();
        $userService->logout($this->fd);
    }

}