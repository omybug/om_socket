<?php
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC); 
$client->on("connect", function(swoole_client $cli) {
    $data = array('a'=>'Test','f'=>'test','d'=>array('hello'));
    $cli->send(json_encode($data));
}); 
$client->on("receive", function(swoole_client $cli, $data){ 
    echo "Receive: $data";
});
$client->on("error", function(swoole_client $cli){ 
    echo "error\n"; 
}); 
$client->on("close", function(swoole_client $cli){ 
    echo "Connection close\n"; 
});
$client->connect('127.0.0.1', 9000);
?>