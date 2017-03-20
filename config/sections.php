<?php

$sections = [];

// One column wysiwyg
$sections['one_column_wysiwyg'] = [
    'name'            => 'One Column Wysiwyg',
    'description'     => 'One Column Wysiwyg',
    'admin_template'  => 'admin.sections.one-column-wysiwyg',
    'public_template' => 'frontend.sections.one-column-wysiwyg',
    'class'           => 'one-column-wysiwyg',
    'fields'          => [
        'section_colors'     => 'required',
        'container_color'    => '',
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
        'section_pad_left'   => '',
        'section_pad_right'  => '',
        'background_image'   => '',
        'body'               => 'required',
    ],
    'attachments'     => [
        'background_image',
    ],
];

// The icon in the section picker
$sections['one_column_wysiwyg']['picker'] = <<<EOT
    <a href="#" class="picker one-column-wysiwyg" data-template="one_column_wysiwyg">
        <div class="inner">
            <div class="body">&nbsp;</div>
        </div>
    </a>
EOT;

// One column wysiwyg
$sections['one_column_centered_wysiwyg'] = [
    'name'            => 'One Column Centred Wysiwyg',
    'description'     => 'One Column Centred Wysiwyg',
    'admin_template'  => 'admin.sections.one-column-centered-wysiwyg',
    'public_template' => 'frontend.sections.one-column-centered-wysiwyg',
    'class'           => 'one-column-centered-wysiwyg',
    'fields'          => [
        'section_colors'     => 'required',
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
        'section_pad_left'   => '',
        'section_pad_right'  => '',
        'body'               => 'required',
    ],
];

// The icon in the section picker
$sections['one_column_centered_wysiwyg']['picker'] = <<<EOT
    <a href="#" class="picker one-column-centered-wysiwyg" data-template="one_column_centered_wysiwyg">
        <div class="inner">
            <div class="body">&nbsp;</div>
        </div>
    </a>
EOT;

// Two Columns
$sections['two_column_wysiwyg'] = [
    'name'            => 'Two Column Wysiwyg',
    'description'     => 'Two columns of wysiwyg',
    'admin_template'  => 'admin.sections.two-column-wysiwyg',
    'public_template' => 'frontend.sections.two-column-wysiwyg',
    'class'           => 'two-column-wysiwyg',
    'fields'          => [
        'section_title'      => '',
        'section_colors'     => 'required',
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
        'column_one_content' => 'required',
        'column_one_color'   => 'required',
        'column_one_arrow'   => '',
        'column_two_content' => 'required',
        'column_two_color'   => 'required',
        'column_two_arrow'   => '',
    ],
];
// The icon in the section picker
$sections['two_column_wysiwyg']['picker'] = <<<EOT
    <a href="#" class="picker two-column-wysiwyg" data-template="two_column_wysiwyg">
        <div class="inner">
            <div class="col">&nbsp;</div>
            <div class="col">&nbsp;</div>
        </div>
    </a>
EOT;

// Three Columns
$sections['three_column_wysiwyg'] = [
    'name'            => 'Three Column Wysiwyg',
    'description'     => 'Three columns of wysiwyg',
    'admin_template'  => 'admin.sections.three-column-wysiwyg',
    'public_template' => 'frontend.sections.three-column-wysiwyg',
    'class'           => 'three-column-wysiwyg',
    'fields'          => [
        'section_title'        => '',
        'section_colors'       => 'required',
        'container_color'      => '',
        'section_pad_top'      => '',
        'section_pad_bottom'   => '',
        'column_one_content'   => 'required',
        'column_one_color'     => 'required',
        'column_one_arrow'     => '',
        'column_two_content'   => 'required',
        'column_two_color'     => 'required',
        'column_two_arrow'     => '',
        'column_three_content' => 'required',
        'column_three_color'   => 'required',
        'column_three_arrow'   => '',
        'body'                 => '',
    ],
];
// The icon in the section picker
$sections['three_column_wysiwyg']['picker'] = <<<EOT
    <a href="#" class="picker three-column-wysiwyg" data-template="three_column_wysiwyg">
        <div class="inner">
            <div class="col">&nbsp;</div>
            <div class="col">&nbsp;</div>
            <div class="col">&nbsp;</div>
        </div>
    </a>
EOT;

// Two column full width
$sections['two_column_full_width_wysiwyg'] = [
    'name'            => 'Two Column Full Width Wysiwyg',
    'description'     => 'Two columns of full width wysiwyg',
    'admin_template'  => 'admin.sections.two-column-full-width-wysiwyg',
    'public_template' => 'frontend.sections.two-column-full-width-wysiwyg',
    'class'           => 'two-column-full-width-wysiwyg',
    'fields'          => [
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
        'column_one_content' => 'required_without:sections.[[counter]].column_one_video',
        'column_one_color'   => 'required',
        'column_one_image'   => '',
        'column_two_content' => 'required_without:sections.[[counter]].column_two_video',
        'column_two_color'   => 'required',
        'column_two_image'   => '',
    ],
    'attachments'     => [
        'column_one_image',
        'column_two_image',
    ],
];
// The icon in the section picker
$sections['two_column_full_width_wysiwyg']['picker'] = <<<EOT
    <a href="#" class="picker two-column-full-width-wysiwyg" data-template="two_column_full_width_wysiwyg">
        <div class="inner">
            <div class="col">&nbsp;</div>
            <div class="col">&nbsp;</div>
        </div>
    </a>
EOT;

// Download
$sections['download'] = [
    'name'            => 'Download',
    'description'     => 'File attachment block',
    'admin_template'  => 'admin.sections.download',
    'public_template' => 'frontend.sections.download',
    'class'           => 'download',
    'fields'          => [
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
        'title'              => 'required',
        'description'        => 'required',
        'action'             => '',
        'color'              => '',
        'download'           => '',
    ],
    'attachments'     => [
        'download',
    ],
];

// The icon in the section picker
$sections['download']['picker'] = <<<EOT
    <a href="#" class="picker download" data-template="download">
        <div class="inner">
            <div class="col">
                <div class="play-button"><i class="fa fa-cloud-download"></i></div>
            </div>
        </div>
    </a>
EOT;

// Video
$sections['video'] = [
    'name'            => 'Video',
    'description'     => 'Centered video block',
    'admin_template'  => 'admin.sections.video',
    'public_template' => 'frontend.sections.video',
    'class'           => 'video',
    'fields'          => [
        'section_colors'     => 'required',
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
        'title'              => '',
        'description'        => '',
        'image'              => '',
        'url'                => 'required',
        'color'              => '',
    ],
    'attachments'     => [
        'image',
    ],
];

// The icon in the section picker
$sections['video']['picker'] = <<<EOT
    <a href="#" class="picker video" data-template="video">
        <div class="inner">
            <div class="col">
                <div class="play-button"><i class="fa fa-youtube-play"></i></div>
            </div>
        </div>
    </a>
EOT;

// Video
$sections['video_testimonial'] = [
    'name'            => 'Video Testimonial',
    'description'     => 'Centered video testimonial block',
    'admin_template'  => 'admin.sections.video-testimonial',
    'public_template' => 'frontend.sections.video-testimonial',
    'class'           => 'video-testimonial',
    'fields'          => [
        'section_colors'     => 'required',
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
        'type'               => 'required',
        'title'              => 'required',
        'description'        => '',
        'position'           => '',
        'image'              => 'required',
        'url'                => 'required',
    ],
    'attachments'     => [
        'image',
    ],
];

$sections['video_testimonial']['picker'] = <<<EOT
    <a href="#" class="picker video-testimonial" data-template="video_testimonial">
        <div class="inner">
            <div class="col">
                <div class="play-button"><i class="fa fa-video-camera"></i></div>
            </div>
        </div>
    </a>
EOT;

// Video list
$sections['video_list'] = [
    'name'            => 'Video List',
    'description'     => 'Video list block',
    'admin_template'  => 'admin.sections.video-list',
    'public_template' => 'frontend.sections.video-list',
    'class'           => 'video-list',
    'fields'          => [
        'section_colors'     => 'required',
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
        'title'              => 'required',
        'description'        => '',
        'image'              => 'required',
        'url'                => 'required',
    ],
    'attachments'     => [
        'image',
    ],
];

$sections['video_list']['picker'] = <<<EOT
    <a href="#" class="picker video-list" data-template="video_list">
        <div class="inner">
            <div class="col">
                <div class="play-button"><i class="fa fa-video-camera"></i></div>
            </div>
            <div class="col">
                <div class="play-button"><i class="fa fa-video-camera"></i></div>
            </div>
        </div>
    </a>
EOT;

// Widget Blocks
$sections['block_widget'] = [
    'name'            => 'Widget',
    'description'     => 'Renders a widget',
    'admin_template'  => 'admin.sections.block-widget',
    'public_template' => 'frontend.sections.block-widget',
    'class'           => 'block-widget',
    'fields'          => [
        'block_id'           => 'required',
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
    ],
];
// The icon in the section picker
$sections['block_widget']['picker'] = <<<EOT
    <a href="#" class="picker block-widget" data-template="block_widget">
        <div class="inner">
            <div class="col">
                <i class="fa fa-code"></i>
            </div>
        </div>
    </a>
EOT;

// Form Blocks
$sections['block_form'] = [
    'name'            => 'Form',
    'description'     => 'Renders a form',
    'admin_template'  => 'admin.sections.block-form',
    'public_template' => 'frontend.sections.block-form',
    'class'           => 'block-form',
    'fields'          => [
        'block_id'           => 'required',
        'title'              => '',
        'body'               => '',
        'section_pad_top'    => '',
        'section_pad_bottom' => '',
    ],
];
// The icon in the section picker
$sections['block_form']['picker'] = <<<EOT
    <a href="#" class="picker block-widget" data-template="block_form">
        <div class="inner">
            <div class="col">
                <i class="fa fa-check-square-o"></i>
                <i class="fa fa-dot-circle-o"></i>
            </div>
        </div>
    </a>
EOT;

// One column wysiwyg
$sections['credential_column_wysiwyg'] = [
    'name'            => 'Credential Column Wysiwyg',
    'description'     => 'Credential Column Wysiwyg',
    'admin_template'  => 'admin.sections.credential-column-wysiwyg',
    'public_template' => 'frontend.sections.credential-column-wysiwyg',
    'class'           => 'credential-column-wysiwyg',
    'fields'          => [
        //'section_colors'     => 'required',
        'container_color' => '',
        // 'section_pad_top'    => '',
        // 'section_pad_bottom' => '',

        'level'           => '',
        'description'     => '',
        'stars'           => '',
        'logo'            => '',
        'title'           => '',
        'body'            => 'required',
    ],
    'attachments'     => [
        'logo',
    ],
];

// The icon in the section picker
$sections['credential_column_wysiwyg']['picker'] = <<<EOT
    <a href="#" class="picker credential-column-wysiwyg" data-template="credential_column_wysiwyg">
        <div class="inner">
            <div class="body">&nbsp;</div>
            <div class="col">&nbsp;</div>
        </div>
    </a>
EOT;

/*
|---------------------------------------------------------------------
| Return the built array
|---------------------------------------------------------------------
|
 */

$block_colors = [
    'default'      => 'Transparent',
    'white'        => 'White',
    'grey'         => 'Grey',
    'legacy'       => 'Legacy Blue',
    'progressive'  => 'Progressive Green',
    'credible'     => 'Credible Blue',
    'confident'    => 'Confident Orange',
    'professional' => 'Professional Black',
    'empowering'   => 'Empowering Yellow',
    'passionate'   => 'Passionate Red',
    'dynamic'      => 'Dynamic Pink',
    'inspired'     => 'Inspired Purple',
];

$section_colors = [
    'white'                => 'White',
    'grey'                 => 'Grey',
    'confident-empowering' => 'Orange to Yellow',
    'passionate-dynamic'   => 'Red to Pink',
    'legacy-credible'      => 'Legacy to Credible Blue',
    'inspired-passionate'  => 'Purple to Red',
    'dynamic-confident'    => 'Pink to Orange',
    'inspired-dynamic'     => 'Purple to Pink',
    'legacy-progressive'   => 'Legacy blue to Green',
    'dynamic-pale-pink'    => 'Pink to Pale Pink',
];

$credential_colors = [
    'default'                 => 'Transparent',
    'credential-pink'         => 'Pink',
    'credential-light-blue'   => 'Light blue',
    'credential-purple'       => 'Purple',
    'credential-light-purple' => 'Light purple',
    'credential-yellow'       => 'Yellow',
    'credential-light-orange' => 'Light orange',
    'credential-orange'       => 'Orange',
    'credential-red'          => 'Red',
    'credential-red'          => 'Red',
    'credential-light-green'  => 'Light green',
    'credential-mid-green'    => 'Mid green',
    'credential-green'        => 'Green',
    'credential-blue'         => 'Blue',

    // Expert
    'credential-exprt-purple' => 'Expert Purple',
    'credential-exprt-green'  => 'Expert Green',
    'credential-exprt-yellow' => 'Expert Yellow',
    'credential-exprt-red'    => 'Expert Red',
];

return [

    // The available sections types
    'sections' => $sections,

    'block_colors' => $block_colors,

    'section_colors' => $section_colors,

    'credential_colors' => $credential_colors,

    'options' => [
        'one_column_wysiwyg'            => [
            'colors'         => $block_colors,
            'section_colors' => $section_colors,
            'classes'        => 'xxxxxx',
            'mapping'        => [
                'body' => [
                    'column_one_content',
                    'column_two_content',
                    'column_three_content',
                ],
            ],
        ],
        'one_column_centered_wysiwyg'   => [
            'section_colors' => $section_colors,
            'mapping'        => [
                'body' => [
                    'column_one_content',
                    'column_two_content',
                    'column_three_content',
                ],
            ],
        ],
        'two_column_wysiwyg'            => [
            'colors'         => $block_colors,
            'section_colors' => $section_colors,
            'mapping'        => [
                'section_title'      => [
                    'title',
                ],
                'column_one_content' => [
                    'body',
                    'description',
                ],
                'column_two_content' => [
                    'column_three_content',
                ],
            ],
        ],
        'three_column_wysiwyg'          => [
            'colors'         => $block_colors,
            'section_colors' => $section_colors,
            'mapping'        => [
                'section_title'      => [
                    'title',
                ],
                'column_one_content' => [
                    'body',
                    'description',
                ],
                'column_two_content' => [
                    'column_three_content',
                ],
            ],
        ],
        'two_column_full_width_wysiwyg' => [
            'colors'  => $block_colors,
            'mapping' => [
                'column_one_content' => [
                    'body',
                ],
            ],
        ],
        'download'                      => [
            'colors' => $block_colors,
        ],
        'video'                         => [
            'colors'         => $block_colors,
            'section_colors' => $section_colors,
        ],
        'video_testimonial'             => [
            'colors'         => $block_colors,
            'section_colors' => $section_colors,
        ],
        'video_list'                    => [
            'colors'         => $block_colors,
            'section_colors' => $section_colors,
        ],
        'block_widget'                  => [
            // Add any options here
        ],
        'block_form'                    => [
            // Add any options here
        ],
        'action'                        => [
            // Add any options here
        ],
        'credential_column_wysiwyg'     => [
            'colors'  => $credential_colors,
            'classes' => 'xxxxxx',
        ],
    ],
];
