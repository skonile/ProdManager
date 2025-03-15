<?php
declare(strict_types = 1);

namespace App\Api\V1;

use App\Request;

class JWT
{
    private $secretKey;
    private $algorithm;

    public function __construct($secretKey, $algorithm = 'HS256')
    {
        $this->secretKey = $secretKey;
        $this->algorithm = $algorithm;
    }

    public function generate(int $userId, int $expirationTime = 60 * 60): string{
        $header = $this->header($this->algorithm);
        $payload = $this->payload($userId, $expirationTime);
        $signature = hash_hmac('sha256', "$header.$payload", $this->secretKey);
        return "$header.$payload.$signature";
    }

    public function verify(string $jwt): bool {
        [$header, $payload, $signature] = explode('.', $jwt);
        $validSignature = hash_hmac('sha256', "$header.$payload", $this->secretKey);
        return hash_equals($signature, $validSignature);
    }

    public function getPayload(string $jwt): ?array {
        [$header, $payload, $signature] = explode('.', $jwt);
        $decodedPayload = json_decode(base64_decode($payload), true);
        return is_array($decodedPayload)? $decodedPayload: null;
    }

    public function getJWTFromRequest(Request $request): ?string {
        $authHeader = $request->getHeader('Authorization');
        if($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches))
            return $matches[1];
        return null;
    }

    protected function header(string $algorithm): string{
        return base64_encode(
            json_encode(
                [
                    'alg' => $algorithm,
                    'typ' => 'JWT'
                ]
            )
        );
    }

    protected function payload(int $userId, int $expirationTime): string{
        return base64_encode(
            json_encode(
                [
                    'sub' => $userId,
                    'exp' => time() + $expirationTime
                ]
            )
        );
    }
}