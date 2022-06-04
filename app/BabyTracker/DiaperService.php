<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 14:15
 */

namespace App\BabyTracker;


class DiaperService
{

    /**
     * https://wx.babytracker.cn/miniapp_api/v1/diaperings
     * {"recorded_at":"2022-06-04T13:37:00+08:00","change_type":1,"weight":1,"poo_type":null,"poo_color":null,"remark":"","auth_token":"","baby_id":"","remind":false,"remind_in":180}
     * {
    "code": 0,
    "message": "success",
    "data": {
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
    }
    }
     */
    public function change() {

    }

    public function getLatest() {

    }
}