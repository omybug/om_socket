<?php
/**
 * User: omybug
 * Date: 2015/10/15
 * Time: 17:38
 */

namespace core;


class Tick {

    protected $soc;

    const MAX_TIME = 86400;

    function __construct($soc){
        $this->soc = $soc;
    }

    public static function tick($serv, $workerId){
        if($workerId == 0 && !$serv->taskworker){
            $ticks = Config::get('ticks');
            if(empty($ticks)){
                return;
            }
            foreach($ticks as $tick){
                if($tick['t'] == 1 && $tick['time'] < Tick::MAX_TIME){
                    $serv->tick($tick['time'] * 1000, function() use ($serv, $workerId, $tick){
                        $serv->task($tick);
                    });
                }
            }
            $serv->tick(1000, function() use ($serv, $workerId){
                $stime = time() - strtotime(date('Y-m-d 00:00:00'));
                $ticks = Config::get('ticks');
                foreach($ticks as $tick){
                    if($tick['t'] == 2 && $tick['time'] == $stime && $tick['time'] < Tick::MAX_TIME){
                        $serv->task($tick);
                    }
                }
            });
        }
    }

}