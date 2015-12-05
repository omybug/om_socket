<?php
/**
 * User: omybug
 * Date: 15-10-4 15:03
 */

namespace core;

class Config {

    private static $CONFIG_DIR = 'config/';

    public static $LOBBY_ROOM = 'lobby_room';

    const PACKAGE_EOF = "\r\n";

    private static $config = array();

    public static function load(){
        self::$config = array();
        self::$config = self::loadConfigFile('app_dev.php');
        if(empty(self::$config)){
            self::$config   = self::loadConfigFile('app.php');
        }
        self::$config['filters']  = self::loadConfigFile('filters.php');
        self::$config['routes']   = self::loadConfigFile('routes.php');
        self::$config['ticks']    = self::loadConfigFile('ticks.php');
    }

    private static function loadConfigFile($file){
        $path = APP_ROOT.self::$CONFIG_DIR.$file;
        if ($file !== '' and file_exists($path)){
            return require $path;
        }
        return array();
    }

    /**
     * @param string $key
     * @param object $default
     * @return object
     */
    public static function get($key, $default = null){
        if(array_key_exists($key, self::$config)){
            return self::$config[$key];
        }else{
            return $default;
        }
    }

    /**
     * @param string $key
     * @param object $val
     */
    public static function set($key, $val){
        self::$config[$key] = $val;
    }


    /**
     * 重新加载配置文件
     * @param $file
     */
    public static function reload($file){
        self::$config = array();
        self::load($file);
    }

    /**
     * @return boolean
     */
    public static function isDebug(){
        return self::get('is_debug');
    }

    /**
     * 获取违禁词
     * @return array
     */
    public static function getBadWords(){
//        return require_once('config/BadWords.php');
        return unserialize(BAD_WORDS);
    }

}


