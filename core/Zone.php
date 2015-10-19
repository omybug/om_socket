<?php
namespace core;

//http://www.cnblogs.com/weafer/archive/2011/09/21/2184059.html
class Zone{
	private $rooms;
	//最大房间数10000个
	const ROOM_MAX = 10000;
    //大厅房间ID
    const LOBBY_ROOM_ID = 1;

	private $redis;

	private $tag = "zone";
	
	function __construct(){
        $this->redis = Redis::instance();
	}

	function createRoom($roomName, $master=0){
		for($i = 1 ; $i < self::ROOM_MAX ; $i++){
			if(!$this->roomExist($i)){
				$room = new Room();
				$room->create($i, $roomName, $master);
				$this->redis->sAdd($this->tag, $i);
				return $room;
			}
		}
		return false;
	}

	function destoryRoom($roomId){
		if(!$this->roomExist($roomId)){
			echo "$roomId is not exist\n";
			return;
		}
		$room = new Room();
		$room->init($roomId);
		$room->destory();
		$this->redis->sRem($this->tag, $roomId);
	}

    /**
     * @return Room
     */
	function getRoom($roomId){
		if($this->roomExist($roomId)){
			$room = new Room();
			return $room->init($roomId);
		}
		return false;
	}

	function getRooms(){
		$roomIds = $this->getRoomIds();
		$rooms = [];
		foreach($roomIds as $roomId){
			$r = new Room();
			$rooms[] = $r->init($roomId);
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
		return $this->redis->sMembers($this->tag);
	}

	function getSize(){
		return $this->redis->sSize($this->tag);
	}

	function roomExist($roomId){
		return $this->redis->sIsMember($this->tag, $roomId);
	}
}
?>