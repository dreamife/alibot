<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 14:18
 */

namespace App\BabyTracker;


use App\Lib\BabyTrackerRequest;

class HistoryService
{
    private $babyTrackerRequest;

    const TRACKER_TYPE_MAP = [
        "diapering" => [
            "name" => "换尿布",
            "time_gap" => 150,
            "hint" => "下次要早点换哦"
            ],
    ];

    public function __construct(BabyTrackerRequest $request)
    {
        $this->babyTrackerRequest = $request;
    }

    /**
     * https://wx.babytracker.cn/miniapp_api/v1/statistics/lastest?baby_id=&auth_token=
     * {
    "code": 0,
    "message": "success",
    "data": {
    "feeding": {
    "recorded_at": "2022-06-04T10:23:53+08:00",
    "recorded_by": {
    "id": 62418,
    "nickname": "喵星。陈不跑🏃🏃",
    "avatar_url": "https://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIS7jWEj4on0YoTELNpQAP7N9OX2y14TNhayeKwEZO85mXiazibAACrNqichLicyFIShW1rP3J1kV6dXA/132",
    "gender": 0
    },
    "id": 5902768,
    "left_time": 240,
    "right_time": 180,
    "total_time": 420,
    "position": 2,
    "left_first": false,
    "remark": "",
    "type": "breastmilkfeeding"
    },
    "diapering": {
    "recorded_at": "2022-06-04T13:37:00+08:00",
    "recorded_by": {
    "id": 62420,
    "nickname": "DreaMing",
    "avatar_url": "https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqFibyxKg2tO3b61MNV0Be2aMp72HGlLBJywricCz825sKicNVIEejUwy1eL4cYzibUolhkKricG8mgytQ/132",
    "gender": 0
    },
    "id": 4059485,
    "change_type": 1,
    "weight": 1,
    "poo_type": null,
    "poo_color": null,
    "remark": "",
    "poo_photo": null,
    "type": "diapering"
    },
    "sleeping": {
    "recorded_at": "2022-06-04T11:50:00+08:00",
    "recorded_by": {
    "id": 62418,
    "nickname": "喵星。陈不跑🏃🏃",
    "avatar_url": "https://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIS7jWEj4on0YoTELNpQAP7N9OX2y14TNhayeKwEZO85mXiazibAACrNqichLicyFIShW1rP3J1kV6dXA/132",
    "gender": 0
    },
    "id": 3451181,
    "total_time": 3906,
    "quality": null,
    "sleep_type": null,
    "remark": "",
    "start_at": "2022-06-04T11:50:00+08:00",
    "end_at": "2022-06-04T12:55:06+08:00",
    "type": "sleeping"
    }
    }
    }
     */

    public function latest($type) {
        $latest = $this->babyTrackerRequest->latest()[$type] ?? [];
        $trackerConfig = static::TRACKER_TYPE_MAP[$type];
        if(empty($latest)) {
            return sprintf("今天还没有%s呢", $trackerConfig['name']);
        }
        $return = "上次%s已经过去%s%s了";
        $timeDiff = floor((time() - strtotime($latest['recorded_at'])) / 60);
        $data = [$trackerConfig['name'],
            $timeDiff >= 60 ? ((ceil($timeDiff/60))."小时") : "",
            $timeDiff%60 ? (($timeDiff%60)."分"):""
        ];
        if($timeDiff > $trackerConfig['time_gap'] && !empty($trackerConfig['hint'])) {
            $return .= ", ".$trackerConfig['hint'];
        }
        return sprintf($return, ...$data);
    }


    public function history() {

    }

}