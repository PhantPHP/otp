<?php

declare(strict_types=1);

namespace Test\Entity\Request;

use Phant\Otp\Entity\Request\State;

final class StateTest extends \PHPUnit\Framework\TestCase
{
    public function testCases(): void
    {
        $this->assertCount(4, State::cases());
    }

    public function testGetLabel(): void
    {
        $this->assertIsString(State::Requested->getLabel());
        $this->assertIsString(State::Refused->getLabel());
        $this->assertIsString(State::Verified->getLabel());
        $this->assertIsString(State::Granted->getLabel());
    }

    public function testCanBeSetTo(): void
    {
        // Requested to ...
        $result = State::Requested->canBeSetTo(State::Requested);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Requested->canBeSetTo(State::Refused);

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);


        $result = State::Requested->canBeSetTo(State::Verified);

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);


        $result = State::Requested->canBeSetTo(State::Granted);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        // Refused to ...
        $result = State::Refused->canBeSetTo(State::Requested);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Refused->canBeSetTo(State::Refused);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Refused->canBeSetTo(State::Verified);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Refused->canBeSetTo(State::Granted);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        // Verified to ...
        $result = State::Verified->canBeSetTo(State::Requested);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Verified->canBeSetTo(State::Refused);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Verified->canBeSetTo(State::Verified);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Verified->canBeSetTo(State::Granted);

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);


        // Granted to ...
        $result = State::Granted->canBeSetTo(State::Requested);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Granted->canBeSetTo(State::Refused);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Granted->canBeSetTo(State::Verified);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);


        $result = State::Granted->canBeSetTo(State::Granted);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);
    }
}
