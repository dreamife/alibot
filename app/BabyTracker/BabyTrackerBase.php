<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-06-05
 * Time: 16:54
 */

namespace App\BabyTracker;


use App\Lib\BabyTrackerRequest;
use Illuminate\Http\Request;

class BabyTrackerBase
{
    protected $babyTrackerRequest;

    public function __construct(BabyTrackerRequest $request)
    {
        $this->babyTrackerRequest = $request;
    }

    public function getAskedInfo() {

    }
}