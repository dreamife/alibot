<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 14:18
 */

namespace App\BabyTracker;


use App\Lib\BabyTrackerRequest;
use App\Lib\Util;

class HistoryService extends BabyTrackerBase
{
    const DIAPERING = "换尿布";
    const SLEEPING = "醒";
    const FEEDING = "喂";

    const TRACKER_TYPE_MAP = [
        self::DIAPERING => [
            "name" => "换尿布",
            "time_gap" => 150,
            "hint" => "下次要早点换哦",
            "real_type" => 'diapering',
            ],
        self::FEEDING => [
            "name" => "喂奶",
            "time_gap" => 150,
            "hint" => "宝宝饿坏了吧",
            "real_type" => 'feeding',
        ],
        self::SLEEPING => [
            "name" => "睡醒",
            "time_gap" => 180,
            "hint" => "宝宝困得不行了吧",
            "real_type" => 'sleeping',
        ]
    ];

    public function latest($type) {
        $trackerConfig = static::TRACKER_TYPE_MAP[$type];
        $latest = $this->babyTrackerRequest->latest()[$trackerConfig['real_type']] ?? [];
        if(empty($latest)) {
            return sprintf("今天还没有%s呢", $trackerConfig['name']);
        }
        $return = "上次%s已经过去%s了";
        switch ($type) {
            case self::DIAPERING:
            default:
                $activityTime = $latest['recorded_at'];
                break;
            case self::SLEEPING:
                $activityTime = $latest['end_at'];
                break;
            case self::FEEDING:
                $activityTime = date("Y-m-d H:i:s", strtotime($latest['recorded_at']) + $latest['total_time']);
                break;
        }
        list($timeDiff, $timeDesc) = Util::calTimeDiff($activityTime);
        $data = [$trackerConfig['name'],$timeDesc];
        if($timeDiff > $trackerConfig['time_gap'] && !empty($trackerConfig['hint'])) {
            if($type == self::SLEEPING && empty($this->babyTrackerRequest->sleepTimings()[0] ?? [])) {
                $return .= ", ".$trackerConfig['hint'];
            } else {
                $return .= ", 现在又睡了，好羡慕";
            }

        }
        return sprintf($return, ...$data);
    }


    public function history() {

    }

}