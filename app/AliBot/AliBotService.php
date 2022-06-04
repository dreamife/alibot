<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 17:59
 */

namespace App\AliBot;


use App\BabyTracker\HistoryService;
use App\BabyTracker\SleepService;
use Illuminate\Http\Request;
class AliBotService
{

    const INTENT_MAP =[
        'last_diaper_change' => [HistoryService::class, "latest", ["diapering"]],
        'sleep_start' => [SleepService::class, "sleepStats", [SleepService::START]],
    ];

    public function result(Request $request) {

        $intent = $request->get('intentName');
        $processConfig = static::INTENT_MAP[$intent];
        $reply = app($processConfig[0])->{$processConfig[1]}(...$processConfig[2]);
        return [
            "returnCode" => 0,
            "returnValue" => [
                "reply" => $reply,
                "resultType" => "RESULT",
                "executeCode" => "SUCCESS"
            ]
        ];
    }

    private function callService() {

    }



}