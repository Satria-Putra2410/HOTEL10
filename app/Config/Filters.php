<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things a little nicer and simpler.
     *
     * @var array<string, array<string, array<string, string>>>
     * @phpstan-var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => \App\Filters\AuthFilter::class,   // Alias untuk AuthFilter
        'admin'         => \App\Filters\AdminFilter::class,  // Alias untuk AdminFilter
        'tamu'          => \App\Filters\TamuFilter::class,   // PERBAIKAN: Alias untuk TamuFilter ditambahkan di sini
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>
     */
    public array $globals = [
        'before' => [
            // 'csrf', // Aktifkan CSRF jika diperlukan, tapi bisa menyebabkan masalah di awal pengembangan
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * List of filter classes that are used on a
     * particular route (specific to one route).
     *
     * Example:
     * 'users' => ['role:admin', 'throttle']
     *
     * @var array<string, list<string>>
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'admin/*', // Melindungi semua rute di bawah grup 'admin'
                'tamu/*',  // Melindungi semua rute di bawah grup 'tamu'
            ],
        ],
        'admin' => [
            'before' => [
                'admin/*', // Memastikan hanya admin yang bisa mengakses rute admin
            ],
        ],
        'tamu' => [
            'before' => [
                'tamu/*', // Memastikan hanya tamu yang bisa mengakses rute tamu
            ],
        ],
    ];
}

