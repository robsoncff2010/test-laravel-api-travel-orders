<?php

namespace App\Exceptions\Domain\Auth;

use DomainException;

class InvalidCredentialsException extends DomainException
{
    protected $errorCode = 'INVALID_CREDENTIALS';

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
