<?php

declare(strict_types=1);

namespace Phant\Otp\Entity;

use Phant\DataStructure\Key\Ssl as SslKey;
use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;
use Phant\Otp\Entity\Otp;
use Phant\Otp\Entity\Request\Id;
use Phant\Otp\Entity\Request\Lifetime;
use Phant\Otp\Entity\Request\State;
use Phant\Otp\Entity\Request\Token;

final class Request
{
    public const TOKEN_PAYLOAD_LIFETIME = 'lifetime';
    public const TOKEN_PAYLOAD_EXPIRATION = 'expiration';
    public const TOKEN_PAYLOAD_ID = 'request_access_id';

    private function __construct(
        public readonly Id $id,
        public readonly Otp $otp,
        public readonly Lifetime $lifetime,
        public readonly mixed $payload,
        private readonly int $expiration,
        private State $state,
        private int $numberOfRemainingAttempts
    ) {
    }

    public static function make(
        mixed $payload,
        int $numberOfRemainingAttempts,
        int $lifetime
    ): self {
        if ($numberOfRemainingAttempts < 1) {
            throw new NotCompliant('the number of attempts must be at least 1');
        }

        return new self(
            Id::generate(),
            Otp::generate(),
            new Lifetime($lifetime),
            $payload,
            time() + $lifetime,
            State::Requested,
            $numberOfRemainingAttempts
        );
    }

    public function canBeSetStateTo(
        State $state
    ): bool {
        return $this->state->canBeSetTo($state);
    }

    public function setState(
        State $state
    ): self {
        if (!$this->state->canBeSetTo($state)) {
            throw new NotAuthorized('State can be set to set to : ' . $state->value);
        }

        $this->state = $state;

        return $this;
    }

    public function getNumberOfRemainingAttempts(
    ): int {
        return $this->numberOfRemainingAttempts;
    }

    public function checkOtp(
        string|Otp $otp
    ): bool {
        if ($this->numberOfRemainingAttempts <= 0) {
            throw new NotAuthorized('The number of attempts is reach');
        }

        if (is_string($otp)) {
            $otp = new Otp($otp);
        }

        $this->numberOfRemainingAttempts--;

        return $this->otp->check($otp);
    }

    public function tokenizeId(
        SslKey $sslKey
    ): Token {
        $id = (string)$this->id;

        $datas = [
            self::TOKEN_PAYLOAD_LIFETIME => $this->lifetime,
            self::TOKEN_PAYLOAD_EXPIRATION => $this->expiration,
            self::TOKEN_PAYLOAD_ID => $id,
        ];

        $token = $sslKey->encrypt(
            json_encode($datas)
        );

        $token = strtr(base64_encode($token), '+/=', '._-');

        return new Token($token);
    }

    public static function untokenizeId(
        Token $token,
        SslKey $sslKey
    ): Id {
        $token = base64_decode(strtr((string)$token, '._-', '+/='));

        $datas = json_decode(
            $sslKey->decrypt($token),
            true
        );

        $expiration = $datas[ self::TOKEN_PAYLOAD_EXPIRATION ] ?? 0;

        if ($expiration < time()) {
            throw new NotCompliant('Token expired');
        }

        $id = $datas[ self::TOKEN_PAYLOAD_ID ];

        return new Id($id);
    }
}
