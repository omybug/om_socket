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
        $this->send($result);
    }

    public function logout(){
        $userService = new UserService();
        $userService->logout($this->fd);

    }

}