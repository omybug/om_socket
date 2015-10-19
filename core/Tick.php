<?php
/**
 * User: omybug
 * Date: 2015/10/15
 * Time: 17:38
 */

namespace core;


class Tick {

    protected $soc;

    function __construct($soc){
        $this->soc = $soc;
    }

    public static function tick($serv, $workerId){
        if($workerId == 1 && !$serv->taskworker){
            $ticks = Config::get('ticks');
            foreach($ticks as $tick){
                if($tick['t'] == 1){
                    $serv->tick($tick['time'], function() use ($serv, $workerId, $tick){
                        $serv->task($tick);
                    });
                }else{

                }
            }
        }
    }

}