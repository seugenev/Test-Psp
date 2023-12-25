<?php

namespace App\Services\Notification;

use App\Exceptions\ApplicationException;

trait NotificationUrlTrait
{
    /**
     * Set wallet notification url for the defined PSP
     * accept a pair of search replacement
     *
     * @param array $replace [[search1, search2], [replace1, replace2]] or ['search', 'replace']
     * @return string
     * @throws ApplicationException
     */
    public function getCardUrl(array $replace = []): string
    {
        $url = config('psp.' . request()->header('Test-Psp') . '.notification_url.card');
        if (!$url) {
            throw new ApplicationException('Unable to determine ' . request()->header('Test-Psp') . ' notification_url.card');
        }

        return count($replace) === 2 ? str_replace($replace[0], $replace[1], $url) : $url;
    }

    /**
     * Set wallet notification url for the defined PSP
     * accept a pair of search replacement
     *
     * @param array $replace [[search1, search2], [replace1, replace2]] or ['search', 'replace']
     * @return string
     * @throws ApplicationException
     */
    public function getWalletUrl(array $replace = []): string
    {
        $url = config('psp.' . request()->header('Test-Psp') . '.notification_url.wallet');
        if (!$url) {
            throw new ApplicationException('Unable to determine ' . request()->header('Test-Psp') . ' notification_url.wallet');
        }

        return count($replace) === 2 ? str_replace($replace[0], $replace[1], $url) : $url;
    }
}
