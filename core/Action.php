<?php
/**
 * User: omybug
 * Date: 15-10-5 22:26
 */

namespace core;

class Action {

    protected $soc;
    protected $fd;
    protected $data;

    function __construct($soc, $fd, $data){
        $this->soc = $soc;
        $this->fd = $fd;
        $this->data = $data;
    }

    public function send($data){
        return $this->soc->send($this->fd, json_encode($data));
    }


    public function sendToRoom(){

    }

    public function sendToUsers($fds, $data){
        foreach($fds as $fd){
            $this->sendToUser($fd, $data);
        }
    }

    public function sendToUser($fd, $data){
        $this->soc->send($fd, json_encode($data));
    }

    public function close($fd){
        $this->soc->close($fd);
    }

    public function exist($fd){
        return $this->soc->exist($fd);
    }

} 