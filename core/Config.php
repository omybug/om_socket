<?php
/**
 * User: omybug
 * Date: 15-10-4 15:03
 */

namespace core;

class Config {

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

}


