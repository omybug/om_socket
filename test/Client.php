<?php
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC); 
$client->on("connect", function(swoole_client $cli) {
    //测试登录
    $data = array('a'=>'Home','f'=>'login','d'=>array('account'=>'test2','password'=>'000000'));
    //
    $cli->send(json_encode($data));
}); 
$client->on("receive", function(swoole_client $cli, $data){ 
    echo "Receive: $data".PHP_EOL;
    $data = array('a'=>'Chat','f'=>'publish','d'=>array('msg'=>'中国天安门挂着毛泽东的头像'));
    $cli->send(json_encode($data));
    exit;
});
$client->on("error", function(swoole_client $cli){ 
    echo "error\n"; 
});
$client->on("close", function(swoole_client $cli){ 
    echo "Connection close\n"; 
});
$client->connect('127.0.0.1', 9000);
?>