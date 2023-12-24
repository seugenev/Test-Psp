<?php

namespace App\Services;

use App\Services\Notification\Notification;
use App\Services\Notification\NotificationUrlTrait;
use Illuminate\Http\Request;

class Processor
{
    use NotificationUrlTrait;

    public function __construct(
        protected Notification $notification,
        protected Request $request,
    ) { }
}
