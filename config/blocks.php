<?php

// Check if defined for console.
if (!defined('BLOCK_CACHE_PER_PAGE')) {
    define('BLOCK_CACHE_PER_PAGE', 1);
}

if (!defined('BLOCK_CACHE_PERMANENTLY')) {
    define('BLOCK_CACHE_PERMANENTLY', 2);
}

if (!defined('BLOCK_CACHE_NONE')) {
    define('BLOCK_CACHE_NONE', 3);
}

/*
|---------------------------------------------------------------------
| The types blocks
|---------------------------------------------------------------------
| Define any blocks with it's rules
| They must have ['name', 'description', 'admin_template', 'public_template', 'fields', 'rules', 'cache']
|
 */
$blocks = [];

// One column wysiwyg
$blocks['one_column_wysiwyg'] = [
    'name'            => 'One Column Wysiwyg',
    'description'     => 'A one column block that has a WYSIWYG',
    'admin_template'  => 'admin.blocks.one_column_wysiwyg',
    'public_template' => 'frontend.blocks.one_column_wysiwyg',
    'fields'          => [
        'title',
        'body',
        'class',
    ],
    'rules'           => [
        'body' => 'required',
    ],
    'cache'           => BLOCK_CACHE_PERMANENTLY,
];

// One Column Registered
$blocks['one_column_registered'] = [
    'name'            => 'One Column Registered',
    'description'     => 'A one column block that shows dynamic content from a registered handler',
    'admin_template'  => 'admin.blocks.one_column_registered',
    'public_template' => 'frontend.blocks.one_column_registered',
    'fields'          => [
        'col_one_method',
        'col_one_title',
        'col_one_class',
        'col_one_body',
    ],
    'rules'           => [
        'col_one_method' => 'required',
    ],
    'cache'           => BLOCK_CACHE_NONE,
];

/*
|---------------------------------------------------------------------
| Return the built array
|---------------------------------------------------------------------
|
 */
return [

    // The available block types
    'blocks' => $blocks,

    // If the solution has global blocks
    'regions' => [
        null      => 'None', // Leave this here
        'content' => 'Content area',
        'footer'  => 'Footer',
    ],
];
