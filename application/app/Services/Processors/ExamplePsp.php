<?php

namespace App\Services\Processors;

use App\Jobs\SendResponse;
use App\Services\Processor;
use App\Structures\Dto\ResponseDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExamplePsp extends Processor
{
    /**
     * Payout Scenario
     *
     * @throws Throwable
     */
    public function payout(): mixed
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

        $payload2 = [
            'message' => 'Another Payload',
            'status' => 'success',
            'amount' => $this->request->get('amount'),
        ];

        $headers2 = [
            'X-Authorization' => '1111111111111111111111111111111',
        ];


        $responseDto = ResponseDto::make()
            ->url($this->getCardUrl())
            ->headers($headers)
            ->payload($payload);

        $notificationDto = ResponseDto::make()
            ->url($this->getWalletUrl([['{XXX}', '{YYY}'], ['123', '456']]))
            ->headers($headers2)
            ->payload($payload2);

        // sending immediate response with the pending status
        $this->notification->send($responseDto);

        // sending notification with the final transaction status
        dispatch((new SendResponse($notificationDto))->delay(20));

        // another way with the chains and bus
        Bus::chain([
            Bus::batch([
                (new SendResponse($responseDto)),
            ]),
            Bus::batch([
                (new SendResponse($notificationDto))->delay(20),
            ]),
        ])->dispatch();

        // if required instant response as an answer to the request instead of sending separate notification
        return new JsonResponse(
            $responseDto->getPayload(),
            Response::HTTP_OK,
            $responseDto->getHeaders()
        );

//        dispatch((new SendResponse($headers, $payload)));
//        $this->notification->send($payload, $headers, 'https://google.com');
//        $this->notification->card()->send($payload, $headers);
//        $this->notification->wallet([['{XXX}', '{YYY}'], ['123', '456']])->send($payload, $headers);
//        $this->notification->wallet(['{XXX}','123'])->send($payload, $headers);
    }

}
