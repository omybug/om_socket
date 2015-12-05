<?php

function debug($msg){
    echo $msg.PHP_EOL;
}

function login(swoole_client $cli){
    $data = array('a'=>'Home','f'=>'login','d'=>array('account'=>'test1','password'=>'000000'));
    $cli->send(json_encode($data));
    debug('Login');
}

function createRole(swoole_client $cli){
    $data = array('a'=>'User','f'=>'createRole','d'=>array('name'=>'oMyBug'));
    $cli->send(json_encode($data));
    debug('createRole');
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

function buy(swoole_client $cli){
    $amount = rand(1,10);
    $itemId = rand(1,10);
    $data = array('a' => 'Shop', 'f' => 'buy', 'd' => array('itemId' => $itemId,'amount'=>$amount));
    $cli->send(json_encode($data));
    debug("buy $itemId $amount");
}

function sell(swoole_client $cli){
    $amount = rand(1,10);
    $itemId = rand(1,10);
    $data = array('a' => 'Shop', 'f' => 'buy', 'd' => array('itemId' => $itemId,'amount'=>$amount));
    $cli->send(json_encode($data));
    debug("sell $itemId $amount");
}

$mark = 0;
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
$client->on("connect", function(swoole_client $cli) {
    login($cli);


});
$client->on("receive", function(swoole_client $cli, $data) use (&$mark){
    debug($data);
//    if($mark < 100){
//        buy($cli);
//    }else{
//        if(rand(1,2) == 1){
//            buy($cli);
//        }else{
//            sell($cli);
//        }
//    }
    if($mark < 1){
        createRole($cli);
    }
    $mark = $mark + 1;
    sleep(1);
});
$client->on("error", function(swoole_client $cli){
    echo "error\n";
});
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";
});
$client->connect('127.0.0.1', 9000);
?>