<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 21:36
 */

namespace App\BabyTracker;


use App\Lib\BabyTrackerRequest;
use App\Lib\Util;

class SleepService
{
    const START = 0;
    const END = 1;
    const DURATION = 2;

    public function __construct(BabyTrackerRequest $request)
    {
        $this->babyTrackerRequest = $request;
    }

    public function sleepStats($type) {
        $sleepTimings = $this->babyTrackerRequest->sleepTimings()[0] ?? [];
        if(empty($sleepTimings)) {
            return "今天还没有睡觉呢";
        }
        switch ($type) {
            case static::START:
                $time = $sleepTimings['start_at'];
                break;
        }

        return sprintf("宝宝已经睡了%s", Util::calTimeDiff($time)[1]);

    }
}