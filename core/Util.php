<?php
/**
 * User: omybug
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
     * 是否有特殊字符从
     * @param $str
     * @return bool
     */
    public static function hasSpecialChar($str){
        $pattern='/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/';
        if(preg_match($pattern,$str)){
            return true;
        }
        return false;
    }

    /**
     * @param string $str
     * @param string $rep
     * @return string
     */
    public static function filterWords($str, $rep='***'){
        $s = Util::timestamp();
        $badWords = Config::getBadWords();
        $result = strtr($str,array_combine($badWords,array_fill(0,count($badWords),$rep)));
        $e = Util::timestamp() - $s;
        Log::log("bad words spend : $e ms");
        return $result;
    }

    /**
     * @return int 毫秒时间戳
     */
    public static function timestamp() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
}