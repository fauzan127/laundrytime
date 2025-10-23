<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;

class VerifyCsrfToken extends Middleware
{
    protected function shouldSkipCsrfValidation(Request $request): bool
    {
        return $request->is('payment/callback');
    }
}