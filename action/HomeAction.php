<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/12
 * Time: 16:38
 */

class HomeAction extends \core\Action{

    public function login(){
        $userService = new UserService();
        $userService->login($this->data['account'], $this->data['password']);
    }

}