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
        $this->bind($this->fd, $uid);
        var_dump($this->getUid($this->fd));
        $zone = new \core\Zone();
        $lobby = $zone->getLobbyRoom();
        //上线群发消息
        if($lobby->join($uid)){
            $data = array('f'=>'Chat','a'=>'publish','msg'=>'Welcome '.$uid.' !');
            $this->sendToRoom($lobby->getRoomId(),$data);
        }
        \core\Log::log('login '.$uid.' '.json_encode($this->soc->connection_info($this->fd)));
        $this->send($result);
    }

    public function logout(){
        $userService = new UserService();
        $userService->logout($this->fd);

    }

}