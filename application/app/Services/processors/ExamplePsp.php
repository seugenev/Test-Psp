<?php

namespace App\Services\processors;

use App\Exceptions\ApplicationException;
use App\Services\Processor;

class ExamplePsp extends Processor
{
    /**
     * Payout Scenario
     *
     * @throws ApplicationException
     */
    public function payout(): void
    {
        'here a lot of calculations';

        $payload = [
            'message' => 'hello world',
            'status' => 'success',
            'amount' => $this->request->get('amount'),
        ];

        $headers = [
            'X-Authorization' => '2349yu3f9hng9832h493ewv3ohn',
        ];

//        $this->notification->send($payload, $headers, 'https://google.com');
//        $this->notification->card()->send($payload, $headers);
        $this->notification->wallet([['{XXX}', '{YYY}'], ['123', '456']])->send($payload, $headers);
//        $this->notification->wallet(['{XXX}','123'])->send($payload, $headers);
    }

}
