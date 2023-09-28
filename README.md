# OTP

## Presentation

The OTP service is designed to manage OTPs (One Time Passwords) that can be used for authentication, confirmation or any other validation requirement for a user.

Process :

1. The service generate an OTP and send it to the user,
2. The user receives the OTP,
3. The application retrieves the OTP from the user,
4. The application verifies the OTP with the service.

The OTP is sent to the user by your own OtpSender service (e-mail, SMS, etc.).


## Technologies used

- `PHP 8.2`
- `Composer` for dependencies management (PHP)


## Installation

`composer install`


## Usage

### Config

```php
use Phant\DataStructure\Key\Ssl as SslKey;
use Phant\Otp\Service\Request as Service;
use App\OtpRepository;
use App\OtpSender;

// Config

$otpRepository = new OtpRepository();
$otpSender = new OtpSender();
$sslKey = new SslKey($privateKey, $publicKey);


// Build service

$service = new Service(
	$otpRepository,
	$otpSender,
	$sslKey
);

```


### Request OTP

```php
// OTP context transmitted to sender
$payload = [
	'email' => 'willy@wonka.com',
];

$requestToken = $service->generate(
	$payload
);
```


### Verify OTP

```php
use Phant\Error\NotCompliant;

// Request token obtained previously
$requestToken = '...';

// Obtain Otp from user
$otp = '123456';

try {
	$payload = $service->verify(
		$requestToken,
		$otp
	);
} catch (NotCompliant $e) {
	$numberOfAttemptsRemaining = $otpService->getNumberOfRemainingAttempts(
		$requestToken
	);
}

```
