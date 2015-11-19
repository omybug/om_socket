<?php
/**
 * User: omybug
 * Date: 15-10-3 15:29
 */

namespace core;

class Zone{
    private $rooms;
    //最大房间数10000个
    const ROOM_MAX = 10000;
    //大厅房间ID
    const LOBBY_ROOM_ID = 1;
    const TAG = 'zone';

    private $redis;

    function __construct(){
        $this->redis = Redis::instance();
    }

    function createRoom($roomName, $master=0){
        for($i = 1 ; $i < self::ROOM_MAX ; $i++){
            if(!$this->roomExist($i)){
                $room = new Room();
                $room->create($i, $roomName, $master);
                $this->updateRoom($room);
                return $room;
            }
        }
        return false;
    }

    function destoryRoom($roomId){
        Log::debug('destory room ' . $roomId);
        $room = $this->getRoom($roomId);
        if($room){
            $room->destory();
        }
        $this->redis->hDel(Zone::TAG, $roomId);
    }

    /**
     * @param $roomId
     * @return Room|bool
     */
    function getRoom($roomId){
        if($this->roomExist($roomId)){
            $room = new Room();
            return $room->init($this->redis->hGet(Zone::TAG, $roomId));
        }
        echo 'Room is not exist'.PHP_EOL;
        return false;
    }

    /**
     * @param Room|int $arg
     */
    function updateRoom($arg){
        $room = $arg;
        if(is_numeric($arg)){
            $room = $this->getRoom($room);
        }
        if(!empty($room)){
            $this->redis->hSet(Zone::TAG, $room->getRoomId(), json_encode($room->getRoomInfo()));
        }
    }

    /**
     * @return array
     */
    function getRooms(){
        $result = $this->redis->hVals(Zone::TAG);
        $rooms = [];
        foreach($result as $_v){
            $room = new Room();
            array_push($rooms, $room->init($_v));
        }
        return $rooms;
    }

    /**
     * @return Room
     */
    function getLobbyRoom(){
        return $this->getRoom(self::LOBBY_ROOM_ID);
    }

    function getRoomIds(){
        return $this->redis->hKeys(Zone::TAG);
    }

    function getSize(){
        return $this->redis->hLen(Zone::TAG);
    }

    function roomExist($roomId){
        Log::log('roomExist ' . Zone::TAG . $roomId);
        return $this->redis->hExists(Zone::TAG, $roomId);
    }
}
?>