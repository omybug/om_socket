<?php
/**
 * User: omybug
 * Date: 15-11-11 19:57
 */
class UserAction extends \core\Action{

    public function createRole(){
        if(empty($this->data['name']) || \core\Util::hasBadWords($this->data['name'])){
            $m = new \core\Message('User','createRole','name is error',-1);
            $this->send($m);
            return;
        }
        $suser = new UserService();
        $m = new \core\Message('User','createRole');
        if($suser->createRole($this->uid, $this->data['name'])){
            $m->setMsg('create ok');
        }else{
            $m->setMsg('create fail');
        }
        $this->send($m);
    }
}