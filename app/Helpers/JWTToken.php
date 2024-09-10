<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;



class JWTToken
{
    public static function createToken($userEmail, $userId, $expiryTime = 3600): string
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'pos-app',
            'iat' => time(),
            'exp' => time() + $expiryTime,
            'email' => $userEmail,
            'id' => $userId
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function decodeToken($token)
    {
        try {
            if ($token == null) {
                return 'Unauthorized';
            }
            $key = env('JWT_KEY');

            if (self::isTokenInvalid($token)) {
                return 'Unauthorized';
            }

            return JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Throwable $th) {
            return 'Unauthorized';
        }
    }

    public static function invalidateToken($token)
    {
        Cache::put($token, true, now()->addMonth());
    }

    public static function isTokenInvalid($token)
    {
        return Cache::has($token);
    }
}