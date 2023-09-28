<?php

declare(strict_types=1);

namespace Fixture;

use Phant\DataStructure\Key\Ssl;

final class SslKey
{
    private const PRIVATE = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDSuaKgYq3rUx+kfmH/SAoiji7p9xRqSs+9D/3XA9FX4KVq0LFo
7H7CMbkbw/FXz7zvS+GaobF+P/U52oqC9s3ATagm7uewI6kGT64V6smtU2UqRP3f
JxleahDmuCoBr/pnZu/5MBN8uHo3t4ABNpccHohpdEMfkL84vMtl9UObUwIDAQAB
AoGBAIlfg2lTa1YSJnqx+WjWqeTFFEhqTuDMTIlQN4dIcdz8ElqEGmPwaOJoT+iN
4HQCdYT6zuRjrLZFM7S3h7zA8HfRJlu4nnFS+aLiGdcrJodDLBTM6zwMF243koS7
xF0aaTlMYsfC0ic4xW8S+bqOPeOe7sVVoIxUQKsb5vdhubJhAkEA/amrRanrjNgu
wQb5mZv0WlRPiv0N3EsJ/j7hquHKgD5zLD2QLCa1HxmsDOwh6GNHWEPIMF0Z/4aF
IRZvnHQNNwJBANSqr7mrk6Nt2AnFX9QUnSLYe3j6ddXMX+I9qEJEr+VGurJqYtiU
Ze/TNVsYehb0O8JY1UwDJ6UJcODjtCruEMUCQQD8ilIw/hW72HLbzDTtoJ2q3KuA
haWp/69IR5Rmi3sPKJ2DmxsRScwi1W08RE8RzN1326vPsrEye9vI8ExYKBYLAkBe
WEKQ6g8bR5W57/ftTB/R35wXNXWlHX/EDHpiu7oUyuX0VMH5Nwxp8pcPDLLNEBia
xXIKwLOLwb5z5lB9YxPJAkAiJmO9buLWVeJvrQB6HR638pctMaXkuNUDMpA+PPOl
tXqe6pq1w3XUZ61GuxlrIR+c/IMWtBPuqS4K4Po2/CSU
-----END RSA PRIVATE KEY-----
EOD;

    private const PUBLIC = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDSuaKgYq3rUx+kfmH/SAoiji7p
9xRqSs+9D/3XA9FX4KVq0LFo7H7CMbkbw/FXz7zvS+GaobF+P/U52oqC9s3ATagm
7uewI6kGT64V6smtU2UqRP3fJxleahDmuCoBr/pnZu/5MBN8uHo3t4ABNpccHohp
dEMfkL84vMtl9UObUwIDAQAB
-----END PUBLIC KEY-----
EOD;

    public static function get(): Ssl
    {
        return new Ssl(
            self::PRIVATE,
            self::PUBLIC
        );
    }
}
