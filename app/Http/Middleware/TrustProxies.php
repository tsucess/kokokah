<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Symfony\Component\HttpFoundation\Request;

class TrustProxies extends Middleware
{
    protected $proxies;

    // Use HEADER_X_FORWARDED_AWS_ELB or HEADER_X_FORWARDED_FOR depending on your server
    protected $headers = Request::HEADER_X_FORWARDED_FOR | 
                         Request::HEADER_X_FORWARDED_HOST | 
                         Request::HEADER_X_FORWARDED_PORT | 
                         Request::HEADER_X_FORWARDED_PROTO;
}

