<?php
/**
 * User: omybug
 * Date: 15-10-5 22:26
 */

namespace core;

use service\UserService;

abstract class BaseController {

    /**
     * @var Log|null
     */
    protected $log;

    protected $soc;
    protected $fd;
    protected $data;
    protected $uid;
    private $userManager;

    function __construct(){
        $this->log = Log::instance();
        $this->userManager = UserManager::instance();
    }

//    function __construct($soc = null, $fd = null , $data = null){
//        $this->soc = $soc;
//        $this->fd = $fd;
//        $this->data = $data;
//    }

    public function setSoc($soc){
        $this->soc = $soc;
        return $this;
    }

    public function setFd($fd){
        if($fd < 1){
            return;
        }
        $this->fd = $fd;
        $this->uid = $this->userManager->getBindUid($fd);
        return $this;
    }

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public abstract function validator($fname, $data);

    /**
     * @param Message $data
     * @return mixed
     */
    public function send($data){
        return $this->soc->send($this->fd, $data);
    }

    /**
     * @param $roomId
     * @param Message $data
     */
    public function sendToRoom($roomId, $data){
        $zone = new Zone();
        $room = $zone->getRoom($roomId);
        $userIds = $room->getUsers();
        $fds = $this->userManager->getBindFd($userIds);
        $this->sendToUsers($fds, $data);
    }

    /**
     * @param $fds
     * @param Message $data
     */
    public function sendToUsers($fds, $data){
        if(empty($data) || empty($fds)){
            return;
        }
        foreach($fds as $fd){
            $this->sendToUser($fd, $data);
        }
    }

    /**
     * @param $fd
     * @param Message $data
     */
    public function sendToUser($fd, $data){
        if(empty($data)){
            return;
        }
        $this->soc->send($fd, $data);
    }

    public function close($fd){
        $this->soc->close($fd);
    }

    public function exist($fd){
        return $this->soc->exist($fd);
    }

    public function bind($fd, $uid){
        $this->soc->bind($fd, $uid);
    }

    public function getUid($fd){
        return $this->soc->connection_info($this->fd);
    }

    public function getIp($fd = null){
        if(!empty($fd)){
            if($fd < 1){
                return null;
            }
            $fdinfo = $this->soc->connection_info($fd);
        }else{
            $fdinfo = $this->soc->connection_info($this->fd);
        }
        if(array_key_exists(remote_ip,$fdinfo)){
            return $fdinfo['remote_ip'];
        }
        return null;
    }

} 