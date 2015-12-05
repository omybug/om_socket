<?php
/**
 * User: omybug
 * Date: 15-11-11 19:57
 */

namespace controller;

use service\UserService;
use core\Message;
use core\Util;

class UserController extends Controller{

    public function createRole(){
        if(empty($this->data['name']) || Util::hasBadWords($this->data['name'])){
            $m = new Message('User@createRole','name is error',-1);
            $this->send($m);
            return;
        }
        $suser = new UserService();
        $m = new Message('User@createRole');
        if($suser->createRole($this->uid, $this->data['name'])){
            $m->setMsg('create ok');
        }else{
            $m->setMsg('create fail');
        }
        $this->send($m);
    }

}