<?php

namespace App\Exceptions\Domain;

interface DomainExceptionInterface
{
    public function getStatusCode(): int;
    public function getErrorCode(): string;
}