<?php
/**
 * User: omybug
 * Date: 15-10-5 22:26
 */

namespace core;

class Action {

    /**
     * @var Log|null
     */
    protected $log;

    protected $soc;
    protected $fd;
    protected $data;
    protected $uid;

    function __construct(){
        $this->log = Log::instance();
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
        $this->fd = $fd;
        $us = new \UserService();
        $this->uid = $us->getBindUid($fd);
        return $this;
    }

    public function setData($data){
        $this->data = $data;
        return $this;
    }

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
        $us = new \UserService();
        $fds = $us->getBindFd($userIds);
        $this->sendToUsers($fds, $data);
    }

    /**
     * @param $fds
     * @param Message $data
     */
    public function sendToUsers($fds, $data){
        foreach($fds as $fd){
            $this->sendToUser($fd, $data);
        }
    }

    /**
     * @param $fd
     * @param Message $data
     */
    public function sendToUser($fd, $data){
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

} 