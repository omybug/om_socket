<?php

namespace core;

define('BAD_WORDS', serialize(require_once('config/BadWords.php')));

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
            //表示每60秒遍历一次，一个连接如果600秒内未向服务器发送任何数据，此连接将被强制关闭。
            'heartbeat_idle_time' => 600,
            'heartbeat_check_interval' => 60,
            'log_file' => 'logs/swoole.log'
//            'open_eof_check' => true,
//            'open_eof_split' => PHP_EOL,
//            'package_eof' => PHP_EOL,
//            'open_length_check' => 'true'
        ));
        $serv->on('Start', array($this, 'onStart'));
        $serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $serv->on('Connect', array($this, 'onConnect'));
        $serv->on('Receive', array($this, 'onReceive'));
        $serv->on('Close', array($this, 'onClose'));
        $serv->on('WorkerStop', array($this, 'onShutdown'));
        $serv->on('Task', array($this, 'onTask'));
        $serv->on('Finish', array($this, 'onFinish'));
        $this->serv = $serv;
    }

    function onStart($server){
        Log::log('Server is running @'.Config::get('server')['host'].':'.Config::get('server')['port']);
    }

    function onWorkerStart($serv, $workerId){
        Tick::tick($serv, $workerId);
    }

    function onShutdown($serv){
        Log::log("Server: onShutdown");
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
        Log::debug('on finish : '. $data);
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
        Log::error('exceptionHandler');
    }

    public static function fatalError(){
        Log::error('fatalError');
    }
}

$main = new Main();
$main->start();

?>