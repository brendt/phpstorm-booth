<?php

namespace Booth;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends Exception implements NotFoundExceptionInterface
{
    public function __construct(string $id)
    {
        parent::__construct("Dependency not found: $id");
    }
}