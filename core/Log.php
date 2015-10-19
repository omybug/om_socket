<?php

namespace core;

class Log {
	public static $LOG 		= 1;
	public static $DEBUG 	= 2;
	public static $ERROR 	= 3;
	public static $SQL 		= 4;
	public static $ROUTE	= 5;

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
            Log::instance()->write($message, self::$logName[Log::$SQL]);
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
		$date = new \DateTime();
		$logDir = $this->path . $date->format('Y-m-d') . '/';
		$logFile = $logDir . $levelType . '.log';
		if(!is_dir($logDir)) {
			if(mkdir($logDir,0777) !== true){
				return;
			}
		}
		$this->edit($logFile, $date, $message);
	}

	private function edit($logFile,$date,$msg) {
		$msg = $date->format('H:i:s ').$msg .PHP_EOL;
        if(Config::isDebug()) {
            echo $msg;
        }
        file_put_contents($logFile, $msg,FILE_APPEND);
	}
}
?>
