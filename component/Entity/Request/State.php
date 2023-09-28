<?php

declare(strict_types=1);

namespace Phant\Otp\Entity\Request;

enum State: string
{
    case Requested = 'requested';

    case Refused = 'refused';

    case Verified = 'verified';

    case Granted = 'granted';

    public function getLabel(): string
    {
        return match ($this) {
            self::Requested => 'Requested',
            self::Refused => 'Refused',
            self::Verified => 'Verified',
            self::Granted => 'Granted',
        };
    }

    public function canBeSetTo(
        self $state
    ): bool {
        switch ($state) {
            case State::Requested:

                break;

            case State::Refused:

                return ($this == State::Requested);

            case State::Verified:

                return ($this == State::Requested);

            case State::Granted:

                return ($this == State::Verified);
        }

        return false;
    }
}
