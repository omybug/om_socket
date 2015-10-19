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
     * @param string $str
     * @param string $rep
     * @return string
     */
    public static function filterWords($str, $rep='***'){
        return strtr($str,array_combine(Config::getBadWords(),array_fill(0,count(Config::getBadWords()),$rep)));;
    }

    /**
     * @return int
     */
    public static function timestamp() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
}