<?php

declare(strict_types=1);

namespace Fixture\Port\Gateway;

use Psr\SimpleCache\CacheInterface;
use Phant\Otp\Entity\Request;

final class Sender implements \Phant\Otp\Port\Gateway\Sender
{
    public function __construct(
        public CacheInterface $cache
    ) {
    }

    public function send(
        Request $request
    ): void {
        $this->cache->set(
            (string) $request->id,
            $request
        );
    }
}
