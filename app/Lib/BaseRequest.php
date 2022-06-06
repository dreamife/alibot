<?php

namespace App\Lib;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class BaseRequest
{

    const TIMEOUT = 10;
    const MAX_RETRY = 3;
    const BASE_URL_KEY = '';
    const CACHE_EXPIRE_SECONDS = [];

    static $instance;
    private $client;
    protected $wantsRawOutPut = false;

    public function __construct() {

        $this->client = new Client([
            'base_uri' => getenv(static::BASE_URL_KEY)
        ]);
    }

    protected function httpRequest($url, $requestMethod = 'POST', $params = [], $type = 'json', $headers = [], $retried = 0) {
        try {
            $response = $this->client->request(
                $requestMethod,
                $url,
                [
                    'headers' => $headers,
                    $type => $params,
                    'timeout' => static::TIMEOUT
                ]
            );
            return $this->parseResponse($response);
        } catch (GuzzleException $exception) {
            ++$retried;
            Log::error( static::class." fail, $retried times, url $url, params ".json_encode($params)
                ." message ".$exception->getMessage());
            if($retried >= static::MAX_RETRY) {
                Log::error(static::class.__FILE__.__LINE__.$exception->getMessage());
                return null;
            } else {
                usleep(500000);
                return $this->httpRequest($url, $requestMethod, $params, $type, $headers, $retried);
            }
        } catch (\Throwable $exception) {
            Log::error(static::class.$exception->getFile().$exception->getLine().$exception->getMessage());
        }
    }


    protected function parseResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        $contents = $response->getBody()->getContents();
        return $this->wantsRawOutPut ? $contents : (json_decode($contents, true) ?: []);
    }

    /***
     * @return BaseRequest
     */
    public static function getInstance() {
        if(!isset(static::$instance[static::class])) {
            static::$instance[static::class] = new static();
        }
        return static::$instance[static::class];
    }

}