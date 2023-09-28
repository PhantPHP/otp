<?php

declare(strict_types=1);

namespace Test\Service;

use Phant\Cache\File as SimpleCache;
use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;
use Phant\Otp\Entity\Request;
use Phant\Otp\Entity\Request\Token;
use Phant\Otp\Service\Request as ServiceRequest;
use Fixture\Entity\Request as FixtureRequest;
use Fixture\Sslkey as FixtureSslkey;
use Fixture\Port\Adapter\Sender as FixturePortOtpSender;
use Fixture\Service\Request as FixtureServiceRequest;

final class RequestTest extends \PHPUnit\Framework\TestCase
{
    protected ServiceRequest $service;
    protected Token $fixture;
    protected SimpleCache $cache;

    public function setUp(): void
    {
        $this->service = (new FixtureServiceRequest())();
        $this->fixture = $this->service->generate(
            [
                'foo' => 'bar',
            ]
        );
        $this->cache = new SimpleCache(realpath(__DIR__ . '/../..') . '/.storage/', 'sender');
    }

    public function testGenerate(): void
    {
        $this->assertIsObject($this->fixture);
        $this->assertInstanceOf(Token::class, $this->fixture);
    }

    public function testGenerateInvalid(): void
    {
        $this->expectException(NotCompliant::class);

        $this->service->generate(
            null,
            0
        );
    }

    public function testVerify(): void
    {
        $request = $this->cache->get(
            (string) Request::untokenizeId(
                $this->fixture,
                FixtureSslkey::get()
            )
        );

        $result = $this->service->verify(
            (string) $this->fixture,
            $request->otp
        );

        $this->assertEquals($request->payload, $result);
    }

    public function testVerifyInvalid(): void
    {
        try {
            $this->service->verify(
                $this->fixture,
                '000000'
            );
        } catch (NotCompliant $e) {
        }

        $result = $this->service->getNumberOfRemainingAttempts(
            $this->fixture
        );
        $this->assertEquals(2, $result);

        try {
            $this->service->verify(
                $this->fixture,
                '000000'
            );
        } catch (NotCompliant $e) {
        }

        $result = $this->service->getNumberOfRemainingAttempts(
            $this->fixture
        );
        $this->assertEquals(1, $result);

        try {
            $this->service->verify(
                $this->fixture,
                '000000'
            );
        } catch (NotCompliant $e) {
        }

        $result = $this->service->getNumberOfRemainingAttempts(
            $this->fixture
        );
        $this->assertEquals(0, $result);
    }

    public function testVerifyNotAuthorized(): void
    {
        $this->expectException(NotAuthorized::class);

        $request = $this->cache->get(
            (string) Request::untokenizeId(
                $this->fixture,
                FixtureSslkey::get()
            )
        );

        $result = $this->service->verify(
            $this->fixture,
            $request->otp
        );

        $this->assertEquals($request->payload, $result);

        $this->service->verify(
            $this->fixture,
            $request->otp
        );
    }

    public function testVerifyNotCompliant(): void
    {
        $this->expectException(NotCompliant::class);

        $this->service->verify(
            $this->fixture,
            '000000'
        );
    }

    public function testGetNumberOfRemainingAttempts(): void
    {
        $result = $this->service->getNumberOfRemainingAttempts(
            (string) $this->fixture
        );

        $this->assertIsInt($result);
        $this->assertEquals(3, $result);
    }
}
