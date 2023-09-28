<?php

declare(strict_types=1);

namespace Phant\Otp\Port\Adapter;

use Phant\Otp\Entity\Request;

interface Sender
{
    public function send(
        Request $request
    ): void;
}
