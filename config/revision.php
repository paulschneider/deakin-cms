<?php

return [

    'table'          => 'revisions',

    'status'         => [
        'current' => 'Current',
        'draft'   => 'Draft',
        'archive' => 'Archive',
        'preview' => 'Preview',
    ],

    // Default fallback for items being saved over
    'status_archive' => 'archive',

    // Default status for current items/
    'status_draft'   => 'draft',

    // Default status for current items/
    'status_current' => 'current',

    // Some logic/
    'operation'      => [
        'update' => [],
        'create' => ['current'],
    ],
];
