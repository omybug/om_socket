<?php
/**
 * User: omybug
 * Date: 15-10-4 15:03
 */

namespace core;

class Config {

    public static $LOBBY_ROOM = 'lobby_room';

    const PACKAGE_EOF = "\r\n";

    private static $config = array();

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
     * @param string $file
     * @return array
     */
    public static function load($file){
        $config = array();
        if ($file !== '' and file_exists($file)){
            $config = array_merge($config, require $file);
        }
        self::$config = array_merge($config, self::$config);
        return $config;
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


