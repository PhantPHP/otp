<?php

declare(strict_types=1);

namespace Phant\Otp\Port\Repository;

use Phant\Otp\Entity\Request as EntityRequest;
use Phant\Otp\Entity\Request\Id;

interface Request
{
    public function set(
        EntityRequest $request
    ): void;

    public function get(
        Id $id
    ): EntityRequest;
}
