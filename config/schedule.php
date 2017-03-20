<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Schedule allowed commands
    |--------------------------------------------------------------------------
    |
     */

    // The available options.
    'allowed'                  => [
        ''                  => 'No action',
        'cron:offline'      => 'Take offline',
        'cron:online'       => 'Take online, visible to public',
        'cron:draft-online' => 'If draft, set current & make visible to public',
    ],

    // Relationship to load and attach to.
    'relationship'             => [
        'cron:draft-online' => 'revision',
    ],

    // Little bit of where = value logic here.
    'relationship_constraints' => [
        'cron:draft-online' => ['revision' => ['status' => 'draft']],
    ],

    // Only one per parent entity may exist.
    'only_one'                 => [
        'cron:draft-online' => true,
    ],
];
