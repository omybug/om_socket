<?php
/**
 * User: omybug
 * Date: 15-10-19 19:51
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

    public static function isReallyWritable($file)
    {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') OR ! ini_get('safe_mode')))
        {
            return is_writable($file);
        }

        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */
        if (is_dir($file))
        {
            $file = rtrim($file, '/').'/'.md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE)
            {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        }
        elseif ( ! is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE)
        {
            return FALSE;
        }

        fclose($fp);
        return TRUE;
    }
}