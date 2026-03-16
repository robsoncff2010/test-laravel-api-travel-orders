<?php

namespace App\Exceptions\Domain\TravelOrder;

use App\Exceptions\Domain\DomainExceptionInterface;
use DomainException;

class InvalidOrderStatusException extends DomainException implements DomainExceptionInterface
{
    protected $errorCode = 'ORDER_INVALID_STATUS';

    public function getStatusCode(): int
    {
        return 422;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
