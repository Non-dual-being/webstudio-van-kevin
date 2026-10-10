<?php

declare(strict_types=1);

namespace Demo\Contracts;

use DateTimeImmutable;

interface Clock
{
    public function today(): DateTimeImmutable;
}
