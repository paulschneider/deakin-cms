/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [
      { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
      { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'] },
      { name: 'document', groups: ['mode'] },
      '/',
      { name: 'links' },
      { name: 'insert' },
      { name: 'styles' },
    ];

    config.extraAllowedContent = 'i';

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Image';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';

    config.extraPlugins = 'attachment,justify,icons,buttons,fontawesome,actionbuttons,sourcedialog';
    config.removePlugins = 'sourcearea';

    config.disallowedContent = 'div(!attachment-preview)';

    config.contentsCss = '/assets/css/admin/editor.css';

    config.stylesSet = 'my_styles:/assets/js/admin/ck-styles.js';

    CKEDITOR.dtd.$removeEmpty.span = 0;

  };

