<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-06
 * Time: 20:35
 */

namespace App\BabyTracker;



use Illuminate\Support\Facades\Cache;

class GrowthService extends BabyTrackerBase
{
    const TYPE_WEIGHT = 'weight';
    const TYPE_HEIGHT = 'height';

    const WEIGHT_GROSS = "毛重";
    const WEIGHT_NET = "净重";
    const WEIGHT_TARE = "皮重";

    public function setWeight($weight, $weightType) {

        if($weightObj = json_decode($weight)) {
            $weight = $weightObj->value;
        }

        if($weightType == static::WEIGHT_GROSS) {
            return Cache::put($this->weightGrossCacheKey(), $weight, 7200) ? "好啦, 毛重{$weight}公斤" : "有点问题唉";
        } elseif ($weightType == static::WEIGHT_TARE) {
            if(!($grossWeight = Cache::get($this->weightGrossCacheKey()))) {
                return "还没记录毛重呢";
            }
            $netWeight = $grossWeight - $weight;
        } elseif ($weightType == static::WEIGHT_NET) {
            $netWeight = $weight;
        }

        if(isset($netWeight)) {
            $this->babyTrackerRequest->setGrowth(static::TYPE_WEIGHT,
                $netWeight, isset($grossWeight) ? "毛重$grossWeight" : "");
            if($this->babyTrackerRequest->isSucceed()) {
                return "体重$netWeight, 添加成功";
            }
        }

        return "体重添加失败";

    }

    private function weightGrossCacheKey() {
        return getenv("BABY_ID")."#WEIGHT_GROSS";
    }

}