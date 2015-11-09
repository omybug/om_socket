<?php
/**
 * User: omybug
 * Date: 15-11-1 15:29
 */

class RoomAction extends \core\Action{

    private $zone;

    function __construct(){
        $this->zone = new \core\Zone();
    }

    public function join(){
        $roomId = intval($this->data['roomId']);
        $room = $this->zone->getRoom($roomId);
        if(empty($room)){
            $msg = new \core\Message('Room','create','room is not exit', 1);
            $this->send($msg);
            return;
        }
        \core\Log::debug(' -RoomAction- join ' . $this->uid);
        $room->join($this->uid);
        return;
    }

    public function create(){
        $name = $this->data['roomName'];
        if(\core\Util::hasBadWords($name) || \core\Util::hasSpecialChar($name)){
            $msg = new \core\Message('Room','create','name has bad words', 1);
            $this->send($msg);
            return;
        }
        $room = $this->zone->createRoom($name);
        \core\Log::debug(' -RoomAction- create ' . $this->uid);
        $room->join($this->uid);
        $msg = new \core\Message('Room','create',$room->getRoomInfo());
        $this->send($msg);
    }

    public function leave(){
        $roomId = intval($this->data['roomId']);
        $room = $this->zone->getRoom($roomId);
        if(empty($room)){
            $msg = new \core\Message('Room','create','room is not exit', 1);
            $this->send($msg);
            return;
        }
        $room->leave($this->uid);
    }

    public function getRooms(){
        $rooms = $this->zone->getRooms();
        $msg = new \core\Message('Room','getRooms',$rooms);
        $this->send($msg);
    }

    public function getMembers(){
        $roomId = intval($this->data['roomId']);
        $room = $this->zone->getRoom($roomId);
        $users = array();
        if(!empty($room)){
            $users = $room->getUsers();
        }
        $msg = new \core\Message('Room','getMemebers',$users);
        $this->send($msg);
    }
} 