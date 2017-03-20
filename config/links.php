<?php

return [

    /*
    |--------------------------------------------------------------------------
    | For links, targets available.
    |--------------------------------------------------------------------------
    |
    */

    'targets' => [
        ''       => 'Same Page',
        '_blank' => 'New Window',
        '_top'   => 'Top Window'
    ],

    /*
    |--------------------------------------------------------------------------
    | For menu attachment, default selected menu in the form.
    |--------------------------------------------------------------------------
    |
    */

    // Main Menu.
    'default_menu' => 2,

    // Ignore the admin menu.
    'all_ignore' => [1],

    // Do not attempt to maintain URL patterns.
    'no_restructure' => [1],
];
