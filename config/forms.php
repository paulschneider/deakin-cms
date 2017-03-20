<?php
/*
|---------------------------------------------------------------------
| The types of forms
|---------------------------------------------------------------------
| Define any forms with it's rules
| first_name, last_name, email, ip_address are required globally,
| so they don't need to added to the form rules here
 */
$forms = [];

$days   = array_map_assoc(range(1, 31), 'Day');
$months = array_map_assoc(range(1, 12), 'Month');
$years  = array_map_assoc(range(1940, date('Y')), 'Year');

$contact_form_types = [
    'meeting'  => 'Enquiry',
    'pilot'    => 'Pilot',
    'insights' => 'Workforce Insights',
    'intel'    => 'Workforce Intelligence',
];

// Contact form
$forms['contact'] = [
    'type'           => 'contact',
    'rules'          => [
        'name'                 => 'required',
        'message'              => 'required',
        'reason'               => 'in:' . implode(',', array_keys($contact_form_types)),
        'g-recaptcha-response' => 'required|recaptcha',
    ],
    'submission'     => [
        'subject' => 'Contact Form',
        'to'      => [
            'Website Administrator' => 'support@deakindigital.com',
            // 'Website Administrator' => 'al.munnings@iconinc.com.au',
        ],
    ],
    'options'        => [
        'reason' => $contact_form_types,
    ],
    // Paramaters to preserve in URL.
    'url_paramaters' => ['modal', 'reason'],
    'redirect'       => 'contact/thankyou',
];

// Contact form
$forms['newsletter'] = [
    'type'           => 'newsletter',
    'rules'          => [
        'name'    => 'required',
        'surname' => 'required',
        'agree'   => 'accepted|required',
    ],
    'submission'     => [
        'subject' => 'Newsletter Signup',
        'to'      => [
            'Website Administrator' => 'support@deakindigital.com',
            // 'Website Administrator' => 'al.munnings@iconinc.com.au',
        ],
    ],
    // Paramaters to preserve in URL.
    'url_paramaters' => ['modal'],
    'redirect'       => 'newsletter/thankyou',
];

/*
|---------------------------------------------------------------------
| Return the built array
|---------------------------------------------------------------------
|
 */
return [

    'default_rules' => [
        'email'      => 'email|required',
        'redirect'   => 'required',
        'uri'        => 'required',
        'type'       => 'required',
        'ip_address' => 'required',
        'modal'      => '',
    ],

    // The available forms types
    'forms' => $forms,

    // Statuses that can be set
    'status' => [
        'unread'   => 'Unread',
        'read'     => 'Read',
        'actioned' => 'Actioned',
    ],
];
