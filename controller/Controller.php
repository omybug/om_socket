<?php
/**
 * User: omybug
 * Date: 15-12-2 20:15
 */

namespace controller;

use \core\BaseController;

abstract class Controller extends BaseController{

    public function validator($fname, $data){
        return true;
    }
}