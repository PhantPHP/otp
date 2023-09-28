<?php

declare(strict_types=1);

namespace Fixture\Entity;

use Phant\Otp\Entity\Otp;
use Phant\Otp\Entity\Request as EntityRequest;
use Phant\Otp\Entity\Request\State;

final class Request
{
    public static function get(
        mixed $payload = null,
        int $numberOfAttemptsLimit = 3,
        int $lifetime = 900
    ): EntityRequest {
        $payload ??= ['foo' => 'bar'];

        return EntityRequest::make(
            $payload,
            $numberOfAttemptsLimit,
            $lifetime
        );
    }

    public static function getExpired(
        int $numberOfAttemptsLimit = 3
    ): EntityRequest {
        return self::get(null, $numberOfAttemptsLimit, -9999);
    }

    public static function getVerified(
    ): EntityRequest {
        return (self::get())
            ->setState(State::Verified);
    }
}
