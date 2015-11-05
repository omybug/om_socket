<?php
$mark = 0;
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
$client->on("connect", function(swoole_client $cli) {
    //测试登录
    $data = array('a'=>'Home','f'=>'login','d'=>array('account'=>'test2','password'=>'000000'));
    //
    $cli->send(json_encode($data).PHP_EOL);

    $data = array('a'=>'Chat','f'=>'publish','d'=>array('msg'=>'test'));
    //
    $cli->send(json_encode($data).PHP_EOL);

//    $data = array('a'=>'Room','f'=>'create','d'=>array('roomName'=>'R1'));
//    $cli->send(json_encode($data));

});
$client->on("receive", function(swoole_client $cli, $data) use (&$mark){
    var_dump($data);
    if($mark == 0) {
        $mark = $mark + 1;
//        $data = array('a' => 'Room', 'f' => 'create', 'd' => array('roomName' => 'R2'));
        $data = array('a' => 'Room', 'f' => 'join', 'd' => array('roomId' => '2'));
        $cli->send(json_encode($data));
    }
});
$client->on("error", function(swoole_client $cli){
    echo "error\n";
});
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";
});

$client->connect('127.0.0.1', 9000);


////同步阻塞
//$client = new swoole_client(SWOOLE_SOCK_TCP);
//if(!$client->connect('127.0.0.1', 9000)){
//    exit(' connect failed' . PHP_EOL);
//}
//$data = array('a'=>'Home','f'=>'login','d'=>array('account'=>'test1','password'=>'000000'));
//$client->send(json_encode($data));
//var_dump($client->recv());
//var_dump($client->recv());


//$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
//$client->connect('127.0.0.1', 9000);
//$data = array('a'=>'Home','f'=>'login','d'=>array('account'=>'test1','password'=>'000000'));
//$client->send(json_encode($data));
//$client->rev();
?>