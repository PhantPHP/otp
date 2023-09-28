<?php

declare(strict_types=1);

namespace Test\Entity\RequestAccess;

use Phant\Otp\Entity\Otp;

use Phant\Error\NotCompliant;

final class OtpTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct(): void
    {
        $result = new Otp('123456');

        $this->assertIsObject($result);
        $this->assertInstanceOf(Otp::class, $result);
    }

    public function testConstructFail(): void
    {
        $this->expectException(NotCompliant::class);

        new Otp('I23456');
    }

    public function testCheck(): void
    {
        $result = (new Otp('123456'))->check('123456');

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testCheckDifferent(): void
    {
        $result = (new Otp('123456'))->check('I23456');

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);
    }

    public function testGenerate(): void
    {
        $result = Otp::generate();

        $this->assertIsObject($result);
        $this->assertInstanceOf(Otp::class, $result);
    }
}
