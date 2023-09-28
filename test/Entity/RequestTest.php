<?php

declare(strict_types=1);

namespace Test\Entity;

use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;
use Phant\Otp\Entity\Otp;
use Phant\Otp\Entity\Request;
use Phant\Otp\Entity\Request\Id;
use Phant\Otp\Entity\Request\State;
use Phant\Otp\Entity\Request\Token;
use Fixture\Entity\Otp as FixtureOtp;
use Fixture\Entity\Request as FixtureRequest;
use Fixture\SslKey as FixtureSslKey;

final class RequestTest extends \PHPUnit\Framework\TestCase
{
    protected Request $fixture;
    protected Request $fixtureExpired;
    protected Request $fixtureVerified;

    public function setUp(): void
    {
        $this->fixture = FixtureRequest::get();
        $this->fixtureExpired = FixtureRequest::getExpired();
        $this->fixtureVerified = FixtureRequest::getVerified();
    }

    public function testMake(): void
    {
        $payload = ['foo' => 'bar'];
        $numberOfRemainingAttempts = 3;
        $lifetime = 900;

        $entity = Request::make(
            $payload,
            $numberOfRemainingAttempts,
            $lifetime
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Request::class, $entity);
        $this->assertEquals($payload, $entity->payload);
        $this->assertEquals($lifetime, $entity->lifetime->value);
        $this->assertEquals($numberOfRemainingAttempts, $entity->getNumberOfRemainingAttempts());
    }

    public function testCanBeSetStateTo(): void
    {
        $result = $this->fixture->canBeSetStateTo(State::Verified);

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testSetState(): void
    {
        $entity = $this->fixture->setState(State::Verified);

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Request::class, $entity);
    }

    public function testSetStateNotAuthorized(): void
    {
        $this->expectException(NotAuthorized::class);

        $this->fixtureVerified->setState(State::Verified);
    }

    public function testGetNumberOfRemainingAttempts(): void
    {
        $result = $this->fixture->getNumberOfRemainingAttempts();

        $this->assertIsInt($result);
        $this->assertEquals(3, $result);
    }

    public function testCheckOtpNotAuthorized(): void
    {
        $this->expectException(NotAuthorized::class);

        $result = $this->fixture->checkOtp(
            '000000'
        );
        $this->assertEquals(false, $result);

        $result = $this->fixture->getNumberOfRemainingAttempts();
        $this->assertEquals(2, $result);

        $result = $this->fixture->checkOtp(
            '000000'
        );
        $this->assertEquals(false, $result);

        $result = $this->fixture->getNumberOfRemainingAttempts();
        $this->assertEquals(1, $result);

        $result = $this->fixture->checkOtp(
            '000000'
        );
        $this->assertEquals(false, $result);

        $result = $this->fixture->getNumberOfRemainingAttempts();
        $this->assertEquals(0, $result);

        $this->fixture->checkOtp(
            '000000'
        );
    }

    public function testCheckOtp(): void
    {
        $result = $this->fixture->checkOtp(
            $this->fixture->otp
        );

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testTokenizeId(): void
    {
        $value = $this->fixture->tokenizeId(
            FixtureSslkey::get()
        );

        $this->assertIsObject($value);
        $this->assertInstanceOf(Token::class, $value);
    }

    public function testUntokenizeId(): void
    {
        $value = $this->fixture->untokenizeId(
            $this->fixture->tokenizeId(
                FixtureSslkey::get()
            ),
            FixtureSslkey::get()
        );

        $this->assertIsObject($value);
        $this->assertInstanceOf(Id::class, $value);
        $this->assertEquals($this->fixture->id, $value);
    }

    public function testUntokenizeIdNotCompliant(): void
    {
        $this->expectException(NotCompliant::class);

        $this->fixture->untokenizeId(
            $this->fixtureExpired->tokenizeId(
                FixtureSslkey::get()
            ),
            FixtureSslkey::get()
        );
    }
}
