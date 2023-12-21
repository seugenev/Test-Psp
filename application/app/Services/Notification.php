<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Notification
{
    private string $url = '';

    public function __construct(protected Request $request)
    { }


    /**
     * @throws ApplicationException
     */
    public function send(array $payload, array $headers = [], string $url = ''): void
    {
        if (empty($url) && empty($this->url)) {
            throw new ApplicationException('Unable to determine notification url');
        }

        $notification = Http::acceptJson();
        if ($headers) {
            $notification->withHeaders($headers);
        }

        dd($url ?: $this->url, $payload, $headers);

        $notification->post(
            $url ?: $this->url,
            $payload
        );
    }


    /**
     * Set wallet notification url for the defined PSP
     * accept a pair of search replacement
     *
     * @param array $replace [[search1, search2], [replace1, replace2]] or ['search', 'replace']
     * @return $this
     * @throws ApplicationException
     */
    public function card(array $replace = []): self
    {
        $url = config('psp.' . $this->request->header('Test-Psp') . '.notification_url.card');
        if (!$url) {
            throw new ApplicationException('Unable to determine ' . request()->header('Test-Psp') . ' notification_url.card');
        }

        $this->url = count($replace) === 2 ? str_replace($replace[0], $replace[1], $url) : $url;
        return $this;
    }

    /**
     * Set wallet notification url for the defined PSP
     * accept a pair of search replacement
     *
     * @param array $replace [[search1, search2], [replace1, replace2]] or ['search', 'replace']
     * @return $this
     * @throws ApplicationException
     */
    public function wallet(array $replace = []): self
    {
        $url = config('psp.' . $this->request->header('Test-Psp') . '.notification_url.wallet');
        if (!$url) {
            throw new ApplicationException('Unable to determine ' . request()->header('Test-Psp') . ' notification_url.wallet');
        }

        $this->url = count($replace) === 2 ? str_replace($replace[0], $replace[1], $url) : $url;
        return $this;
    }
}
