<?php

namespace App\Services;

use Illuminate\Http\Request;

class Processor
{
    public function __construct(
        protected Notification $notification,
        protected Request $request,
    ) { }
}
