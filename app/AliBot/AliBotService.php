<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 17:59
 */

namespace App\AliBot;


use App\BabyTracker\DiaperService;
use App\BabyTracker\GrowthService;
use App\BabyTracker\HistoryService;
use App\BabyTracker\SleepService;
use App\Lib\BaseRequest;
use App\SmartDevice\SmartDeviceBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AliBotService
{
    protected $entities;

    public function __construct(Request $request)
    {
        $this->entities = $request->get("slotEntities", []);
    }

    const INTENT_MAP =[
        'last_activity' => [HistoryService::class, "latest", ["^tracker_type"]],
        'sleep_time' => [SleepService::class, "sleepStats", [SleepService::START]],
        'diaper_change' => [DiaperService::class, "change", ["^diaper_content"]],
        'sleep_start' => [SleepService::class, "sleepOp", [SleepService::START]],
        'sleep_end' => [SleepService::class, "sleepOp", [SleepService::END]],
        'add_weight' => [GrowthService::class, "setWeight", ["^sys.weight", "^weight_type"]],
        'device_status' => [SmartDeviceBase::class, "deviceStatus", ["^device", "^device_status"]],
    ];

    public function result(Request $request) {

        $intent = $request->get('intentName');
        $processConfig = static::INTENT_MAP[$intent];
        try {
            $service = app($processConfig[0]);
            $params = $this->processParams($request, $processConfig[2]);
            $reply = $service->{$processConfig[1]}(...$params);
            $askedInfo = $service->getAskedInfo();
        } catch (\Throwable $exception) {
            Log::error("Failed when processing $intent ".$exception->getMessage().$exception->getFile().$exception->getLine());
            $reply = "出了点问题，赶紧让粑粑去修啊";
        }

        return [
            "returnCode" => 0,
            "returnValue" => [
                "reply" => $reply,
                "resultType" => "RESULT",
                "executeCode" => "SUCCESS"
            ]
        ];
    }

    private function processParams(Request $request, $params) {
        $entityMap = array_column(
            array_map(function($entity) {
                return preg_replace("/\(\w+\)/u", "", $entity);
            }, $request->get('slotEntities', [])),
            "standardValue",
            "intentParameterName");
        return array_map(function ($param) use($entityMap) {
            if(strpos($param, "^") === 0) {
                return $entityMap[substr($param, 1)];
            } else {
                return $param;
            }
        }, $params);
    }



}