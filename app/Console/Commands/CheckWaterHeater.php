<?php

namespace App\Console\Commands;

use App\Notifications\NotiForAlibot;
use App\Notifications\NotiModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckWaterHeater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:water_heater';

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
        $path = Storage::path('device_status');
        exec("tail -n 1 $path", $o);
        $lastRecord = $o[0] ?? "";
        $lastData = explode(",", $lastRecord);
        if(!empty($lastData[2])) {
            $time = strtotime($lastData[0]);
            $status = $lastData[2];
            if(time() - $time > 3600) {
                NotiModel::new("Water Heater Not Turned on", "last record $lastRecord")
                    ->notify(new NotiForAlibot());
            }
        }
        return 0;
    }
}
