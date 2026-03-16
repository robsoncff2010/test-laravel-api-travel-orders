<?php

namespace App\Exceptions\Domain\Auth;

use App\Exceptions\Domain\DomainExceptionInterface;
use DomainException;

class InvalidCredentialsException extends DomainException implements DomainExceptionInterface
{
    protected $errorCode = 'INVALID_CREDENTIALS';

    public function getStatusCode(): int
    {
        return 422;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
