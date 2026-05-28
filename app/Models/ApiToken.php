<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'token', 'permissions'];

    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * Validate a given token against the stored hash.
     */
    public static function validateToken(string $tokenName, string $token): bool
    {
        $apiToken = self::where('name', $tokenName)->first();
        if (! $apiToken) {
            return false;
        }

        return hash_equals($apiToken->token, self::hashToken($token));
    }

    public function decodedPermissions(): array
    {
        $permissions = json_decode($this->permissions ?? '[]', true);

        return is_array($permissions) ? $permissions : [];
    }

    public function allowsRoute(?string $routeName): bool
    {
        return $routeName !== null
            && in_array($routeName, $this->decodedPermissions(), true);
    }
}
