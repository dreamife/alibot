<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-06
 * Time: 06:10
 */

namespace App\Lib;


use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocalPCForwarder extends BaseRequest
{
    const TIMEOUT = 2;
    const MAX_RETRY = 0;
    const BASE_URL_KEY = 'LOCAL_PC_ENDPOINT';


    public function forward(Request $request) {
        $res = [];
        try {
            $headers = $request->headers->all();
            unset($headers['host']);
            $res = $this->httpRequest($request->path(),
                $request->method(),
                file_get_contents("php://input"),
                "body", $headers);
        } catch (GuzzleException $exception) {
            Log::info("Forward fail because timeout");
        } catch (\Exception $exception) {
            Log::error("Forward fail because exception ". $exception->getMessage());
        }
        return $res;
    }

}