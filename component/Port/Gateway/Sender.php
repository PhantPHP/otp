<?php

declare(strict_types=1);

namespace Phant\Otp\Port\Gateway;

use Phant\Otp\Entity\Request;

interface Sender
{
    public function send(
        Request $request
    ): void;
}
