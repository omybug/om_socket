<?php

namespace core;

class Main{
	private $serv;

	function __construct(){
        Config::load('config/Config.php');
        //删除redis中所有数据
        Redis::instance()->flushall();
	}

    function initSocket(){

        $serv = new \swoole_server(Config::get('server')['host'], Config::get('server')['port']);
        $serv->set(array(
//            'timeout' => 1, //select and epoll_wait timeout.
//            'poll_thread_num' => 1, //reactor thread num
            'worker_num' => 2, //reactor thread num
//            'backlog' => 128, //listen backlog
//            'max_conn' => 10000,
//            'dispatch_mode' => 2,
//            'open_tcp_keepalive' => 1,
//            'log_file' => '/tmp/swoole.log', //swoole error log
        ));
        $serv->on('WorkerStart', array($this, 'onStart'));
        $serv->on('Connect', array($this, 'onConnect'));
        $serv->on('Receive', array($this, 'onReceive'));
        $serv->on('Close', array($this, 'onClose'));
        $serv->on('WorkerStop', array($this, 'onShutdown'));
        $this->serv = $serv;
    }

    function intWebSocket(){

    }


	function onStart($serv)
    {
        echo "Server: start.Swoole version is [" . SWOOLE_VERSION . "]\n";
    }

    function onShutdown($serv)
    {
        echo "Server: onShutdown\n";
    }

    function onClose($serv, $fd, $from_id)
    {
        Log::debug($fd.' closed');
    }

    function onConnect($serv, $fd, $from_id)
    {
        
    }

    function onReceive($serv, $fd, $from_id, $args)
    {
        Log::route($args);
        $data = json_decode($args,true);
        if(!$this->check($data)){
            echo "msg error ! \n";
            return false;
        }
        $action = $data['a'].'Action';
        $func = $data['f'];
        if(class_exists($action)) {
            $action = new $action($serv, $fd, $data['d']);
            if(method_exists($action,$func)){
                $action->$func();
            }else{
                echo $action.'.'.$func.' is not exist!';
            }
        }else{
            echo 'action '.$action.' is not exist!';
        }
    }

	public function start(){
        $this->initSocket();
        $this->serv->start();
	}

    private function check($data){
        if(!array_key_exists('a', $data)){
            return false;
        }
        if(!array_key_exists('f', $data)){
            return false;
        }
        return true;
    }
}

spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file .= '.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    $paths = array('action','activity','service','dao');
    foreach($paths as $p){
        if (stristr($class,$p) && file_exists($p . DIRECTORY_SEPARATOR . $class . '.php')) {
            require $p . DIRECTORY_SEPARATOR . $class . '.php';
            return true;
        }
    }
    return false;
});

$main = new Main();
$main->start();

?>