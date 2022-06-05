<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 16:05
 */

namespace App\Lib;

use Illuminate\Support\Facades\Log;

class BabyTrackerRequest extends BaseRequest
{
    const BASE_URL_KEY = 'BABY_TRACKER_BASE_URL';

    protected $errMsg = "";
    protected $succeed;

    protected function httpRequest($url, $requestMethod = 'POST', $params = [], $type = 'json', $headers = [], $retried = 0) {

        if($requestMethod == 'GET' && !str_contains($url, 'auth_token')) {
            $url .= (preg_match("/\?.+=.+/", $url) ? "&" : "?")."auth_token=".
                getenv("BABY_TRACKER_TOKEN")."&baby_id=".getenv("BABY_ID");
        } elseif ($type == 'json') {
            $params['auth_token'] = getenv("BABY_TRACKER_TOKEN");
            $params['remark'] = "add by ali genie";
            $params["baby_id"] = getenv('BABY_ID');
        }
        $headers['Authorization'] = getenv("BABY_TRACKER_TOKEN");
        $res = parent::httpRequest($url, $requestMethod, $params, $type, $headers, $retried);
        return $res;

    }

    public function latest() {
        return $this->httpRequest("statistics/lastest",
            "GET");
    }

    public function sleepTimings() {
        return $this->httpRequest("sleep_timings", "GET");
    }

    public function changeDiaper($type, $time = null) {
        return $this->httpRequest("diaperings", "POST", [
            "recorded_at" => $time ?: date("Y-m-d H:i:s"),
            "change_type" => $type,
            "weight" => 1,
            "poo_type" => null,
            "poo_color" => null,
        ]);
    }

    public function sleepStart($time = null) {
        return $this->httpRequest("sleep_timings/start", "POST", [
            "start_at" => $time ?: date("Y-m-d H:i:s"),
        ]);
    }

    public function sleepEnd($time = null) {
        return $this->httpRequest("sleep_timings/end", "POST", [
            "end_at" => $time ?: date("Y-m-d H:i:s"),
        ]);
    }

    public function sleepRec($end, $start) {
        return $this->httpRequest("sleepings", "POST", [
            "start_at" => $start,
            "end_at" => $end,
        ]);
    }

    protected function parseResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        $response =  parent::parseResponse($response);
        Log::info(json_encode($response));
        if(($response['code'] ?? "") != 0) {
            $this->errMsg = $response['message'] ?? "";
            $this->succeed = false;
        } else {
            $this->errMsg = "";
            $this->succeed = true;
        }
        return $response['data'] ?? [];
    }

    public function getErrorMsg() {
        return $this->errMsg;
    }

    public function isSucceed() {
        return $this->succeed;
    }

}