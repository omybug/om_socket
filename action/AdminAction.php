<?php
/**
 * User: omybug
 * Date: 15-11-9 21:34
 */
class AdminAction extends \core\Action{

    private $as;

    function __construct(){
        parent::__construct();
        $this->as = new AdminService();
    }

    /**
     * 禁言
     */
    public function banChat(){
        $this->as->banChat($this->data);
    }

    /**
     * 禁游戏
     */
    public function banGame(){
        $this->as->banGame($this->data);
    }

    /**
     * 解除禁言
     */
    public function unBanChat(){
        $this->as->unBanGame($this->data);
    }

    /**
     * 解禁账号
     */
    public function unBanGame(){
        $this->as->unBanGame($this->data);
    }

    /**
     * 用户IP
     * @return null|string ip
     */
    public function findIp(){
        $uid = is_numeric($this->data['uid']);
        if($uid < 1){
            return;
        }
        $us = new UserService($this);
        $fd = $us->getBindFd($uid);
        $m = new \core\Message('Admin','getIp',array('ip'=>$this->getIp($fd)));
        $this->send($m);
    }

    /**
     * 重启Task进程
     */
    public function reload(){
        //重启task进程
        $result = $this->soc->reload(false);
        $m = new \core\Message('Admin','reload',array('msg'=>'Server Reload '.$result?'OK':'Fail'));
        $this->send($m);
        return;
    }
}