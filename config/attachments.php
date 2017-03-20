<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Attachment Paths
    |--------------------------------------------------------------------------
    |
    | When setting the path for file uploads, use an attachment path.
    | Use this to map to a taxonomy ID.
    |
     */

    'path' => [
        'default'     => 1,
        'dropzone'    => 1,
        'pages'       => 2,
        'banners'     => 5,
        'profiles'    => 8,
        'articles'    => 42,
        'credentials' => 17,
    ],

    'vocab' => 'attachments',

    /*
    |--------------------------------------------------------------------------
    | Attachment Classes
    |--------------------------------------------------------------------------
    |
    | Defined styles used in the rendered output of an attachment
    |
     */

    'styles' => [
        'align' => [
            'left'   => ['class' => ['pull-left']],
            'right'  => ['class' => ['pull-right']],
            'center' => ['class' => ['center']],
        ],

        'sizes' => [
            'full'         => '1168x540#',
            'large'        => '650x650',
            'medium'       => '450x450',
            'thumb'        => '150x150#',
            'thumb_aspect' => '150x150',
            'micro'        => '40x40#',
        ],
    ],

    "defaults" => [
        "THIRD_PARTY_TESTIMONY"         => env("THIRD_PARTY_TESTIMONY_ATTACHMENT_ID"),
        "CREDENTIAL_SUBMISSION_PLANNER" => env("CREDENTIAL_SUBMISSION_PLANNER_ATTACHMENT_ID"),
    ],
];
