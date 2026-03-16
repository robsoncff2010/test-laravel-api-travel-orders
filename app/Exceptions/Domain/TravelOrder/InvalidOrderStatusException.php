<?php

namespace App\Exceptions\Domain\TravelOrder;

use DomainException;

class InvalidOrderStatusException extends DomainException
{
    protected $errorCode = 'ORDER_INVALID_STATUS';

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}