<?php

namespace core;

define('BAD_WORDS', serialize(require_once('config/BadWords.php')));

class Main{

    private $serv;

    function __construct(){
        spl_autoload_register('core\Main::autoloadCore');
        register_shutdown_function('core\Main::fatalError');
        set_error_handler('core\Main::errorHandler');
        set_exception_handler('core\Main::exceptionHandler');
        Config::load('config/Config.php');
        //删除redis中所有数据
        Redis::instance()->flushall();
        //创建大厅
        $zone = new Zone();
        $zone->createRoom(Config::$LOBBY_ROOM);
        Redis::instance()->close();
        //务必销毁，否者会共享到work线程，造成并发问题
        Redis::instance()->destory();
    }

    function initSocket(){
        $serv = new \swoole_server(Config::get('server')['host'], Config::get('server')['port']);
        $serv->set(array(
            'worker_num' => 2,
            'task_worker_num' => 6,
            //是否作为守护进程
            'daemonize' => !Config::isDebug(),
            //表示每60秒遍历一次，一个连接如果600秒内未向服务器发送任何数据，此连接将被强制关闭。
            'heartbeat_idle_time' => 600,
            'heartbeat_check_interval' => 60,
            'log_file' => 'logs/swoole.log',
            'package_eof' => Config::PACKAGE_EOF,
            'open_eof_check' => true,
            'open_eof_split' => true,
            'package_max_length' => 1024 * 1024 * 2, //2M
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
        $log = 'Server is running @'.Config::get('server')['host'].':'.Config::get('server')['port'];
        if(Config::isDebug()){
            echo $log.PHP_EOL;
        }
        Log::log($log);
        cli_set_process_title('server_'.Config::get('app_id'));
    }

    function onWorkerStart($serv, $workerId){
        Log::log("Worder $workerId Start");
        if(function_exists('opcache_reset')){
            opcache_reset();
        }
        if(function_exists('apc_clear_cache')){
            apc_clear_cache();
        }
        Config::reload('config/Config.php');
        spl_autoload_register('core\Main::autoloadAction');
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
        Log::route($msg);
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
        $msg = rtrim($msg);
//        $serv->send($fd,$msg.Config::PACKAGE_EOF);
//        Log::route($msg);
        $serv->task(array('fd'=>$fd, 'msg'=>$msg));
    }

    public function start(){
        $this->initSocket();
        $this->serv->start();
    }

    public static function autoloadCore($class) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $file .= '.php';
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }

    public static function autoloadAction($class){
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