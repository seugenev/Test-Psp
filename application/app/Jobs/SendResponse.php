<?php

namespace App\Jobs;

use App\Exceptions\ApplicationException;
use App\Services\Notification\Notification;
use app\Structures\Dto\ResponseDto;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendResponse implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected ResponseDto $responseDto)
    { }

    /**
     * Execute the job.
     * @throws ApplicationException
     */
    public function handle(Notification $notification): void
    {
//        $notification->send(
//            $this->responseDto->getPayload(),
//            $this->responseDto->getHeaders(),
//            $this->responseDto->getUrl()
//        );

        logger()->info('Job Executed', [
            '$headers' => $this->responseDto->getHeaders(),
            '$body' => $this->responseDto->getPayload(),
            '$url' => $this->responseDto->getUrl(),
        ]);
    }
}
