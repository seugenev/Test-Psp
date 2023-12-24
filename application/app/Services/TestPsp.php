<?php

namespace App\Services;

use Exception;

/**
 * This class method need to be called at the place where headers are sent
 * to the Psp/Apm to determine the processor and send it name in the custom
 * header "Test-Psp" which test-psp tool relies on.
 */
class TestPsp
{
    /**
     * Deepness of the trace
     * @var int
     */
    private static int $traceLimit = 6;

    /**
     * Determines the Psp/Apm processor which was used to make method call
     * Sample usage:
     *   $pspName = TestPsp::detectProcessor();
     *   header('Test-Psp: ' . $pspName);
     *
     * @param string $needle folder name of the Processors location
     * @return string
     * @throws Exception
     */
    public static function detectProcessor(string $needle = 'Processors'): string
    {
        $pspInitiator = '';
        $trace = debug_backtrace(self::$traceLimit);
        foreach ($trace as $element) {
            if (str_contains($element['file'], $needle)) {
                $pspInitiator = basename($element['file'], '.php');
                break;
            }
        }

        if (!$pspInitiator) {
            throw new Exception('PSP was not defined');
        }

        return $pspInitiator;
    }
}
