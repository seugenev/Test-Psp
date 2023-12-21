<?php
namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application Exception
 */
class ApplicationException extends Exception
{
    /**
     * Create an application exception.
     *
	 * @param mixed $message [optional]
	 * @param mixed $httpCode [optional]
	 * @param mixed $previous [optional]
	 *
     * @see Exception::__construct()
     */
    public function __construct($message = null, $httpCode = null, $previous = null)
    {
        parent::__construct(
            $message ?? 'Something went wrong!',
            $httpCode ?? Response::HTTP_INTERNAL_SERVER_ERROR,
            $previous
        );
    }

    /**
     * Get HTTP error code.
     *
     * It's equivalent to Exception::getCode method.
     *
     * @return mixed
     * @see getCode()
     */
    public function getHttpCode(): mixed
    {
        return $this->getCode();
    }
}
