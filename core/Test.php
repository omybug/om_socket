<?php
/**
 * User: omybug
 * Date: 2015/10/9
 * Time: 18:09
 */

namespace core;


class Test {

    public $log;

    function __construct(){
        Config::load('config/Config.php');
        $this->log = Log::instance();
    }

}

spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file .= '.php';
    $file = "..".DIRECTORY_SEPARATOR.$file;
    if (file_exists($file)) {
        require $file;
        return true;
    }
    $paths = array('action','activity','service','dao');
    foreach($paths as $p){
        if (stristr($file,$p) && file_exists($p . DIRECTORY_SEPARATOR . $file)) {
            require __DIR__.DIRECTORY_SEPARATOR.$p . DIRECTORY_SEPARATOR . $file;
            return true;
        }
    }
    return false;
});