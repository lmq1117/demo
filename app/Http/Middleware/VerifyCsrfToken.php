<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/wechat'
    ];
    
    // 在cookie中进行scrf校验
    public function handle($request, \Closure $next) {
        return parent::addCookieToResponse($request, $next($request));
    }
}
