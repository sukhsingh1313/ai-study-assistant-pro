<?php

use Illuminate\Support\Str;

$databaseUrl = env('DATABASE_URL');
$dbHost = env('DB_HOST', '127.0.0.1');
$dbPort = env('DB_PORT', '3306');
$dbDatabase = env('DB_DATABASE', 'ai_study_assistant');
$dbUsername = env('DB_USERNAME', 'root');
$dbPassword = env('DB_PASSWORD', '');

if ($databaseUrl) {
    $parsedUrl = parse_url($databaseUrl);
    if (isset($parsedUrl['host'])) {
        $dbHost = $parsedUrl['host'];
    }
    if (isset($parsedUrl['port'])) {
        $dbPort = $parsedUrl['port'];
    }
    if (isset($parsedUrl['path'])) {
        $dbDatabase = ltrim($parsedUrl['path'], '/');
    }
    if (isset($parsedUrl['user'])) {
        $dbUsername = $parsedUrl['user'];
    }
    if (isset($parsedUrl['pass'])) {
        $dbPassword = $parsedUrl['pass'];
    }
}

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => $dbHost,
            'port' => $dbPort,
            'database' => $dbDatabase,
            'username' => $dbUsername,
            'password' => $dbPassword,
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => $dbHost,
            'port' => env('DB_PORT', $dbPort ?: '5432'),
            'database' => $dbDatabase,
            'username' => $dbUsername,
            'password' => $dbPassword,
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => env('DB_SSLMODE', 'prefer'),
        ],

    ],

    'migrations' => 'migrations',

    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],

];
