<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'model-search/result', '/save_basic_info', '/settings/save_account_type', '/settings/save_task_type',
        '/settings/save_currency', '/settings/save_customer', '/settings/save_language', '/settings/save_category', '/settings/save_category_tag',
        '/settings/save_city', '/settings/save_country', '/settings/save_default_settings'
    ];
}
