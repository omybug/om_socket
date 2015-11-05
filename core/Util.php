<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/19
 * Time: 19:51
 */

namespace core;


class Util {

    /**
     * @param $str
     * @return bool
     */
    public static function hasBadWords($str){
        return $str != self::filterWords($str);
    }

    /**
     * @param string $str
     * @param string $rep
     * @return string
     */
    public static function filterWords($str, $rep='***'){
        $badWords = Config::getBadWords();
        return strtr($str,array_combine($badWords,array_fill(0,count($badWords),$rep)));
    }

    /**
     * @return int 毫秒时间戳
     */
    public static function timestamp() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
}