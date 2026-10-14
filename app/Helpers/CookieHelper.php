<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class CookieHelper
{
    /**
     * Create or queue a secure cookie.
     *
     * @param  int  $minutes  Default 60 mins
     * @param  string|null  $sameSite  'lax', 'strict', 'none'
     */
    public static function make(
        string $name,
        string $value,
        int $minutes = 60,
        ?string $path = '/',
        ?string $domain = null,
        ?bool $secure = null,
        bool $httpOnly = true,
        ?string $sameSite = 'lax'
    ): SymfonyCookie {
        return Cookie::make(
            $name,
            $value,
            $minutes,
            $path,
            $domain,
            $secure ?? config('session.secure', false),
            $httpOnly,
            false,
            $sameSite
        );
    }

    /**
     * Queue a cookie to be sent with the next response.
     */
    public static function queue(string $name, string $value, int $minutes = 60): void
    {
        Cookie::queue(self::make($name, $value, $minutes));
    }

    /**
     * Retrieve a cookie value from request.
     */
    public static function get(string $name, mixed $default = null): mixed
    {
        return request()->cookie($name, $default);
    }

    /**
     * Queue a cookie expiration to forget/delete it.
     */
    public static function forget(string $name, ?string $path = '/', ?string $domain = null): void
    {
        Cookie::queue(Cookie::forget($name, $path, $domain));
    }
}
