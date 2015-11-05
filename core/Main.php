<?php

namespace core;

class Main{

    private $serv;

    function __construct(){
        spl_autoload_register('core\Main::autoload');
        register_shutdown_function('core\Main::fatalError');
        set_error_handler('core\Main::errorHandler');
        set_exception_handler('core\Main::exceptionHandler');

        Config::load('config/Config.php');
        //删除redis中所有数据
        Redis::instance()->flushall();
        //创建大厅
        $zone = new Zone();
        $zone->createRoom(Config::$LOBBY_ROOM);
    }

    function initSocket(){
        $serv = new \swoole_server(Config::get('server')['host'], Config::get('server')['port']);
        $serv->set(array(
            'worker_num' => 2,
            'task_worker_num' => 4,
            //抢占式
            'dispatch_mode' => 3,
            'log_file' => 'logs/swoole.log',
            'open_eof_check' => true,
            'open_eof_split' => PHP_EOL,
            'package_eof' => PHP_EOL,
            'open_length_check' => 'true'
        ));
        $serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $serv->on('Connect', array($this, 'onConnect'));
        $serv->on('Receive', array($this, 'onReceive'));
        $serv->on('Close', array($this, 'onClose'));
        $serv->on('WorkerStop', array($this, 'onShutdown'));
        $serv->on('Task', array($this, 'onTask'));
        $serv->on('Finish', array($this, 'onFinish'));
        $this->serv = $serv;
    }

    function onOpen(swoole_websocket_server $server, $request) {
        echo "server: handshake success with fd{$request->fd}\n";
    }

    function onMessage(swoole_websocket_server $server, $frame) {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd, "this is server");
    }

    function onWorkerStart($serv, $workerId){
        Tick::tick($serv, $workerId);
//        Log::debug('onWorkerStart' . $workerId);
    }

    function onShutdown($serv){
        echo "Server: onShutdown\n";
    }

    function onClose($serv, $fd, $fromId) {
        Log::debug('on close ' . $fd);
        $userService = new \UserService();
        $userService->offline($fd);
    }

    function onConnect($serv, $fd, $fromId){
        Log::debug('on connecte : ' . $fd);
    }

    function onTask($serv, $taskId, $fromId, $msg){
        if(array_key_exists('fd', $msg)){
            Route::route($serv, $msg['msg'], $msg['fd'], $taskId, $fromId);
        }else{
            Route::route($serv, $msg, -1, $taskId, $fromId);
        }
        return;
    }

    function onFinish($serv, $taskId, $data){
        log::debug('on finish : '. $data);
    }

    function onReceive($serv, $fd, $fromId, $msg){
        Log::route($msg);
        $serv->task(array('fd'=>$fd, 'msg'=>$msg));
    }

    public function start(){
        $this->initSocket();
        $this->serv->start();
    }

    public static function autoload($class) {
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
    }

    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    public static function errorHandler($errno, $errstr, $errfile, $errline){
        Log::error("[$errno] $errstr");
        Log::error("Error $errfile on line $errline");
    }

    public static function exceptionHandler($e){

    }

    public static function fatalError(){

    }
}

$main = new Main();
$main->start();

?>