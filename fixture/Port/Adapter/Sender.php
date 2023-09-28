<?php

declare(strict_types=1);

namespace Fixture\Port\Adapter;

use Psr\SimpleCache\CacheInterface;
use Phant\Otp\Entity\Otp;
use Phant\Otp\Entity\Request;

final class Sender implements \Phant\Otp\Port\Adapter\Sender
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
