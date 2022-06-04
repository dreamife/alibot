<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 21:41
 */

namespace App\Lib;


class Util
{

    public static function calTimeDiff($time, $base = null) {
        if(empty($base)) {
            $base = time();
        }
        $timeDiff = floor(($base - strtotime($time)) / 60);
        $diffStr = ($timeDiff >= 60 ? ((ceil($timeDiff/60))."小时") : "").
            ($timeDiff%60 ? (($timeDiff%60)."分钟"):"");
       return [$timeDiff, $diffStr];
    }

}