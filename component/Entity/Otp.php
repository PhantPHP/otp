<?php

declare(strict_types=1);

namespace Phant\Otp\Entity;

final class Otp extends \Phant\DataStructure\Abstract\Value\Varchar
{
    public const PATTERN = '/^\d{6}$/';
    public const LENGTH = 6;

    public function __construct(
        string $otp
    ) {
        $otp = trim($otp);

        parent::__construct($otp);
    }

    public function check(
        string|self $otp
    ): bool {
        return $this->value === (string)$otp;
    }

    public static function generate(

    ): self {
        $otp = '';

        $characters = '0123456789';
        for ($i = 0; $i < self::LENGTH; $i++) {
            $otp .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return new self($otp);
    }
}
