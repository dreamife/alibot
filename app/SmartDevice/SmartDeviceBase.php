<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-12-13
 * Time: 08:17
 */

namespace App\SmartDevice;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SmartDeviceBase
{

    public function getAskedInfo() {

    }

    public function deviceStatus($device, $status) {
        Storage::put("device_status", date("Y-m-d H:i:s").", $device, $status\n");
    }
}