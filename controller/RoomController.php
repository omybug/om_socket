<?php
/**
 * User: omybug
 * Date: 15-11-1 15:29
 */

namespace controller;


use core\Log;
use core\Message;
use core\Util;
use core\Zone;

class RoomController extends Controller{

    private $zone;

    function __construct(){
        $this->zone = new Zone();
    }

    public function join(){
        $roomId = intval($this->data['roomId']);
        $room = $this->zone->getRoom($roomId);
        if(empty($room)){
            $msg = new Message('Room@create','room is not exit', 1);
            $this->send($msg);
            return;
        }
        Log::debug(' -RoomAction- join ' . $this->uid);
        $room->join($this->uid);
        return;
    }

    public function create(){
        $name = $this->data['roomName'];
        if(Util::hasBadWords($name) || Util::hasSpecialChar($name)){
            $msg = new Message('Room@create','name has bad words', 1);
            $this->send($msg);
            return;
        }
        $room = $this->zone->createRoom($name);
        Log::debug(' -RoomAction- create ' . $this->uid);
        $room->join($this->uid);
        $msg = new Message('Room@create',$room->getRoomInfo());
        $this->send($msg);
    }

    public function leave(){
        $roomId = intval($this->data['roomId']);
        $room = $this->zone->getRoom($roomId);
        if(empty($room)){
            $msg = new Message('Room@create','room is not exit', 1);
            $this->send($msg);
            return;
        }
        $room->leave($this->uid);
    }

    public function getRooms(){
        $rooms = $this->zone->getRooms();
        $msg = new Message('Room@getRooms',$rooms);
        $this->send($msg);
    }

    public function getMembers(){
        $roomId = intval($this->data['roomId']);
        $room = $this->zone->getRoom($roomId);
        $users = array();
        if(!empty($room)){
            $users = $room->getUsers();
        }
        $msg = new Message('Room@getMemebers',$users);
        $this->send($msg);
    }

} 