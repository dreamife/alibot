<?php

namespace App\Http\Controllers;

use App\AliBot\AliBotService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $alibotService;
    public function __construct(AliBotService $aliBotService)
    {
        $this->alibotService = $aliBotService;
    }

    public function aliGenie(Request $request) {
        Log::info(json_encode($request->all(), JSON_UNESCAPED_UNICODE));
        return new JsonResponse($this->alibotService->result($request));
    }
}
