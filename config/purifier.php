<?php

return [

    'version' => filemtime(__FILE__),

    'encoding'  => 'UTF-8',
    'finalize'  => true,
    'preload'   => false,
    'cachePath' => storage_path('purifier'),

    // Adding these to the HTMLDefinition

    'custom_definition' => [
        'span' => [
            'data-attachment-id'      => 'CDATA',
            'data-attachment-align'   => 'CDATA',
            'data-attachment-caption' => 'CDATA',
            'data-attachment-type'    => 'CDATA',
            'data-attachment-mode'    => 'CDATA',
            'data-attachment-size'    => 'CDATA',
            'data-icon'               => 'CDATA',
            'data-icon-id'            => 'CDATA',
            'data-target'             => 'CDATA',
            'data-text'               => 'CDATA',
            'data-url'                => 'CDATA',
            'data-widget'             => 'CDATA',
            'data-icon-class'         => 'CDATA',
            'data-video-url'          => 'CDATA',
        ],
        'mark' => 'Inline',
        'div'  => [
            'data-video-url' => 'CDATA',
        ],
        'a'    => [
            'data-video-url' => 'CDATA',
        ],
    ],

    'settings' => [
        // Default is only loaded if you dont choose a format (eg basic_html)
        'default'    => [
            'HTML.Doctype'             => 'HTML 4.01 Transitional',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],
        'basic_html' => [
            'AutoFormat.AutoParagraph'          => false,
            'AutoFormat.Linkify'                => false,
            'HTML.Allowed'                      => 'mark,p,br,ul[class],li[class],ol,b,strong,i[class]',
            'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
            'AutoFormat.RemoveEmpty'            => true,
        ],
        'titles'     => [
            'AutoFormat.AutoParagraph' => false,
            'HTML.Allowed'             => 'br',
        ],
        'full_html'  => [
            'AutoFormat.AutoParagraph'          => false,
            'AutoFormat.Linkify'                => true,
            "HTML.SafeIframe"                   => true,
            "URI.SafeIframeRegexp"              => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
            'HTML.Allowed'                      => 'h1,h2,h3,h4,h5,h6,div,b,strong,i,em,a,ul,ol,li,p,br,hr,span,img,abbr,sup,sub,table,thead,tbody,tfoot,th,tr,td,figure,figcaption,blockquote,iframe',
            'CSS.AllowedProperties'             => 'font-weight,text-decoration,padding-left,padding-right,color,background-color,text-align,width,border',
            'Attr.EnableID'                     => true,
            'Attr.AllowedFrameTargets'          => ['_blank'],
            'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
            'AutoFormat.RemoveEmpty'            => false,
            'HTML.AllowedAttributes'            => [
                '*.class', '*.id', '*.style', '*.align',

                'a.href', 'a.target', 'a.title', 'a.rel', 'a.name',
                'img.src', 'img.width', 'img.height', 'img.alt',
                'td.rowspan', 'td.colspan', 'td.width', 'td.valign',
                'th.rowspan', 'th.colspan', 'th.width', 'th.valign',
                'table.width', 'table.style', 'table.cellpadding', 'table.cellspacing',
                'abbr.title',
                'iframe.src',

                // Attachments and Icons
                'span.data-attachment-id',
                'span.data-attachment-align',
                'span.data-attachment-caption',
                'span.data-attachment-type',
                'span.data-attachment-mode',
                'span.data-attachment-size',
                'span.data-icon',
                'span.data-target',
                'span.data-icon-id',
                'span.data-text',
                'span.data-url',
                'span.data-widget',
                'span.data-icon-class',
                'a.data-video-url',
                'span.data-video-url',
                'div.data-video-url',
            ],
        ],
        'youtube'       => [
            "HTML.SafeIframe"      => true,
            "URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
        ],
        'imported_data' => [
            'HTML.Doctype'                  => 'HTML 4.01 Transitional',
            'HTML.Allowed'                  => 'div,b,strong,i,em,a[href|title|target],ul,ol,li,p,br,img[width|height|alt|src],abbr,sup,sub',
            'AutoFormat.AutoParagraph'      => false,
            'AutoFormat.RemoveEmpty'        => true,
            'AutoFormat.AutoParagraph'      => false,
            'AutoFormat.Linkify'            => false,
            "HTML.SafeIframe"               => false,
            'Core.Encoding'                 => 'utf-8',
            'Core.EscapeNonASCIICharacters' => true,
        ],
    ],

];
