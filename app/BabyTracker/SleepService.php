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

class SleepService extends BabyTrackerBase
{
    const START = 0;
    const END = 1;
    const DURATION = 2;
    private $sleepStart;


    public function sleepStats($type) {
        $sleepTimings = $this->babyTrackerRequest->sleepTimings()[0] ?? [];
        if(empty($sleepTimings)) {
            return "宝宝醒着呢";
        }
        switch ($type) {
            case static::START:
                $time = $sleepTimings['start_at'];
                $this->sleepStart = $time;
                break;
        }

        return sprintf("宝宝已经睡了%s", Util::calTimeDiff($time)[1]);

    }

    public function sleepOp($type) {

        $extraMsg = "";
        if($type == static::END) {
            $this->sleepStats(static::START);
            if(!$this->sleepStart) {
                return "报告，我这边的记录宝宝还没睡呢，是不是漏记睡眠开始时间了？";
            }
            $this->babyTrackerRequest->sleepRec(date("Y-m-d H:i:s"), $this->sleepStart);
            $this->babyTrackerRequest->sleepEnd();
            list($sleepingTime, $timeDesc) = Util::calTimeDiff($this->sleepStart);
            if($sleepingTime > 120) {
                $hint = "真棒！";
            } else {
                $hint = "下次要多睡会了";
            }
            $extraMsg = sprintf("，这次睡了%s， %s", $timeDesc, $hint);
        } else {
            $this->babyTrackerRequest->sleepStart();
        }

        if($this->babyTrackerRequest->isSucceed()) {
            return "OK$extraMsg";
        } else if($err = $this->babyTrackerRequest->getErrorMsg()) {
            return "睡眠添加失败,$err";
        } else {
            return "睡眠添加失败，请手动添加";
        }

    }
}