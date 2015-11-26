<?php

namespace core;

class Log {
    private static $LOG 		= 1;
    private static $DEBUG 	= 2;
    private static $ERROR 	= 3;
    private static $SQL 		= 4;
    private static $ROUTE	= 5;

    private $flogs   = array();

    private $path = 'logs/';

    private static $logName = array(1=>'log',2=>'debug',3=>'error',4=>'sql', 5=>'route');
    private static $instance = null;

    private function __construct() {
        $this->path  = $this->path;
    }

    public static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new Log();
        }
        return self::$instance;
    }

    public static function log($message, $logType = null){
        $logType = empty($logType) ? Log::$LOG : $logType;
        Log::instance()->write($message, self::$logName[$logType]);
    }

    public static function debug($message){
        if(Config::isDebug()) {
            Log::instance()->write($message, self::$logName[Log::$DEBUG]);
        }
    }

    public static function sql($message){
        if(Config::isDebug()) {
            //Log::instance()->write($message, self::$logName[Log::$SQL]);
        }
    }

    public static function error($message){
        Log::instance()->write($message, self::$logName[Log::$ERROR]);
    }

    public static function route($message){
        Log::instance()->write($message, self::$logName[Log::$ROUTE]);
    }

    public function write($message, $levelType) {
        if(is_array($message)){
            $message = json_encode($message);
        }
        if(Config::isDebug() && $levelType == self::$logName[Log::$DEBUG] || $levelType == self::$logName[Log::$ERROR]) {
            echo $message . PHP_EOL;
        }
        $date = new \DateTime();
        $logDir = $this->path . $date->format('Y-m-d') . '/';
        $logFile = $logDir . $levelType . '.log';
        if(!is_dir($logDir)) {
            if(mkdir($logDir,0777) !== true){
                return;
            }
            foreach($this->flogs as $f){
                fclose($f);
            }
            unset($this->flogs);
            $this->flogs = array();
        }
        if(!array_key_exists($logFile, $this->flogs)){
            $this->flogs[$logFile] = fopen($logFile,'a');
        }
        fwrite($this->flogs[$logFile],Util::timestamp().' '.$message .PHP_EOL);
    }
}
?>
