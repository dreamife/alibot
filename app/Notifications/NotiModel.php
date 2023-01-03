<?php
/**
 * Created by PhpStorm.
 * User: dreamife
 * Date: 2022-04-17
 * Time: 13:25
 */

namespace App\Notifications;


use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class NotiModel
{
    use Notifiable {
        notify as traitNotify;
    }

    public $msg;
    public $url;
    public $title;

    private $supressFor = 0;

    public static function new($title, $msg, $url = "")
    {
        $noti = new static();
        $noti->title = $title;
        $noti->msg = $msg;
        $noti->url = substr($url, 0, 500);
        return $noti;
    }

    public function supressFor($seconds) {
        $this->supressFor = $seconds;
        return $this;
    }

    public function notify($instance) {
        if($this->supressFor) {
            if(Cache::get(md5($this->msg))) return ;
            Cache::put(md5($this->msg),1, $this->supressFor);
            return $this->traitNotify($instance);
        } else {
            return $this->traitNotify($instance);
        }
    }

    public function routeNotificationForPushover()
    {
        return "uutZpXsFLsoeyGYEQUp5yYLgfyag8S";
    }

}