<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 14:15
 */

namespace App\BabyTracker;


use App\Lib\BabyTrackerRequest;

class DiaperService extends BabyTrackerBase
{

    CONST DIAPER_PEE = 1;
    CONST DIAPER_POO = 0;
    CONST DIAPER_PEE_AND_POO = 2;


    public function change($diaperContent = null) {
        $diaperType = self::DIAPER_PEE;
        $diaperContent == "粑粑" && $diaperType = self::DIAPER_POO;
        $result = $this->babyTrackerRequest->changeDiaper($diaperType);
        if($result && !empty($result['id'])) {
            return "好啦";
        } else if($msg = $this->babyTrackerRequest->getErrorMsg()){
            return "添加失败， $msg";
        } else {
            return "出了点问题，请手动添加一下";
        }
    }

    public function getLatest() {

    }
}