<?php
/**
 * User: omybug
 * Date: 2015/11/15 13:29
 */

//function __autoload($class) {
//    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
//    $file .= '.php';
//    if (file_exists($file)) {
//        require $file;
//        return true;
//    }
//    $paths = array('action','activity','service','dao','test');
//    foreach($paths as $p){
//        if (stristr($class,$p) && file_exists($p . DIRECTORY_SEPARATOR . $class . '.php')) {
//            require $p . DIRECTORY_SEPARATOR . $class . '.php';
//            return true;
//        }
//    }
//    return false;
//}

abstract class ClientBase {

    private $client;
    const PACKAGE_EOF   = "\r\n";
    const SERVER_IP     = '192.168.100.105';
    const SERVER_PORT   = 9000;
    const LOG_FILE      = 'test.log';

    function __construct(){
        $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        $this->client->set(array('open_eof_check' => true, 'package_eof' => self::PACKAGE_EOF));
        $this->client->on("connect", [$this,'onConnect']);
        $this->client->on("receive", [$this,'onReceive']);
        $this->client->on("error", [$this,'onError']);
        $this->client->on('close', [$this,'onClose']);
    }
    function onConnect(swoole_client $cli){
        $this->log('onConnect');
        $this->connect();
    }

    function onReceive(swoole_client $cli, $data){
        $data = rtrim($data);
        $this->log('receive');
        $this->log($data);
        $this->receive($data);
    }

    function onError(swoole_client $cli){
        $this->log('onError');
    }

    function onClose(swoole_client $cli){
        $this->log('onClose');
    }

    public function start(){
        $this->client->connect(self::SERVER_IP, self::SERVER_PORT);
    }

    public function send($data){
        $this->log('send');
        $this->log($data);
        $this->client->send(json_encode($data).self::PACKAGE_EOF);
    }

    public function log($m){
//        $msg = is_array($m)?json_encode($m):$m;
//        $date = new \DateTime();
//        $msg = $date->format('H:i:s ').$msg .PHP_EOL;
//        echo $msg;
//        file_put_contents(self::LOG_FILE, $msg,FILE_APPEND);
    }

    abstract function connect();

    abstract function receive($data);

}