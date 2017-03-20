<?php

return [

    /**
     * --------------------------------------------------------------------------
     *  Default Cache Store
     * --------------------------------------------------------------------------
     *
     *  This option controls the default cache connection that gets used while
     *  using this caching library. This connection is used when another is
     *  not explicitly specified when executing a given caching function.
     *
     */
    'default' => env('CACHE_DRIVER', 'array'),

    /**
     * --------------------------------------------------------------------------
     *  Cache Stores
     * --------------------------------------------------------------------------
     *
     *  Here you may define all of the cache "stores" for your application as
     *  well as their drivers. You may even define multiple stores for the
     *  same cache driver to group types of items stored in your caches.
     *
     */
    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
        ],

        'database' => [
            'driver'     => 'database',
            'table'      => 'cache',
            'connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path'   => storage_path() . '/framework/cache',
        ],

        'memcached' => [
            'driver'        => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl'          => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'servers'       => [
                [
                    'host'   => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port'   => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],

    ],

    /**
     * --------------------------------------------------------------------------
     *  Cache Key Prefix
     * --------------------------------------------------------------------------
     *
     *  When utilizing a RAM based store such as APC or Memcached, there might
     *  be other applications utilizing the same cache. So, we'll specify a
     *  value to get prefixed to all our keys so we can avoid collisions.
     *
     */
    'prefix' => 'deakin-b2b',

    /**
     * ---------------------------------------------------------------------
     *  Taxonomy Cache
     * ---------------------------------------------------------------------
     *  How long taxonomies should be cached for in minutes
     *
     */
    'taxonomy_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  Page Cache
     * ---------------------------------------------------------------------
     *  How long pages should be cached for in minutes
     *
     */
    'page_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  Attachment Cache
     * ---------------------------------------------------------------------
     *  How long attachments should be cached for in minutes
     *
     */
    'attachment_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  Menu Cache
     * ---------------------------------------------------------------------
     *  How long menus should be cached for in minutes
     *
     */
    'menu_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  URL Cache
     * ---------------------------------------------------------------------
     *  How long dynamic slug based URLs should be cached for in minutes
     *
     */
    'url_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  ACL Cache
     * ---------------------------------------------------------------------
     *  How long ACL should be cached for in minutes
     *
     */
    'acl_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  Variable Cache
     * ---------------------------------------------------------------------
     *  How long variables should be cached for in minutes
     *
     */
    'variable_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  Banner Cache
     * ---------------------------------------------------------------------
     *  How long variables should be cached for in minutes
     *
     */
    'banner_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  Blocks Cache
     * ---------------------------------------------------------------------
     *  How long variables should be cached for in minutes
     *
     */
    'block_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  Icons Cache
     * ---------------------------------------------------------------------
     *  How long icons should be cached for in minutes
     *
     */
    'icons_cache' => 60,

    /**
     * ---------------------------------------------------------------------
     *  Pages that don't need to be cached
     * ---------------------------------------------------------------------
     *
     */
    'exclusions' => [
        'logout',
        'login',
        'admin',
        'admin/*',
        'password/*',
        'activate',
        'activate/*',
    ],
];
