<?php

declare(strict_types=1);

namespace gdianov\opium;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}