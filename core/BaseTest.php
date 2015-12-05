<?php
/**
 * User: omybug
 * Date: 2015/11/15 13:29
 */

require_once "Main.php";

class BaseTest {

    function __construct(){
        spl_autoload_register('core\Main::autoload');
        \core\Config::load();
    }

    protected function log($msg){
        \core\Log::debug($msg, 'test.log');
    }

}