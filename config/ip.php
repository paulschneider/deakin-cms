<?php

/**
 * Decides what may enter miuddleware protected routes.
 */
return [

    'allowed'        => [
        '127.0.0.1', // Loop back
        '127.0.1.1', // Loop back
        # Individuals
        '220.240.207.62', // Icon
    ],

    'allowed_ranges' => [
        ['10.1.1.1', '10.1.1.254'], // Dev
    ],

    'activity_watch' => 30, // Time to watch in admin system.
];
