<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-04
 * Time: 16:05
 */

namespace App\Lib;

class BabyTrackerRequest extends BaseRequest
{
    const BASE_URL_KEY = 'BABY_TRACKER_BASE_URL';

    protected function httpRequest($url, $requestMethod = 'POST', $params = [], $type = 'json', $headers = [], $retried = 0) {

        if(!str_contains($url, 'auth_token')) {
            $url .= (preg_match("/\?.+=.+/", $url) ? "&" : "?")."auth_token=".getenv("BABY_TRACKER_TOKEN");
        }
        $headers['Authorization'] = getenv("BABY_TRACKER_TOKEN");
        return parent::httpRequest($url, $requestMethod, $params, $type, $headers, $retried);

    }

    public function latest() {
        return $this->httpRequest("statistics/lastest?baby_id=".getenv("BABY_ID"),
            "GET");
    }

    protected function parseResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        $response =  parent::parseResponse($response);
        return $response['data'] ?? [];
    }

}