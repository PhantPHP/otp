<?php

declare(strict_types=1);

namespace Phant\Otp\Service;

use Phant\DataStructure\Key\Ssl as SslKey;
use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;
use Phant\Otp\Entity\Otp;
use Phant\Otp\Entity\Request as EntityRequest;
use Phant\Otp\Entity\Request\Id;
use Phant\Otp\Entity\Request\State;
use Phant\Otp\Entity\Request\Token;
use Phant\Otp\Port\Adapter\Sender as AdapterSender;
use Phant\Otp\Port\Repository\Request as RepositoryRequest;
use Phant\Otp\Service\AccessToken as ServiceAccessToken;
use Phant\Otp\Service\RequestAccess as ServiceRequestAccess;
use Phant\Otp\Service\RequestAccessFromOtp as EntityRequestAccessFromOtp;

final class Request
{
    public const LIFETIME = 900; // 15 min

    public function __construct(
        protected readonly RepositoryRequest $repositoryRequest,
        protected readonly AdapterSender $adapterSender,
        protected readonly SslKey $sslKey
    ) {
    }

    public function generate(
        mixed $payload,
        int $numberOfAttemptsLimit = 3,
        int $lifetime = self::LIFETIME
    ): Token {
        $request = EntityRequest::make(
            $payload,
            $numberOfAttemptsLimit,
            $lifetime
        );

        $this->repositoryRequest->set(
            $request
        );

        $this->adapterSender->send(
            $request
        );

        return $request->tokenizeId(
            $this->sslKey
        );
    }

    public function verify(
        string|Token $requestToken,
        string|Otp $otp
    ): mixed {
        $request = $this->getFromToken(
            $requestToken
        );

        if (is_string($otp)) {
            $otp = new Otp($otp);
        }

        if (! $request->canBeSetStateTo(State::Verified)
        ||	 ! $request->canBeSetStateTo(State::Refused)) {
            throw new NotAuthorized('The verification is not authorized');
        }

        if (! $request->checkOtp($otp)) {
            if (! $request->getNumberOfRemainingAttempts()) {
                $request->setState(State::Refused);
            }

            $this->repositoryRequest->set(
                $request
            );

            throw new NotCompliant('OTP not compliant');
        }

        $request->setState(State::Verified);

        $this->repositoryRequest->set(
            $request
        );

        return $request->payload;
    }

    public function getNumberOfRemainingAttempts(
        string|Token $requestToken
    ): int {
        $request = $this->getFromToken(
            $requestToken
        );

        return $request->getNumberOfRemainingAttempts();
    }

    private function getFromToken(
        string|Token $requestToken
    ): EntityRequest {
        if (is_string($requestToken)) {
            $requestToken = new Token($requestToken);
        }

        $requestId = EntityRequest::untokenizeId(
            $requestToken,
            $this->sslKey
        );

        return $this->repositoryRequest->get(
            $requestId
        );
    }
}
