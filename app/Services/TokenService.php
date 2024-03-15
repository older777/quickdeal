<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

/**
 * @author art
 */
class TokenService
{
    /**
     * Get User Toekn
     */
    public static function getUserToken(): ?string
    {
        if (Auth::user()) {
            $cache = 'token_'.Auth::user()->getAuthIdentifier();
            if (cache($cache)) {
                return cache($cache);
            } else {
                $token = Auth::user()->createToken(md5(Auth::user()->name.now()->format('His')))->plainTextToken;
                cache([$cache => $token], 24 * 3600);

                return $token;
            }
        } else {
            return null;
        }
    }
}
