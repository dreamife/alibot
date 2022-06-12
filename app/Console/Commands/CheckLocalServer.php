<?php

namespace App\Console\Commands;

use App\Lib\LocalPCForwarder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckLocalServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:local';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $res = LocalPCForwarder::getInstance()->test();
        if(($res['msg'] ?? "") == "ok") {
            Cache::put("localServerLive", 1);
        } else {
            Cache::put("localServerLive", 0);
        }
        return 0;
    }
}
