<?php

function debug($msg){
    echo $msg.PHP_EOL;
}

function login(swoole_client $cli){
    $data = array('a'=>'Home','f'=>'login','d'=>array('account'=>'test2','password'=>'000000'));
    $cli->send(json_encode($data));
    debug('Login');
}

function publish(swoole_client $cli){
    //发公共消息
    $data = array('a'=>'Chat','f'=>'publish','d'=>array('msg'=>'test'));
    $cli->send(json_encode($data));
    debug('Chat publish');
}

function createRoom(swoole_client $cli){
    //创建房间
    $data = array('a'=>'Room','f'=>'create','d'=>array('roomName'=>'R1'));
    $cli->send(json_encode($data));
    debug('Create Room');
}

function joinRoom(swoole_client $cli){
    $data = array('a' => 'Room', 'f' => 'join', 'd' => array('roomId' => '2'));
    $cli->send(json_encode($data));
    debug('Join Room');
}

function leaveRoom(swoole_client $cli){
    $data = array('a' => 'Room', 'f' => 'leave', 'd' => array('roomId' => '2'));
    $cli->send(json_encode($data));
    debug('Leave Room');
}

$mark = 0;
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
$client->on("connect", function(swoole_client $cli) {
    login($cli);

});
$client->on("receive", function(swoole_client $cli, $data) use (&$mark){
    debug($data);
    if($mark == 1) {
        createRoom($cli);
    }
    if($mark == 2){
        joinRoom($cli);
    }
    if($mark == 3){
        leaveRoom($cli);
    }
    $mark = $mark + 1;

});
$client->on("error", function(swoole_client $cli){
    echo "error\n";
});
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";
});
$client->connect('127.0.0.1', 9000);
?>