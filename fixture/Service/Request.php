<?php

declare(strict_types=1);

namespace Fixture\Service;

use Phant\Cache\File as SimpleCache;
use Phant\Otp\Service\Request as ServiceRequest;
use Fixture\Port\Gateway\Sender as FixtureGateway;
use Fixture\Port\Repository\Request as FixtureRepository;
use Fixture\SslKey as FixtureSslKey;

final class Request
{
    public function __invoke(): ServiceRequest
    {
        return new ServiceRequest(
            new FixtureRepository(
                new SimpleCache(realpath(__DIR__ . '/../..') . '/.storage/', 'request')
            ),
            new FixtureGateway(
                new SimpleCache(realpath(__DIR__ . '/../..') . '/.storage/', 'sender')
            ),
            FixtureSslKey::get()
        );
    }
}
