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
    public function getIp(){
        $uid = is_numeric($this->data['uid']);
        if($uid < 1){
            return;
        }
        $us = new UserService($this);
        $fd = $us->getBindFd($uid);
        return parent::getIp($fd);
    }
} 