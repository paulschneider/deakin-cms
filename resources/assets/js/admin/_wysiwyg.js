(function($) {

  // Extend CMSAdmin object
  CMSAdmin.wysiwyg = function($editor) {
    var id = $editor.attr('id'),
        name = $editor.attr('name'),
        type = 'full';

    // Type types of config
    var editorConfig = {
      full: '/assets/js/admin/ck-full.js',
      basic: '/assets/js/admin/ck-basic.js',
      micro: '/assets/js/admin/ck-micro.js'
    };

    if ($editor.hasClass('basic')) type = 'basic';
    if ($editor.hasClass('micro')) type = 'micro';

    if (id === undefined) id = name;

    // Make sure that the instance is distroyed
    if (CKEDITOR.instances[id]) CKEDITOR.instances[id].destroy(true);

    if ($editor.hasClass('inline-editor')) {
      CKEDITOR.inline( id , {
        customConfig: editorConfig[type]
      }).on('change', function(e) {
        $(this.element.$).html(e.editor.getData());
      });
    } else {
      // Initailize the instance
      CKEDITOR.replace( id , {
        customConfig: editorConfig[type]
      });
    }

    if (_ckImageSizes !== undefined) {
      CKEDITOR.ckImageSize = _ckImageSizes;
    }
  };

  /**
   * CK EDITOR
   */
  if ($('textarea.wysiwyg').length) {

    // Required to make CK Editor load.
    window.CKEDITOR_BASEPATH = '/assets/js/ckeditor/';

    $.getScript(window.CKEDITOR_BASEPATH + 'ckeditor.js', function() {
      CKEDITOR.disableAutoInline = true;

      var today = new Date();

      CKEDITOR.timestamp = 'v' + today.toISOString().substring(0, 10);
      CKEDITOR.plugins.addExternal( 'attachment', '/assets/js/admin/plugins/attachment/plugin.js' );
      CKEDITOR.plugins.addExternal( 'concertina', '/assets/js/admin/plugins/concertina/plugin.js' );
      CKEDITOR.plugins.addExternal( 'oembed', '/assets/vendor/ckeditor-oembed/plugin.js' );
      CKEDITOR.plugins.addExternal( 'justify', '/assets/js/admin/plugins/justify/plugin.js' );
      CKEDITOR.plugins.addExternal( 'icons', '/assets/js/admin/plugins/icons/plugin.js' );
      CKEDITOR.plugins.addExternal( 'buttons', '/assets/js/admin/plugins/buttons/plugin.js' );
      CKEDITOR.plugins.addExternal( 'fontawesome', '/assets/js/admin/plugins/fontawesome/plugin.js' );
      CKEDITOR.plugins.addExternal( 'actionbuttons', '/assets/js/admin/plugins/actionbuttons/plugin.js' );
      CKEDITOR.plugins.addExternal( 'columns', '/assets/js/admin/plugins/columns/plugin.js' );
      CKEDITOR.plugins.addExternal( 'three-columns', '/assets/js/admin/plugins/three-columns/plugin.js' );

      $('textarea.wysiwyg.micro').each(function(){
        CMSAdmin.wysiwyg($(this));
      });

      $('textarea.wysiwyg.basic').each(function(){
        CMSAdmin.wysiwyg($(this));
      });

      $('textarea.wysiwyg').not('.basic, .micro').each(function() {
        CMSAdmin.wysiwyg($(this));
      });
    });
  }

}(jQuery));
