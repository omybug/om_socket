<?php
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC); 
$client->on("connect", function(swoole_client $cli) {
    //测试登录
    $data = array('a'=>'Home','f'=>'login','d'=>array('account'=>'test1','password'=>'000000'));
    //
    $cli->send(json_encode($data));
}); 
$client->on("receive", function(swoole_client $cli, $data){ 
    echo "Receive: $data";
//    $data = array('a'=>'Test','f'=>'token','d'=>array('account'=>'test1','password'=>'000000'));
//    $cli->send(json_encode($data));
});
$client->on("error", function(swoole_client $cli){ 
    echo "error\n"; 
}); 
$client->on("close", function(swoole_client $cli){ 
    echo "Connection close\n"; 
});
$client->connect('127.0.0.1', 9000);
?>