<?php

namespace App\Services\Notification;

use App\Exceptions\ApplicationException;
use App\Structures\Dto\ResponseDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Notification
{
    use NotificationUrlTrait;

    protected string $notificationUrl;

    public function __construct(protected Request $request)
    { }

    /**
     * @throws ApplicationException
     */
    public function send(ResponseDto $responseDto): void
    {
        if (empty($responseDto->getUrl()) && empty($this->notificationUrl)) {
            throw new ApplicationException('Unable to determine notification url');
        }

        $response = Http::acceptJson();
        if ($responseDto->getHeaders()) {
            $response->withHeaders($responseDto->getHeaders());
        }

//        dd($responseDto->getUrl() ?: $this->notificationUrl, $responseDto->getPayload(), $responseDto->getHeaders());
//
//        $response->post(
//            $responseDto->getUrl() ?: $this->notificationUrl,
//            $responseDto->getPayload()
//        );
    }

    /**
     * @throws ApplicationException
     */
    public function toCardUrl(array $replace = []): static
    {
        $this->notificationUrl = $this->getCardUrl($replace);
        return $this;
    }

    /**
     * @throws ApplicationException
     */
    public function toWalletUrl(array $replace = []): static
    {
        $this->notificationUrl = $this->getWalletUrl($replace);
        return $this;
    }
}
